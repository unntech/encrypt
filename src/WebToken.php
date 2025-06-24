<?php

namespace UNNTech\Encrypt;

class WebToken
{
    public int $err;
    protected string $cipher = 'AES-256-CFB';
    protected string $key = '';
    protected ?string $iv;
    protected string $salt;
    protected static $instance;

    public function __construct(string $key = '', string $cipher = 'aes-256-cfb', ?string $iv = '')
    {
        $this->cipher = $cipher;
        $this->key = $key;
        $this->iv = $iv;
        $this->salt = $key;
        $this->err = 0;
    }

    public static function instance(?string $key = null, ?string $cipher = null, ?string $iv = null): WebToken
    {

        if (static::$instance === null) {
            if (is_null($key)) $key = '';
            if (is_null($iv)) $iv = '';
            if (is_null($cipher)) $cipher = 'AES-256-CFB';
            static::$instance = new static($key, $cipher, $iv);
        }else{
            if (!is_null($key)) {
                static::$instance->key = $key;
                static::$instance->salt = $key;
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
     * 设置加密算法
     * @param string $cipher
     * @return WebToken
     */
    public function setCipher(string $cipher = 'aes-256-cbc'): WebToken
    {
        $this->cipher = $cipher;
        return $this;
    }

    /**
     * 重新设置加密密钥
     * @param string $key
     * @param string|null $iv
     * @return WebToken
     */
    public function setKey(string $key = '', ?string $iv = ''): WebToken
    {
        $this->key = $key;
        $this->iv = $iv;
        return $this;
    }

    /**
     * 重新设置加密盐值
     * @param string $salt
     * @return WebToken
     */
    public function setSalt(string $salt = ''): WebToken
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * 生成TOKEN
     * @param array $jwt (exp: 过期时间, iat: 签发时间, nbf: 生效时间)
     * @param int $exp (0：为使用$jwt数据里的exp； 其它时间为有效期，如 600为10分钟)
     * @param bool|string $salt 是否需要（并使用盐值）生成签名，防止数据被篡改，提高安全性
     * @return bool|string 加密后字符串
     */
    public function getToken(array $jwt, int $exp = 0,  $salt = true)
    {
        if($exp != 0){
            $jwt['exp'] = time() + $exp;
        }
        $ciphertext = (new AES($this->key, $this->cipher, $this->iv))->encrypt(json_encode($jwt, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), 'base64url');
        if ($ciphertext === false) {
            return false;
        }
        if ($salt || $salt === '') {
            $_salt = is_string($salt) ? $salt : null;
            $sign = $this->signature($ciphertext, $_salt);
            $ciphertext .= '.'.$sign;
        }
        $this->err = 0;
        return $ciphertext;

    }

    /**
     * 验证TOKEN
     * @param string $Token
     * @param bool|string|null $salt 需盐值验证签名，防止数据被篡改
     * @return bool|array Jwt数组 失败返回false, err为错误代码
     */
    public function verifyToken(string $Token,  $salt = null)
    {
        if (strpos($Token, '.') === false) {
            $ciphertext = $Token;
            $sign = null;
        }else{
            $_token = explode('.', $Token);
            $ciphertext = $_token[0];
            $sign = $_token[1];
        }
        if ($salt !== false && !is_null($salt)) {
            if (empty($sign)) {
                $this->err = 2; //签名错
                return false;
            }
        }
        if ($salt !== false && ($salt === true || !empty($sign))) {
            $_salt = is_string($salt) ? $salt : null;
            $_sign = $this->signature($ciphertext, $_salt);
            if ($sign != $_sign) {
                $this->err = 2; //签名错，数据被篡改
                return false;
            }
        }
        $plaintext = (new AES($this->key, $this->cipher, $this->iv))->decrypt($ciphertext, 'base64url');
        if ($plaintext === false) {
            $this->err = 1;  //解密失败
            return false;
        } else {
            $arr = json_decode($plaintext, true);
            if (is_array($arr)) {

                $curTime = time();

                //签发时间大于当前服务器时间验证失败
                if (isset($arr['iat']) && $arr['iat'] > $curTime) {
                    $this->err = 3;
                    return false;
                }

                //过期时间小宇当前服务器时间验证失败
                if (isset($arr['exp']) && $arr['exp'] < $curTime) {
                    $this->err = 4;
                    return false;
                }

                //该nbf时间之前不接收处理该Token
                if (isset($arr['nbf']) && $arr['nbf'] > $curTime) {
                    $this->err = 5;
                    return false;
                }

                $this->err = 0;
                return $arr;
            } else {
                $this->err = -1;  //非数组
                return false;
            }
        }

    }

    protected function signature(string $str, ?string $salt = null): string
    {
        $_salt = is_null($salt) ? $this->salt : $salt;
        return Encode::encode(hash("sha256", $str . $_salt, true), 'base64url');

    }

}