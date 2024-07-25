# EncryptionDecryption PHP Class

## Overview

The `EncryptionDecryption` class provides a simple way to encrypt and decrypt data using the OpenSSL library in PHP. It supports AES-256-CTR encryption by default, but you can specify other ciphers supported by OpenSSL if needed.

## Requirements

- PHP 7.0 or higher
- OpenSSL PHP extension (ensure it's loaded)

## Installation

Clone this repository or download the `EncryptionDecryption.php` file and include it in your PHP project.

## Usage

### Class Initialization

To use the `EncryptionDecryption` class, initialize it with your secret key, and optionally specify a cipher, options, and a salt.

```php
$key = "your-secret-key";
$cipher = "aes-256-ctr"; // Optional: defaults to aes-256-ctr if not provided
$options = 0; // Optional: defaults to 0 if not provided
$salt = "unique-salt"; // Optional: defaults to empty string if not provided

$encryption = new EncryptionDecryption($key, $cipher, $options, $salt);
```

### Encrypting Data
To encrypt data, use the encrypt method:

```php
$data = "This is a secret message.";
$encryptedData = $encryption->encrypt($data);
echo "Encrypted: " . $encryptedData . PHP_EOL;
```

### Decrypting Data
To decrypt data, use the decrypt method:

```php
$decryptedData = $encryption->decrypt($encryptedData);
if ($decryptedData === false) {
    echo "Decryption failed or salt mismatch." . PHP_EOL;
} else {
    echo "Decrypted: " . $decryptedData . PHP_EOL;
}
```

### Error Handling
The constructor throws an Exception if the OpenSSL extension is not loaded. Decryption will return false if it fails or if the salt does not match.

### Example
Here is a complete example of how to use the class:

```php
require 'EncryptionDecryption.php'; // Adjust the path as needed

try {
    $key = "your-secret-key";
    $cipher = "aes-256-ctr";
    $options = 0;
    $salt = "unique-salt";

    $data = "This is a secret message.";
    $encryption = new EncryptionDecryption($key, $cipher, $options, $salt);
    
    $encryptedData = $encryption->encrypt($data);
    echo "Encrypted: " . $encryptedData . PHP_EOL;

    $decryptedData = $encryption->decrypt($encryptedData);
    if ($decryptedData === false) {
        echo "Decryption failed or salt mismatch." . PHP_EOL;
    } else {
        echo "Decrypted: " . $decryptedData . PHP_EOL;
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
```

## License
This project is licensed under the MIT License - see the LICENSE file for details.

## Contributing
If you have suggestions or improvements, feel free to create an issue or submit a pull request.
