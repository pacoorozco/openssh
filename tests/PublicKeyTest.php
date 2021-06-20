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

use PacoOrozco\OpenSSH\Exceptions\FileDoesNotExist;
use PacoOrozco\OpenSSH\Exceptions\InvalidPublicKey;
use PacoOrozco\OpenSSH\PublicKey;

class PublicKeyTest extends TestCase
{
    /** @test */
    public function the_public_key_class_can_load_valid_key(): void
    {
        $key = PublicKey::fromFile($this->getStub('publicKey'));
        $this->assertInstanceOf(PublicKey::class, $key);
    }

    /** @test */
    public function a_public_key_will_throw_an_exception_if_it_is_invalid(): void
    {
        $this->expectException(InvalidPublicKey::class);

        PublicKey::fromString('invalid-key');
    }

    /** @test */
    public function it_will_throw_an_exception_when_there_is_no_public_key_file_at_the_path_given(): void
    {
        $this->expectException(FileDoesNotExist::class);

        PublicKey::fromFile('non-existing-file');
    }
}
