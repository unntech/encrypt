<?php
namespace UNNTech\Encrypt;


class Responses
{
    protected string $signType = 'NONE';
    protected ?string $secret = '';
    protected ?string $private_key = '';
    protected int $private_key_bits = 1024;
    protected ?string $public_key = '';
    protected array $headers = [];
    protected bool $encrypted = false;
    protected string $encryption = 'RSA';
    protected int $json_encode_flags = JSON_UNESCAPED_SLASHES;
    protected bool $return_data = false;
    protected static $instance;

    /**
     * @param array $options <p><br>
     * [ 'secret'=>'', <br>
     *   'private_key'=>'', <br>
     *   'private_key_bits'=>1024, <br>
     *   'public_key'=>'', <br>
     *   'signType'=>'SHA256', <br>
     *   'headers'=>[] <br>
     *   'encrypted'=>true <br>
     *   'encryption'=>'RSAIES', <br>
     *   'json_encode_flags'=>JSON_UNESCAPED_SLASHES, <br>
     *   'return_data'=>false <br>
     * ]</p>
     * @return $this
     */
    public function __construct(array $options = [])
    {
        return $this->setOptions($options);
    }

    /**
     * @param array $options <p><br>
     * [ 'secret'=>'', <br>
     *   'private_key'=>'', <br>
     *   'private_key_bits'=>1024, <br>
     *   'public_key'=>'', <br>
     *   'signType'=>'SHA256', <br>
     *   'headers'=>[] <br>
     *   'encrypted'=>true <br>
     *   'encryption'=>'RSAIES', <br>
     *   'json_encode_flags'=>JSON_UNESCAPED_SLASHES, <br>
     *   'return_data'=>false <br>
     * ]</p>
     * @return $this
     */
    public static function instance(array $options = []): Responses
    {

        if (static::$instance === null) {
            static::$instance = new static($options);
        }else{
            static::$instance->setOptions($options);
        }
        return static::$instance;
    }

    /**
     * 获取签名方式
     * @return string
     */
    public function getSignType(): string
    {
        return $this->signType;
    }

    public function setOptions(array $options): Responses
    {
        if(isset($options['secret'])){
            $this->secret = $options['secret'];
        }
        if(isset($options['private_key'])){
            $this->private_key = $options['private_key'];
        }
        if(isset($options['private_key_bits'])){
            $this->private_key_bits = $options['private_key_bits'];
        }
        if(isset($options['public_key'])){
            $this->public_key = $options['public_key'];
        }
        if(isset($options['signType'])){
            $this->signType = $options['signType'];
        }
        if(isset($options['headers'])){
            $this->headers = $options['headers'];
        }
        if(isset($options['encrypted'])){
            $this->encrypted = $options['encrypted'];
        }
        if(isset($options['encryption'])){
            $this->encryption = $options['encryption'];
        }
        if(isset($options['return_data'])){
            $this->return_data = $options['return_data'];
        }
        if(isset($options['json_encode_flags'])){
            $this->json_encode_flags = $options['json_encode_flags'];
        }

        return $this;
    }

    public function getOptions(): array
    {
        return [
            'secret'            => $this->secret,
            'private_key'       => $this->private_key,
            'private_key_bits'  => $this->private_key_bits,
            'public_key'        => $this->public_key,
            'encrypted'         => $this->encrypted,
            'encryption'        => $this->encryption,
            'signType'          => $this->signType,
            'headers'           => $this->headers,
            'json_encode_flags' => $this->json_encode_flags,
            'return_data'       => $this->return_data,
        ];
    }

