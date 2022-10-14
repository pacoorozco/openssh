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

    /**
     * @test
     * @dataProvider providesPublicKeyToTest
     */
    public function it_should_load_a_public_key_from_an_string(
        string $filename,
    )
    {
        $key = PublicKey::fromString(file_get_contents($this->getStub($filename)));

        $this->assertInstanceOf(PublicKey::class, $key);
    }

    public function providesPublicKeyToTest(): \Generator
    {
        yield 'RSA public key' => [
            'filename' => 'id_rsa.pub',
            'fingerprints' => [
                'sha256' => 'MLD9i0XJRUMJOUTtp4f5k7fXCW6uIRDsKoqvuq9+u3Q',
                'md5' => 'be:21:c1:98:e9:bf:5c:da:8a:5a:b5:ad:e3:0c:2b:e8',
            ],
        ];

        yield 'ed25519 public key' => [
            'filename' => 'id_ed25519.pub',
            'fingerprints' => [
                'sha256' => 'Y+Ku1JVZ4QhHjRBEWMY1W+oxD3On3tUuyQmOnHTp4wI',
                'md5' => '30:7c:93:39:05:e1:18:6a:45:89:e1:9d:25:28:a0:04',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providesPublicKeyToTest
     */
    public function it_should_load_a_public_key_from_a_file(
        string $filename,
    ): void
    {
        $key = PublicKey::fromFile($this->getStub($filename));

        $this->assertInstanceOf(PublicKey::class, $key);
    }

    /**
     * @test
     * @dataProvider providesPublicKeyToTest
     */
    public function it_should_encrypt_a_text_using_RSA_public_key(): void
    {
        $key = PublicKey::fromFile($this->getStub('id_rsa.pub'));

        $this->assertNotEquals('foo bar baz', $key->encrypt('foo bar baz'));
    }

    /**
     * @test
     * @dataProvider providesPublicKeyToTest
     */
    public function it_should_get_the_sha256_fingerprint_of_a_key(
        string $filename,
        array  $fingerprints,
    ): void
    {
        $key = PublicKey::fromFile($this->getStub($filename));

        $this->assertEquals($fingerprints['sha256'], $key->getFingerPrint('sha256'));
    }

    /**
     * @test
     * @dataProvider providesPublicKeyToTest
     */
    public function it_should_get_the_md5_fingerprint_of_a_key(
        string $filename,
        array  $fingerprints,
    ): void
    {
        $key = PublicKey::fromFile($this->getStub($filename));

        $this->assertEquals($fingerprints['md5'], $key->getFingerPrint('md5'));
    }
}
