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
