<?php

namespace UNNTech\Encrypt;

class AES
{
    protected string $cipher = 'aes-256-cbc';
    protected ?string $iv;
    protected string $key;
    protected static $instance;

    /**
     * 如有设置iv值则使用用户设定的iv值进行加解密，<br>
     * 默认由框架自动产生随机iv值，并存放于密文头部
     * @param string $key
     * @param string $cipher
     * @param string|null $iv
     */
    public function __construct(string $key = '', string $cipher = 'aes-256-cbc', ?string $iv = '')
    {
        $this->key = $key;
        $this->cipher = $cipher;
        $this->iv = $iv;
    }

    public static function instance(?string $key = null, ?string $cipher = null, ?string $iv = null): AES
    {
        if (static::$instance === null) {
            if (is_null($key)) $key = '';
            if (is_null($iv)) $iv = '';
            if (is_null($cipher)) $cipher = 'AES-256-CBC';
            static::$instance = new static($key, $cipher, $iv);
        }else{
            if (!is_null($key)) {
                static::$instance->key = $key;
            }
            if (!is_null($iv)) {
                static::$instance->iv = $iv;
            }
            if (!is_null($cipher)) {
                static::$instance->cipher = $cipher;
            }
        }
        return static::$instance;
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
     * @param string|null $iv
     * @return $this
     */
    public function setKey(string $key = '', ?string $iv = ''): AES
    {
        $this->key = $key;
        $this->iv = $iv;
        return $this;
    }

    /**
     * 加密
     * @param string $plaintext
     * @param string $code base64 | base64Url | hex | bin
     * @return string|bool
     */
    public function encrypt(string $plaintext, string $code = 'base64')
    {
        $ivLen = openssl_cipher_iv_length($this->cipher);
        if (empty($this->iv)) {
            $iv = $ivLen > 0 ? openssl_random_pseudo_bytes($ivLen) : '';
        } else {
            $iv = $this->iv;
        }
        $ciphertext = openssl_encrypt($plaintext, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);
        if($ciphertext === false) {
            return false;
        }
        if ($ivLen > 0 && empty($this->iv)) {
            $ciphertext = $iv . $ciphertext;
        }
        return Encode::encode($ciphertext, $code);
    }

    /**
     * 解密
     * @param $ciphertext
     * @param string $code base64 | base64Url | hex | bin
     * @return string|bool
     */
    public function decrypt($ciphertext, string $code = 'base64')
    {
        $ciphertext = Encode::decode($ciphertext, $code);
        if (empty($this->iv)) {
            $ivLen = openssl_cipher_iv_length($this->cipher);
            if ($ivLen > 0) {
                $iv = substr($ciphertext, 0, $ivLen);
                $ciphertext = substr($ciphertext, $ivLen);
            }else{
                $iv = '';
            }
        }else{
            $iv = $this->iv;
        }
        $plaintext = openssl_decrypt($ciphertext, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);

        return $plaintext === false ? false : $plaintext;
    }

}