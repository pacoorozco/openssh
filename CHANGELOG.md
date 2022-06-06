# Changelog
All notable changes to this package will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/) and this project adheres to [Semantic Versioning](https://semver.org/).

## 0.3.0 - 2022-06-06
### Added
- `PrivateKey::generate()` creates a new private key. You can obtain the corresponding public key with the `getPublicKey()` method.
- `toFile()` method to store private and public keys into a file.
- 
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
