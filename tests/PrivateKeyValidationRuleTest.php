<?php

namespace PacoOrozco\OpenSSH\Tests;

use Illuminate\Support\Facades\Validator;
use PacoOrozco\OpenSSH\PrivateKey;
use PacoOrozco\OpenSSH\PublicKey;
use PacoOrozco\OpenSSH\Rules\PrivateKeyRule;

class PrivateKeyValidationRuleTest extends TestCase
{
    /** @test */
    public function it_should_pass_when_key_is_private()
    {
        $validator = Validator::make(
            ['key' => PrivateKey::fromFile($this->getStub('id_rsa'))],
            ['key' => new PrivateKeyRule()]
        );

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_should_not_pass_when_key_is_not_private()
    {
        $validator = Validator::make(
            ['key' => PublicKey::fromFile($this->getStub('id_rsa.pub'))],
            ['key' => new PrivateKeyRule()]
        );

        $this->assertFalse($validator->passes());
    }

    /** @test */
    public function it_should_not_pass_when_key_is_null()
    {
        $validator = Validator::make(
            ['key' => null],
            ['key' => new PrivateKeyRule()]
        );

        $this->assertFalse($validator->passes());
    }
}
