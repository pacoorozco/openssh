<?php

namespace PacoOrozco\OpenSSH\Rules;

use Illuminate\Contracts\Validation\Rule;
use PacoOrozco\OpenSSH\Exceptions\NoKeyLoadedException;
use PacoOrozco\OpenSSH\PrivateKey;

class PrivateKeyRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        try {
            if (is_null($value)) {
                return false;
            }

            PrivateKey::fromString($value);
        } catch (NoKeyLoadedException) {
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return 'The :attribute must be a valid private key.';
    }
}
