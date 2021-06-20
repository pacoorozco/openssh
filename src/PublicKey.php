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

use PacoOrozco\OpenSSH\Exceptions\FileDoesNotExist;
use PacoOrozco\OpenSSH\Exceptions\InvalidPublicKey;
use phpseclib3\Crypt\RSA;

class PublicKey
{
    protected RSA\PublicKey $publicKey;

    /**
     * @throws \PacoOrozco\OpenSSH\Exceptions\InvalidPublicKey
     */
    public function __construct(string $publicKeyString)
    {
        try {
            $this->publicKey = RSA::loadFormat('OpenSSH', $publicKeyString);
        } catch (\Throwable) {
            throw InvalidPublicKey::make();
        }
    }

    /**
     * @throws \PacoOrozco\OpenSSH\Exceptions\InvalidPublicKey
     */
    public static function fromString(string $publicKeyString): self
    {
        return new static($publicKeyString);
    }

    /**
     * @throws \PacoOrozco\OpenSSH\Exceptions\InvalidPublicKey
     * @throws \PacoOrozco\OpenSSH\Exceptions\FileDoesNotExist
     */
    public static function fromFile(string $pathToPublicKey): self
    {
        if (!file_exists($pathToPublicKey)) {
            throw FileDoesNotExist::make($pathToPublicKey);
        }

        $publicKeyString = file_get_contents($pathToPublicKey);

        return new static($publicKeyString);
    }

    public function encrypt(string $data): bool|string
    {
        return $this->publicKey->encrypt($data);
    }

    public function verify(string $data, string $signature): bool
    {
        return $this->publicKey->verify($data, $signature);
    }

    public function getFingerPrint(string $algorithm = 'md5'): string
    {
        return $this->publicKey->getFingerprint($algorithm);
    }
}
