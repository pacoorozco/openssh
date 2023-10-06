# Changelog
All notable changes to this package will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/) and this project adheres to [Semantic Versioning](https://semver.org/).

## 0.5.1 - 2023-10-06

### Changed
- Bump `phpseclib/phpseclib` from 3.0.14 to 3.0.23
- [CI] Bump `phpunit/phpunit` from 9.5.0 to 9.6.13
- [CI] Bump `orchestra/testbench` from 7.0.0 to 8.12.2

### Remove
- Support for PHP 8.0 version

## 0.5.0 - 2023-03-03
### Added
- Support for [Laravel 10.x](https://laravel.com/docs/10.x).

## 0.4.0 - 2022-10-14
### Added
- Support to **ed25519 public keys**. ([#7][i7])

[i7]: https://github.com/pacoorozco/openssh/issues/7

## 0.3.0 - 2022-06-06
### Added
- `PrivateKey::generate()` creates a new private key. You can obtain the corresponding public key with the `getPublicKey()` method.
- `toFile()` method to store private and public keys into a file.

### Changed
- `PrivateKey` and `PublicKey` classes use internally `\phpseclib3\Crypt\Common\PrivateKey` and `\phpseclib3\Crypt\Common\PublicKey` interfaces. 
- `getPublicKey()` returns a `\PacoOrozco\OpenSSH\PublicKey` instead of the `\phpseclib3\Crypt\RSA\PublicKey`. 
- `getFingerPrint()` has changed the signature. An algorithm should be submitted: `sha256` or `md5`. 
- The `PrivateKeyRule` and the `PublicKeyRule` returns false if the key is null.
### Removed
- `KeyPair` class has been removed. Use `PrivateKey::generate()` and `getPublicKey()` methods instead.
- Removed `\PacoOrozco\OpenSSH\Exceptions\InvalidPrivateKey` exception, use `\PacoOrozco\OpenSSH\Exceptions\NoKeyLoadedException` instead.
- Removed `\PacoOrozco\OpenSSH\Exceptions\CouldNotDecryptData` exception, use `\PacoOrozco\OpenSSH\Exceptions\BadDecryptionException` instead.

## 0.2.1 - 2021-12-02
### Changed
- Bump `phpseclib/phpseclib` to `v3.0.12`.

## 0.2.0 - 2021-06-20
Initial release
