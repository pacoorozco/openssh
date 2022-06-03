<?php

namespace PacoOrozco\OpenSSH\Tests;

use PacoOrozco\OpenSSH\Exceptions\NoKeyLoadedException;
use PacoOrozco\OpenSSH\PublicKey;

class PublicKeyTest extends TestCase
{
    /** @test */
    public function it_should_throw_an_exception_if_key_is_not_valid()
    {
        $this->expectException(NoKeyLoadedException::class);

        PublicKey::fromString('invalid-key');
    }

    /** @test */
    public function it_should_load_a_public_key_from_an_string()
    {
        $key = PublicKey::fromString(file_get_contents($this->getStub('publicKey')));

        $this->assertInstanceOf(PublicKey::class, $key);
    }

    /** @test */
    public function it_should_load_a_public_key_from_a_file(): void
    {
        $key = PublicKey::fromFile($this->getStub('publicKey'));

        $this->assertInstanceOf(PublicKey::class, $key);
    }

    /** @test */
    public function it_should_encrypt_a_text(): void
    {
        $key = PublicKey::fromFile($this->getStub('publicKey'));

        $this->assertNotEquals('foo bar baz', $key->encrypt('foo bar baz'));
    }

    /** @test */
    public function it_should_get_the_md5_fingerprint_of_a_key(): void
    {
        $key = PublicKey::fromFile($this->getStub('publicKey'));

       $this->assertEquals('be:21:c1:98:e9:bf:5c:da:8a:5a:b5:ad:e3:0c:2b:e8', $key->getFingerPrint('md5'));
    }

    /** @test */
    public function it_should_get_the_sha256_fingerprint_of_a_key(): void
    {
        $key = PublicKey::fromFile($this->getStub('publicKey'));

        $this->assertEquals('MLD9i0XJRUMJOUTtp4f5k7fXCW6uIRDsKoqvuq9+u3Q', $key->getFingerPrint('sha256'));
    }

    /** @test */
    public function it_should_return_an_string_with_the_content_of_the_key(): void
    {
        $publicKey = PublicKey::fromFile($this->getStub('publicKey'));

        $this->assertStringStartsWith('ssh-rsa', $publicKey);
    }
}
