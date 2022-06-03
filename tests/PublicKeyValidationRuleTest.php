<?php

namespace PacoOrozco\OpenSSH\Tests;

use Illuminate\Support\Facades\Validator;
use PacoOrozco\OpenSSH\PrivateKey;
use PacoOrozco\OpenSSH\PublicKey;
use PacoOrozco\OpenSSH\Rules\PublicKeyRule;

class PublicKeyValidationRuleTest extends TestCase
{

    /** @test */
    public function it_should_pass_when_key_is_public()
    {
        $validator = Validator::make(
            ['key' => PublicKey::fromFile($this->getStub('publicKey'))],
            ['key' => new PublicKeyRule()]
        );

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_should_not_pass_when_key_is_not_public()
    {
        $validator = Validator::make(
            ['key' => PrivateKey::fromFile($this->getStub('privateKey'))],
            ['key' => new PublicKeyRule()]
        );

        $this->assertFalse($validator->passes());
    }
}
