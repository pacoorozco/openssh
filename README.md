# Creating and loading private/public OpenSSH keys

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pacoorozco/openssh.svg?style=flat-square)](https://packagist.org/packages/spatie/crypto)
![Tests](https://github.com/pacoorozco/openssh/workflows/Tests/badge.svg)


This package allows you to easily generate OpenSSH private/public key pairs, which can be used as authentication method in SSH connections.

```php
use PacoOrozco\OpenSSH\KeyPair;
use PacoOrozco\OpenSSH\PrivateKey;
use PacoOrozco\OpenSSH\PublicKey;

// generating an OpenSSH key pair
[$privateKey, $publicKey] = (new KeyPair())->generate();

// when passing paths, the generated keys will be written those paths
(new KeyPair())->generate($pathToPrivateKey, $pathToPublicKey);

$data = 'my secret data';

$privateKey = PrivateKey::fromFile($pathToPrivateKey);
$encryptedData = $privateKey->encrypt($data); // returns something unreadable

$publicKey = PublicKey::fromFile($pathToPublicKey);
$decryptedData = $publicKey->decrypt($encryptedData); // returns 'my secret data'
```

Most functions in this package are wrappers around `phpseclib` functions.

## Installation

You can install the package via composer:

```bash
composer require pacoorozco/openssh
```

## Usage

You can generate a key pair using the `generate` function on the `KeyPair` class.

```php
use PacoOrozco\OpenSSH\KeyPair;

[$privateKey, $publicKey] = (new KeyPair())->generate();
```

You can write the keys to disk, by passing paths to the `generate` function.

```php
// when passing paths, the generate keys will to those paths
(new KeyPair())->generate($pathToPrivateKey, $pathToPublicKey)
```

### Loading keys

To load a key from a file use the `fromFile` static method.

```php
PacoOrozco\OpenSSH\PrivateKey::fromFile($pathToPrivateKey);
PacoOrozco\OpenSSH\PublicKey::fromFile($pathToPublicKey);
```

Alternatively, you can also create a key object using a string.

```php
PacoOrozco\OpenSSH\PrivateKey::fromString($privateKeyString);
PacoOrozco\OpenSSH\PublicKey::fromString($publicKeyString);
```

### Encrypting a message with a public key, decrypting with the private key

Here's how you can encrypt data using the public key, and how to decrypt it using the private key.

```php
$data = 'my secret data';

$publicKey = PacoOrozco\OpenSSH\PublicKey::fromFile($pathToPublicKey);
$encryptedData = $publicKey->encrypt($data); // encrypted data contains something unreadable

$privateKey = PacoOrozco\OpenSSH\PrivateKey::fromFile($pathToPrivateKey);
$decryptedData = $privateKey->decrypt($encryptedData); // decrypted data contains 'my secret data'
```

If `decrypt` cannot decrypt the given data (maybe a non-matching public key was used to encrypt the data, or maybe tampered with the data), an exception of class `PacoOrozco\OpenSSH\Exceptions\CouldNotDecryptData` will be thrown.

### Determining if the data can be decrypted

The `PrivateKey` class has a `canDecrypt` method to determine if given data can be decrypted.

```php
PacoOrozco\OpenSSH\PrivateKey::fromFile($pathToPrivateKey)->canDecrypt($data); // returns a boolean;
```

### Signing and verifying data

The `PrivateKey` class has a method `sign` to generate a signature for the given data. The `verify` method on the `PublicKey` class can be used to verify if a signature is valid for the given data.

If `verify` returns `true`, you know for certain that the holder of the private key signed the message, and that it was not tampered with.

```php
$signature = PacoOrozco\OpenSSH\PrivateKey::fromFile($pathToPrivateKey)->sign('my message'); // returns a string

$publicKey = PacoOrozco\OpenSSH\PublicKey::fromFile($pathToPublicKey);

$publicKey->verify('my message', $signature) // returns true;
$publicKey->verify('my modified message', $signature) // returns false;
```

### Validating inputs (Laravel)

You can use this library to validate form inputs. 

To validate if an input is a valid public or private key you can use:
```php
use PacoOrozco\OpenSSH\Rules\PublicKeyRule;

[...]

    public function rules(): array
    {
        return [
            'public_key' => [
                new PublicKeyRule(),
            ],
            'private_key' => [
                new PrivateKeyRule(),
            ],
        ];
    }
}
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
