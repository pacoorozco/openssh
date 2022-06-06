<?php

namespace PacoOrozco\OpenSSH;

use PacoOrozco\OpenSSH\Exceptions\BadDecryptionException;
use PacoOrozco\OpenSSH\Exceptions\FileNotFoundException;
use PacoOrozco\OpenSSH\Exceptions\NoKeyLoadedException;
use phpseclib3\Crypt\RSA;

class PrivateKey
{
    const KEY_OUTPUT_FORMAT = 'OpenSSH';

    protected function __construct(
        protected \phpseclib3\Crypt\Common\PrivateKey $key
    ) {
    }

    public static function generate(int $bits = 2048): self
    {
        return new self(RSA::createKey($bits));
    }

    /**
     * @throws \PacoOrozco\OpenSSH\Exceptions\NoKeyLoadedException
     */
    public static function fromString(string $keyContent): self
    {
        try {
            $key = RSA::loadPrivateKey($keyContent);
        } catch (\Throwable $exception) {
            throw new NoKeyLoadedException($exception->getMessage());
        }

        return new self($key);
    }

    /**
     * @throws \PacoOrozco\OpenSSH\Exceptions\NoKeyLoadedException
     * @throws \PacoOrozco\OpenSSH\Exceptions\FileNotFoundException
     */
    public static function fromFile(string $filename): self
    {
        if (! $keyContent = file_get_contents($filename)) {
            throw new FileNotFoundException('The file was not found: '.$filename);
        }

        return self::fromString($keyContent);
    }

    public function encrypt(string $text): string
    {
        return $this->key->getPublicKey()->encrypt($text);
    }

    public function canDecrypt(string $ciphertext): bool
    {
        try {
            $this->decrypt($ciphertext);
        } catch (BadDecryptionException) {
            return false;
        }

        return true;
    }

    /**
     * @throws \PacoOrozco\OpenSSH\Exceptions\BadDecryptionException
     */
    public function decrypt(string $ciphertext): string
    {
        $decrypted = $this->key->decrypt($ciphertext);

        if (is_null($decrypted)) {
            throw new BadDecryptionException();
        }

        return $decrypted;
    }

    public function sign(string $text): string
    {
        return $this->key->sign($text);
    }

    /**
     * @throws \PacoOrozco\OpenSSH\Exceptions\NoKeyLoadedException
     */
    public function getPublicKey(): PublicKey
    {
        return PublicKey::fromString($this->key->getPublicKey());
    }

    public function toFile(string $filename): bool
    {
        return false !== file_put_contents($filename, $this);
    }

    public function __toString(): string
    {
        return $this->key->toString(self::KEY_OUTPUT_FORMAT);
    }
}
