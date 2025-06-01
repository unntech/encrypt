<?php

namespace UNNTech\Encrypt;

class AES
{
    protected string $cipher = 'aes-256-cbc';
    protected string $iv;
    protected string $key;

    public function __construct(string $key = '', string $iv = '', string $cipher = 'aes-256-cbc')
    {
        $this->key = $key;
        $this->cipher = $cipher;
        $this->iv = empty($iv) ? $this->generate_iv($key) : $iv;
    }

    /**
     * 获取有效密码方式算法列表
     * @return array
     */
    public function getCipher(): array
    {
        return openssl_get_cipher_methods();
    }

    /**
     * 设置加密算法
     * @param string $cipher
     * @return $this
     */
    public function setCipher(string $cipher = 'aes-256-cfb'): AES
    {
        $this->cipher = $cipher;
        return $this;
    }

    /**
     * 生成随机密钥
     * @param int $length
     * @return string
     */
    public function random_passphrase(int $length = 32): string
    {
        return openssl_random_pseudo_bytes($length);
    }

    /**
     * 重新设置加密密钥
     * @param string $key
     * @param string $iv
     * @return $this
     */
    public function setKey(string $key = '', string $iv = ''): AES
    {
        $this->key = $key;
        $this->iv = empty($iv) ? $this->generate_iv($key) : $iv;
        return $this;
    }

    /**
     * 加密
     * @param string $plaintext
     * @param string $code base64 | base64Url | hex | bin
     * @return string
     */
    public function encrypt(string $plaintext, string $code = 'base64'): string
    {
        $ciphertext = openssl_encrypt($plaintext, $this->cipher, $this->key, OPENSSL_RAW_DATA, $this->iv);
        return Encode::encode($ciphertext, $code);
    }

    /**
     * 解密
     * @param $ciphertext
     * @param string $code base64 | base64Url | hex | bin
     * @return string
     */
    public function decrypt($ciphertext, string $code = 'base64'): string
    {
        $ciphertext = Encode::decode($ciphertext, $code);
        return openssl_decrypt($ciphertext, $this->cipher, $this->key, OPENSSL_RAW_DATA, $this->iv);
    }

    private function generate_iv(string $key)
    {
        $len = openssl_cipher_iv_length($this->cipher);
        $str = str_pad(hash("sha256", $key), $len, "\0");
        return substr($str, 0, $len);
    }
}