<?php

namespace PacoOrozco\OpenSSH\Rules;

use Illuminate\Contracts\Validation\Rule;
use PacoOrozco\OpenSSH\Exceptions\NoKeyLoadedException;
use PacoOrozco\OpenSSH\PublicKey;

class PublicKeyRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        try {
            PublicKey::fromString($value);
        } catch (NoKeyLoadedException) {
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return 'The :attribute must be a valid public key.';
    }
}
