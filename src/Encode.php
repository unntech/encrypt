<?php

namespace UNNTech\Encrypt;

class Encode
{
    public static function encode(string $data, string $code = 'base64' ): string
    {
        if(empty($data)){
            return '';
        }
        switch ( strtolower( $code ) ) {
            case 'base64':
                $data = base64_encode( $data );
                break;
            case 'base64url':
                $data = self::base64UrlEncode( $data );
                break;
            case 'hex':
                $data = bin2hex( $data );
                break;
            case 'bin':
            default:
        }
        return $data;
    }

    public static function decode(string $data, string $code = 'base64' )
    {
        if(empty($data)){
            return '';
        }
        switch ( strtolower( $code ) ) {
            case 'base64':
                $data = base64_decode( $data );
                break;
            case 'base64url':
                $data = self::base64UrlDecode( $data );
                break;
            case 'hex':
                $data = self::_hex2bin( $data );
                break;
            case 'bin':
            default:
        }
        return $data;
    }

    public static function convert_public_key_string(string $public_key): string
    {
        return strpos($public_key, 'BEGIN PUBLIC KEY') !== false ? $public_key : "-----BEGIN PUBLIC KEY-----\n" . wordwrap($public_key, 64, "\n", true) . "\n-----END PUBLIC KEY-----";
    }

    public static function convert_private_key_string(string $private_key, string $key_type = 'PRIVATE KEY'): string
    {
        return strpos($private_key, $key_type) !== false ? $private_key : "-----BEGIN {$key_type}-----\n" . wordwrap($private_key, 64, "\n", true) . "\n-----END {$key_type}-----";
    }

    public static function convert_pem_key_single(string $pem): string
    {
        // 去掉头尾的注释行和换行符
        $pem = preg_replace('/^-----.*-----$/m', '', $pem);
        $pem = preg_replace('/\s+/', '', $pem);
        return (string) $pem;
    }

    /**
     * 返回PEM证书的位数
     * @param string $pem
     * @return false | int
     */
    public static function pem_key_bits(string $pem)
    {
        if(strpos($pem, 'PUBLIC KEY')){
            $key = openssl_pkey_get_public($pem);
        }else{
            $key = openssl_pkey_get_private($pem);
        }
        if($key === false)
            return false;
        $details = openssl_pkey_get_details($key);
        if($details === false)
            return false;

        return $details['bits'];
    }

    /**
     * base64UrlEncode   https://jwt.io/  中base64UrlEncode编码实现
     * @param string $input 需要编码的字符串
     * @return string
     */
    public static function base64UrlEncode(string $input): string
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    /**
     * base64UrlEncode  https://jwt.io/  中base64UrlEncode解码实现
     * @param string $input 需要解码的字符串
     * @return bool|string
     */
    public static function base64UrlDecode(string $input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $len = 4 - $remainder;
            $input .= str_repeat('=', $len);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }

    private static function _hex2bin( $hex = false ) {
        return $hex !== false && preg_match( '/^[0-9a-fA-F]+$/i', $hex ) ? pack( "H*", $hex ) : false;
    }
}