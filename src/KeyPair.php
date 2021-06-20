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

use phpseclib3\Crypt\RSA;

class KeyPair
{
    protected int $privateKeyBits;

    public function __construct(
        int $privateKeyBits = 2048,
    ) {
        $this->privateKeyBits = $privateKeyBits;
    }

    public function generate(
        string $privateKeyPath = '',
        string $publicKeyPath = ''
    ): array {
        /** @var \phpseclib3\Crypt\RSA\PrivateKey $rsa */
        $asymmetricKey = RSA::createKey($this->privateKeyBits);

        $publicKey = $asymmetricKey->getPublicKey()->toString('OpenSSH');
        $privateKey = $asymmetricKey->toString('OpenSSH');

        if ($privateKeyPath !== '') {
            file_put_contents($privateKeyPath, $privateKey);
        }

        if ($publicKeyPath !== '') {
            file_put_contents($publicKeyPath, $publicKey);
        }

        return [$privateKey, $publicKey];
    }
}
