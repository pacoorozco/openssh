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

use PacoOrozco\OpenSSH\KeyPair;

class KeyPairTest extends TestCase
{
    /** @test */
    public function it_can_generate_a_private_and_public_key()
    {
        [$privateKey, $publicKey] = (new KeyPair())->generate();

        $this->assertStringStartsWith('-----BEGIN OPENSSH PRIVATE KEY-----', $privateKey);
        $this->assertStringStartsWith('ssh-rsa', $publicKey);
    }

    /** @test */
    public function it_can_write_keys_to_disk()
    {
        $privateKeyPath = $this->getTempPath('privateKey');
        $publicKeyPath = $this->getTempPath('publicKey');

        if (file_exists($privateKeyPath)) {
            unlink($privateKeyPath);
        }

        if (file_exists($publicKeyPath)) {
            unlink($publicKeyPath);
        }

        (new KeyPair())->generate(
            $privateKeyPath,
            $publicKeyPath,
        );

        $this->assertStringStartsWith('-----BEGIN OPENSSH PRIVATE KEY-----', file_get_contents($privateKeyPath));
        $this->assertStringStartsWith('ssh-rsa', file_get_contents($publicKeyPath));
    }
}
