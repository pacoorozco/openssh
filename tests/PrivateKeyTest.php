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

namespace PacoOrozco\OpenSSH\Tests;

use PacoOrozco\OpenSSH\Exceptions\InvalidPrivateKey;
use PacoOrozco\OpenSSH\PrivateKey;

class PrivateKeyTest extends TestCase
{
    /*

    public function the_private_key_class_can_detect_invalid_data()
    {
        $originalData = 'secret data';
        $publicKey = PublicKey::fromFile($this->getStub('publicKey'));
        $encryptedData = $publicKey->encrypt($originalData);
        $privateKey = PrivateKey::fromFile($this->getStub('privateKey'));

        $modifiedDecrypted = $encryptedData . 'modified';
        $this->assertFalse($privateKey->canDecrypt($modifiedDecrypted));

        $this->expectException(CouldNotDecryptData::class);
        $privateKey->decrypt($modifiedDecrypted);
    }
    */

    /** @test */
    public function the_private_key_class_can_load_valid_key(): void
    {
        $key = PrivateKey::fromFile($this->getStub('privateKey'));
        $this->assertInstanceOf(PrivateKey::class, $key);
    }

    /** @test */
    public function a_private_key_will_throw_an_exception_if_it_is_invalid()
    {
        $this->expectException(InvalidPrivateKey::class);

        PrivateKey::fromString('invalid-key');
    }
}
