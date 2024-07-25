<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/EncryptionDecryption.php';

class EncryptionDecryptionTest extends TestCase
{
    private $key = 'test-key';
    private $cipher = 'aes-256-ctr';
    private $options = 0;
    private $salt = 'test-salt';
    private $encryption;

    protected function setUp(): void
    {
        $this->encryption = new EncryptionDecryption($this->key, $this->cipher, $this->options, $this->salt);
    }

    public function testEncryptDecrypt()
    {
        $originalData = 'This is a test message.';
        $encryptedData = $this->encryption->encrypt($originalData);
        $decryptedData = $this->encryption->decrypt($encryptedData);

        $this->assertEquals($originalData, $decryptedData, 'Decrypted data does not match the original data');
    }

    public function testEncryptDecryptWithDifferentKey()
    {
        $anotherKey = 'another-key';
        $anotherEncryption = new EncryptionDecryption($anotherKey, $this->cipher, $this->options, $this->salt);

        $originalData = 'This is a test message.';
        $encryptedData = $this->encryption->encrypt($originalData);
        $decryptedData = $anotherEncryption->decrypt($encryptedData);

        $this->assertFalse($decryptedData, 'Decryption should fail with a different key');
    }

    public function testEncryptDecryptWithDifferentSalt()
    {
        $anotherSalt = 'another-salt';
        $anotherEncryption = new EncryptionDecryption($this->key, $this->cipher, $this->options, $anotherSalt);

        $originalData = 'This is a test message.';
        $encryptedData = $this->encryption->encrypt($originalData);
        $decryptedData = $anotherEncryption->decrypt($encryptedData);

        $this->assertFalse($decryptedData, 'Decryption should fail with a different salt');
    }

    public function testEncryptDecryptWithInvalidData()
    {
        $invalidData = 'invalid-data';
        $decryptedData = $this->encryption->decrypt($invalidData);

        $this->assertFalse($decryptedData, 'Decryption of invalid data should return false');
    }

}
