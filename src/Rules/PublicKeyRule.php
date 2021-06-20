<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2020 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2020 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace PacoOrozco\OpenSSH\Rules;

use App\Libs\RsaSshKey\RsaSshKey;
use Illuminate\Contracts\Validation\Rule;
use PacoOrozco\OpenSSH\Exceptions\InvalidPublicKey;
use PacoOrozco\OpenSSH\PublicKey;

class PublicKeyRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        try {
            PublicKey::fromString($value);
        } catch (InvalidPublicKey) {
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return 'The :attribute must be a valid OpenSSH public key.';
    }
}
