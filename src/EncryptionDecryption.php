<?php
class EncryptionDecryption
{
    private $cipher;
    private $key;
    private $options;
    private $salt;

    public function __construct($key, $cipher = "aes-256-ctr", $options = 0, $salt = "")
    {
        if (!extension_loaded('openssl')) {
            throw new Exception('The OpenSSL extension is not loaded.');
        }

        $this->cipher = $cipher;
        $this->options = $options;
        $this->salt = $salt;
        $this->key = hash('sha256', $key . $salt, true);
    }

    public function encrypt($data)
    {
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);
        $encrypted = openssl_encrypt($data . $this->salt, $this->cipher, $this->key, $this->options, $iv);
        return base64_encode($iv . $encrypted);
    }

    public function decrypt($encryptedData)
    {
        $encryptedData = base64_decode($encryptedData);
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = substr($encryptedData, 0, $ivLength);
        $encrypted = substr($encryptedData, $ivLength);
        $decrypted = openssl_decrypt($encrypted, $this->cipher, $this->key, $this->options, $iv);

        if (!$decrypted || substr($decrypted, -strlen($this->salt)) !== $this->salt) {
            return false; // Decryption failed or salt mismatch
        }

        return substr($decrypted, 0, -strlen($this->salt)); // Remove the salt from the end
    }
}

// Example usage
try {
    $key = "your-secret-key";
    $cipher = "aes-256-ctr"; // Optional: defaults to aes-256-ctr if not provided
    $options = 0; // Optional: defaults to 0 if not provided
    $salt = "unique-salt"; // Optional: defaults to empty string if not provided

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
?>
