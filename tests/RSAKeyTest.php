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

use PacoOrozco\OpenSSH\PrivateKey;
use PacoOrozco\OpenSSH\PublicKey;

class RSAKeyTest extends TestCase
{
    protected PrivateKey $privateKey;

    protected PublicKey $publicKey;

    protected function setUp(): void
    {
        $this->privateKey = PrivateKey::fromFile($this->getStub('id_rsa'));

        $this->publicKey = PublicKey::fromFile($this->getStub('id_rsa.pub'));
    }

    /** @test */
    public function a_public_key_can_be_used_to_encrypt_data_that_can_be_decrypted_by_a_private_key()
    {
        $originalData = 'secret data';

        $encryptedData = $this->publicKey->encrypt($originalData);

        $this->assertNotEquals($originalData, $encryptedData);

        $decryptedData = $this->privateKey->decrypt($encryptedData);

        $this->assertEquals($decryptedData, $originalData);
    }

    /** @test */
    public function it_can_sign_and_verify_a_message()
    {
        $signature = $this->privateKey->sign('my message');

        $this->assertTrue($this->publicKey->verify('my message', $signature));
        $this->assertFalse($this->publicKey->verify('my modified message', $signature));
        $this->assertFalse($this->publicKey->verify('my message', $signature.'- making the signature invalid'));
    }
}
