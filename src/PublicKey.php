<?php

namespace PacoOrozco\OpenSSH;

use PacoOrozco\OpenSSH\Exceptions\FileNotFoundException;
use PacoOrozco\OpenSSH\Exceptions\NoKeyLoadedException;
use phpseclib3\Crypt\RSA;

class PublicKey
{
    const KEY_OUTPUT_FORMAT = 'OpenSSH';

    protected function __construct(
        protected \phpseclib3\Crypt\Common\PublicKey $key
    ) {
    }

    /**
     * @throws \PacoOrozco\OpenSSH\Exceptions\NoKeyLoadedException
     */
    public static function fromString(string $keyContent): self
    {
        try {
            $key = RSA::loadPublicKey($keyContent);
        } catch (\Throwable $exception) {
            throw new NoKeyLoadedException($exception->getMessage());
        }

        return new self($key);
    }

    /**
     * @throws \PacoOrozco\OpenSSH\Exceptions\FileNotFoundException
     * @throws \PacoOrozco\OpenSSH\Exceptions\NoKeyLoadedException
     */
    public static function fromFile(string $filename): self
    {
        if (! $keyContent = file_get_contents($filename)) {
            throw new FileNotFoundException('The file was not found: '.$filename);
        }

        return self::fromString($keyContent);
    }

    public function encrypt(string $plaintext): string
    {
        return $this->key->encrypt($plaintext);
    }

    public function verify(string $text, string $signature): bool
    {
        return $this->key->verify($text, $signature);
    }

    /**
     * Obtain the public key fingerprints.
     *
     * Supported values for $algorithm are 'sha256' and 'md5'.
     * The value returned by this function is identical to what you'd get by running ssh-keygen -lf key.pub
     * on the command line.
     *
     * @param  string  $algorithm
     * @return string
     */
    public function getFingerPrint(string $algorithm): string
    {
        return $this->key->getFingerprint($algorithm);
    }

    public function __toString(): string
    {
        return $this->key->toString(self::KEY_OUTPUT_FORMAT);
    }
}
