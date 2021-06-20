<?php
/*
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2021 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2021 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace PacoOrozco\OpenSSH;

use PacoOrozco\OpenSSH\Exceptions\CouldNotDecryptData;
use PacoOrozco\OpenSSH\Exceptions\FileDoesNotExist;
use PacoOrozco\OpenSSH\Exceptions\InvalidPrivateKey;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\RSA;

class PrivateKey
{
    /** @var \phpseclib3\Crypt\RSA\PrivateKey */
    protected RSA\PrivateKey $privateKey;

    /**
     * @throws \PacoOrozco\OpenSSH\Exceptions\InvalidPrivateKey
     */
    public function __construct(string $privateKeyString)
    {
        try {
            $this->privateKey = RSA::loadFormat('OpenSSH', $privateKeyString);
        } catch (\Throwable) {
            throw InvalidPrivateKey::make();
        }
    }

    /**
     * @throws \PacoOrozco\OpenSSH\Exceptions\InvalidPrivateKey
     */
    public static function fromString(string $privateKeyString): self
    {
        return new static($privateKeyString);
    }

    /**
     * @throws \PacoOrozco\OpenSSH\Exceptions\InvalidPrivateKey
     * @throws \PacoOrozco\OpenSSH\Exceptions\FileDoesNotExist
     */
    public static function fromFile(string $pathToPrivateKey): self
    {
        if (!file_exists($pathToPrivateKey)) {
            throw FileDoesNotExist::make($pathToPrivateKey);
        }

        $privateKeyString = file_get_contents($pathToPrivateKey);

        return new static($privateKeyString);
    }

    public function encrypt(string $data): string
    {
        return $this->privateKey->getPublicKey()->encrypt($data);
    }

    public function canDecrypt(string $data): bool
    {
        try {
            $this->decrypt($data);
        } catch (CouldNotDecryptData) {
            return false;
        }

        return true;
    }

    /**
     * @throws \PacoOrozco\OpenSSH\Exceptions\CouldNotDecryptData
     */
    public function decrypt(string $data): string
    {
        $decrypted = $this->privateKey->decrypt($data);

        if (is_null($decrypted)) {
            throw CouldNotDecryptData::make();
        }

        return $decrypted;
    }

    public function sign(string $data): string
    {
        return $this->privateKey->sign($data);
    }

    public function getPublicKey(): RSA\PublicKey {
        return $this->privateKey->getPublicKey();
    }
}