    public function jsonEncodeFlags(int $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    {
        $this->json_encode_flags = $flags;
        return $this;
    }

    /**
     * 设置secret
     * @param string $secret
     * @return $this
     */
    public function secret(string $secret)
    {
        $this->secret = $secret;
        return $this;
    }

    /**
     * 设置签名类型
     * @param string $signType MD5 | SHA256 | RSA | ECDSA
     * @return $this
     */
    public function signType(string $signType)
    {
        $this->signType = $signType;
        return $this;
    }

    /**
     * 设置RSA私钥
     * @param string $privateKey 私钥
     * @param int $bits 私钥长度位
     * @return $this
     */
    public function privateKey(string $privateKey, int $bits = 1024)
    {
        $this->private_key = $privateKey;
        return $this;
    }

    /**
     * 设置RSA公钥
     * @param string $publicKey 公钥
     * @return $this
     */
    public function publicKey(string $publicKey)
    {
        $this->public_key = $publicKey;
        return $this;
    }

    /**
     * 设置输出公共 header 参数值
     * @param array $headers
     * @return $this
     */
    public function headers(array $headers = [])
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * 设置输出数据为加密
     * @param bool $encrypted
     * @return $this
     */
    public function encrypted(bool $encrypted = true)
    {
        $this->encrypted = $encrypted;
        return $this;
    }

    /**
     * 设置加密类型
     * @param string $encryption RSA | ECIES | RSAIES | AES
     * @return $this
     */
    public function encryption(string $encryption = 'RSA')
    {
        $this->encryption = $encryption;
        return $this;
    }

    public function returnData(bool $return_data = true)
    {
        $this->return_data = $return_data;
        return $this;
    }

    /**
     * 输出完成数据
     * @param array $data
     * @param int $errCode
     * @param string $msg
     * @return array|void
     */
    public function success(array $data = [], int $errCode = 0, string $msg = 'success')
    {
        $ret = [
            'head'     => [
                'errcode'   => $errCode,
                'msg'       => $msg,
                'unique_id' => $_SERVER['UNIQUE_ID'] ?? 'id_' . uniqid(),
                'timestamp' => time(),
            ],
            'body'     => $data,
            'signType' => $this->signType,
        ];
        return $this->response($ret);
    }

    /**
     * 输出错误代码信息
     * @param int $errCode
     * @param string $msg
     * @param array $data
     * @return array|void
     */
    public function error(int $errCode = 0, string $msg = 'fail', array $data = [])
    {
        $ret = [
            'head'     => [
                'errcode'   => $errCode,
                'msg'       => $msg,
                'unique_id' => $_SERVER['UNIQUE_ID'] ?? 'id_' . uniqid(),
                'timestamp' => time(),
            ],
            'body'     => $data,
            'signType' => $this->signType,
        ];
        return $this->response($ret);
    }

    /**
     * 验签
     * @param array $data
     * @param bool $perforce 为true时则必须要签名，NONE签名方式也验签失败
     * @return bool
     */
    public function verifySign(array &$data, bool $perforce = false) : bool
    {
        $data['encrypted'] = $data['encrypted'] ?? false;
        $data['signType'] = $data['signType'] ?? 'NONE';
        $dataSign = $data['sign'] ?? 'NONE';
        if($perforce && empty($this->secret)){
            $this->secret = mt_rand().uniqid();
        }
        $verify = false;
        if($data['signType'] != 'NONE'){
            $head = $data['head'];
            ksort($head);
            $body = $data['body'];
            ksort($body);
            $data_bodyEncrypted =  $data['bodyEncrypted'] ?? '';
            $_signString = json_encode($head,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . $data_bodyEncrypted;
            switch($data['signType']){
                case 'MD5':
                    $signString = $_signString . $this->secret;
                    $sign = strtoupper(md5($signString));
                    if($dataSign == $sign){
                        $verify = true;
                    }
                    break;
                case 'SHA256':
                    $signString = $_signString . $this->secret;
                    $sign = strtoupper(hash("sha256", $signString));
                    if($dataSign == $sign){
                        $verify = true;
                    }
                    break;
                case 'RSA':
                    $signString = $_signString ;
                    if(empty($this->public_key)){
                        //$verify = false;
                    }else{
                        $rsa = new RSA($this->public_key, $this->private_key);
                        $verify = $rsa->verifySign($signString, $dataSign);
                    }
                    break;
                case 'ECDSA':
                    $signString = $_signString ;
                    if(empty($this->public_key)){
                        //$verify = false;
                    }else{
                        $ecdsa = new ECDSA($this->public_key, $this->private_key);
                        $verify = $ecdsa->verifySign($signString, $dataSign);
                    }
                    break;
                default:

            }
        }else{
            $verify = true;
        }
        if(isset($data['encrypted']) && $data['encrypted'] === true && $verify === true){
            switch ($data['encryption']['type']){
                case 'ECIES':
                    $en = $data['encryption'];
                    $ecdsa = new ECDSA($this->public_key, $this->private_key);
                    $dc = $ecdsa->decrypt($data['bodyEncrypted'], $en['tempPublicKey'], $en['iv'], $en['mac'], $en['code']);
                    break;
                case 'RSAIES':
                    $en = $data['encryption'];
                    $rsa = new RSA($this->public_key, $this->private_key);
                    $dc = $rsa->decrypt_ies($data['bodyEncrypted'], $en['cipher'], $en['iv'], $en['mac'], $en['code']);
                    break;
                case 'AES':
                    $en = $data['encryption'];
                    $dc = AES::instance($this->secret, $en['cipher'] ?? null)->decrypt($data['bodyEncrypted'], $en['code'] ?? 'base64');
                    break;
                default:
                    $rsa = new RSA($this->public_key, $this->private_key);
                    $dc = $rsa->decrypt($data['bodyEncrypted']);
            }
            $data['body'] = json_decode($dc, true);
        }
        if($perforce === true){
            if(!in_array($data['signType'], ['MD5', 'SHA256', 'ECDSA', 'RSA'])){
                $verify = false;
            }
        }

        return $verify;
    }

    /**
     * 请求数据生成签名
     * @param array $data
     * signType 提供 MD5、SHA256、RSA、ECDSA，验签时json encode增加中文不转unicode和不转义反斜杠两个参数
     * @return array
     */
    protected function _generate(array $data) : array
    {
        if(empty($data['body'])){
            $data['body'] = ['data'=>''];
        }
        if(!empty($this->headers) && is_array($this->headers)){
            $data['head'] += $this->headers;
        }
        $data['encrypted'] = false;
        $data['bodyEncrypted'] = '';
        if($this->encrypted){
            switch ($this->encryption){
                case 'ECIES':
                    $ecdsa = new ECDSA($this->public_key, $this->private_key);
                    $_enda = $ecdsa->encrypt(json_encode($data['body'], $this->json_encode_flags));
                    if($_enda !== false) { //加密成功
                        $data['encrypted'] = true;
                        $data['bodyEncrypted'] = $_enda['ciphertext'];
                        $data['body'] = ['data'=>'encrypted'];
                        $_encryption = [
                            'type'          => 'ECIES',
                            'tempPublicKey' => $_enda['tempPublicKey'],
                            'iv'            => $_enda['iv'],
                            'code'          => $_enda['code'],
                            'mac'           => $_enda['mac'],
                        ];
                    }else{
                        $_encryption = ['type'=>'ECIES'];
                    }
                    break;
                case 'RSAIES':
                    $rsa = new RSA($this->public_key, $this->private_key);
                    $_enda = $rsa->encrypt_ies(json_encode($data['body'], $this->json_encode_flags));
                    if($_enda !== false) { //加密成功
                        $data['encrypted'] = true;
                        $data['bodyEncrypted'] = $_enda['ciphertext'];
                        $data['body'] = ['data'=>'encrypted'];
                        $_encryption = [
                            'type'   => 'RSAIES',
                            'cipher' => $_enda['cipher'],
                            'iv'     => $_enda['iv'],
                            'code'   => $_enda['code'],
                            'mac'    => $_enda['mac'],
                        ];
                    }else{
                        $_encryption = ['type'=>'RSAIES'];
                    }
                    break;
                case 'AES':
                    $_enda = AES::instance($this->secret)->encrypt(json_encode($data['body'], $this->json_encode_flags));
                    if($_enda !== false){ //加密成功
                        $data['encrypted'] = true;
                        $data['bodyEncrypted'] = $_enda;
                        $data['body'] = ['data'=>'encrypted'];
                        $_encryption = ['type'=>'AES', 'cipher'=>'AES-256-CBC', 'code'=>'base64'];
                    }else{
                        $_encryption = ['type'=>'AES'];
                    }
                    break;
                default:
                    $rsa = new RSA($this->public_key, $this->private_key);
                    $_enda = $rsa->encrypt(json_encode($data['body'], $this->json_encode_flags));
                    if($_enda !== false){ //加密成功
                        $data['encrypted'] = true;
                        $data['bodyEncrypted'] = $_enda;
                        $data['body'] = ['data'=>'encrypted'];
                    }
                    $_encryption = ['type'=>'RSA'];
            }
            $data['encryption'] = $_encryption;
        }
        if(isset($data['signType']) && $data['signType'] != 'NONE') {
            $head = $data['head'];
            ksort($head);
            $body = $data['body'];
            ksort($body);
            $_signString = json_encode($head,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . $data['bodyEncrypted'];
            switch($data['signType']){
                case 'MD5':
                    $signString = $_signString . $this->secret;
                    $sign = strtoupper(md5($signString));
                    $data['sign'] = $sign;
                    break;
                case 'SHA256':
                    $signString = $_signString . $this->secret;
                    $sign = strtoupper(hash("sha256", $signString));
                    $data['sign'] = $sign;
                    break;
                case 'RSA':
                    $signString = $_signString ;
                    if(empty($this->private_key)){
                        $data['sign'] = '';
                    }else{
                        $rsa = new RSA($this->public_key, $this->private_key);
                        $sign = $rsa->sign($signString);
                        $data['sign'] = $sign === false ? '' : $sign;
                    }
                    break;
                case 'ECDSA':
                    $signString = $_signString ;
                    if(empty($this->private_key)){
                        $data['sign'] = '';
                    }else{
                        $ecdsa = new ECDSA($this->public_key, $this->private_key);
                        $sign = $ecdsa->sign($signString);
                        $data['sign'] = $sign === false ? '' : $sign;
                    }
                    break;
                default:
                    $data['signType'] = 'NONE';
                    $data['sign'] = '';
            }
        }

        return $data;
    }

    /**
     * 接口数据输出
     * @param array $data
     * signType 提供 MD5、SHA256验签时json encode增加中文不转unicode和不转义反斜杠两个参数
     * @return array|void
     */
    protected function response(array $data)
    {
        $data = $this->_generate($data);
        if($this->return_data){
            return $data;
        }

        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($data, $this->json_encode_flags);
        exit(0);
    }
}