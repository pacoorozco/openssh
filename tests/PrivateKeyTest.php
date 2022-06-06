<?php

namespace PacoOrozco\OpenSSH\Tests;

use PacoOrozco\OpenSSH\Exceptions\NoKeyLoadedException;
use PacoOrozco\OpenSSH\PrivateKey;
use PacoOrozco\OpenSSH\PublicKey;

class PrivateKeyTest extends TestCase
{
    /** @test */
    public function it_should_throw_an_exception_if_key_is_not_valid()
    {
        $this->expectException(NoKeyLoadedException::class);

        PrivateKey::fromString('invalid-key');
    }

    /** @test */
    public function it_should_load_a_private_key_from_an_string()
    {
        $key = PrivateKey::fromString(file_get_contents($this->getStub('privateKey')));

        $this->assertInstanceOf(PrivateKey::class, $key);
    }

    /** @test */
    public function it_should_load_a_private_key_from_a_file(): void
    {
        $key = PrivateKey::fromFile($this->getStub('privateKey'));

        $this->assertInstanceOf(PrivateKey::class, $key);
    }

    /** @test */
    public function it_should_encrypt_and_decrypt_a_text()
    {
        $key = PrivateKey::fromFile($this->getStub('privateKey'));

        $ciphertext = $key->encrypt('foo bar baz');

        $this->assertTrue($key->canDecrypt($ciphertext));

        $this->assertEquals('foo bar baz', $key->decrypt($ciphertext));
    }

    /** @test */
    public function it_should_return_the_associated_public_key()
    {
        $key = PrivateKey::fromFile($this->getStub('privateKey'));

        $this->assertInstanceOf(PublicKey::class, $key->getPublicKey());
    }

    /** @test */
    public function it_should_write_the_key_to_a_file(): void
    {
        $filename = $this->getTempPath('testing_an_OpenSSH_key');

        // Save a private key into the disk
        $originalKey = PrivateKey::generate();
        $originalKey->toFile($filename);

        // Read the previous saved key
        $savedKey = PrivateKey::fromFile($filename);

        // Checks that is the same key that we saved
        $originalText = 'foo bar baz';
        $cipherText = $originalKey->encrypt($originalText);
        $this->assertEquals($originalText, $savedKey->decrypt($cipherText));
    }

    /** @test */
    public function it_should_return_an_string_with_the_content_of_the_key(): void
    {
        $publicKey = PrivateKey::fromFile($this->getStub('privateKey'));

        $this->assertStringStartsWith('-----BEGIN OPENSSH PRIVATE KEY-----', $publicKey);
    }
}
