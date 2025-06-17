
Encrypt 1.0
===============

[![Latest Stable Version](https://poser.pugx.org/unntech/encrypt/v/stable)](https://packagist.org/packages/unntech/encrypt)
[![Total Downloads](https://poser.pugx.org/unntech/encrypt/downloads)](https://packagist.org/packages/unntech/encrypt)
[![Latest Unstable Version](http://poser.pugx.org/unntech/encrypt/v/unstable)](https://packagist.org/packages/unntech/encrypt)
[![PHP Version Require](http://poser.pugx.org/unntech/encrypt/require/php)](https://packagist.org/packages/unntech/encrypt)
[![License](https://poser.pugx.org/unntech/encrypt/license)](https://packagist.org/packages/unntech/encrypt)

常用的加密库，封装开箱即用


## 主要新特性

* 采用`PHP7`强类型（严格模式）

> Encrypt 1.0 的运行环境要求PHP7.4+，兼容PHP8

## 安装

~~~
composer require unntech/encrypt
~~~


更新框架使用
~~~
composer update unntech/encrypt
~~~

目录结构
~~~
encrypt/
├── src                                     #
│   ├── AES.php                             # AES（Advanced Encryption Standard，高级加密标准）
│   ├── ECDSA.php                           # ECDSA 椭圆曲线数字签名
│   ├── Encode.php                          # 编码常用函数
│   ├── encrypt.md                          # 加解密及验签文档
│   ├── Request.php                         # Api 请求类
│   ├── Response.php                        # Api 输出类
│   └── RSA.php                             # RSA 加密库
├── tests                                   # 测试样例，可删除
├── composer.json                           #
├── license.txt
└── README.md
~~~

## 加解密及验签文档
[encrypt.md](src/encrypt.md)

## 使用示例

### AES 加解密
```php
use UNNTech\Encrypt\AES;

$aes = new AES('key', 'iv');
// 加密
$ciphertext = $aes->encrypt('plaintext');
// 解密
$plaintext = $aes->decrypt($ciphertext);

```

### ECDSA 验签及加解密
```php
use UNNTech\Encrypt\ECDSA;

$ecdsa = new ECDSA();
//生成ECDSA公私钥
$c = $ecdsa->createKey();
var_dump($c);

$publicKey = $c['public'];
$privateKey = $c['private'];

$ecdsa = new ECDSA( $publicKey, $privateKey );
$data = '测试ECDSA数据';
//生成ECDSA签名
$sign = $ecdsa->sign( $data );
//验证ECDSA签名
$y = $ecdsa->verifySign( $data, $sign );
var_dump( $sign, $y );

$arr = ['order'=>'20200826001','money'=>200];
//生成ECDSA签名数据数组
$arr = $ecdsa->signArray($arr);
//验证ECDSA签名数组
$y = $ecdsa->verifySignArray($arr);
var_dump($arr,$y);

//ECIES加密
$x = $ecdsa->encrypt( $data );
var_dump( $x );
//ECIES解密
$y = $ecdsa->decrypt( $x['ciphertext'], $x['tempPublicKey'], $x['iv'], $x['mac'], $x['code'] );
var_dump( $y );

```

### RSA 验签及加解密
```php
use UNNTech\Encrypt\RSA;

//生成RSA公私钥
$rsa = new RSA();
$c = $rsa->createKey();
var_dump($c);

$publicKey = $c['public'];
$privateKey = $c['private'];

$rsa = new RSA( $publicKey, $privateKey );
$data = '测试RSA2';
//生成RSA签名
$sign = $rsa->sign( $data );
//验证RSA签名
$y = $rsa->verifySign( $data, $sign );
var_dump( $sign, $y );

$arr = ['order'=>'20200826001','money'=>200];
//生成RSA签名数据数组
$arr = $rsa->signArray($arr);
//验证RSA签名数组
$y = $rsa->verifySignArray($arr);
var_dump($arr,$y);
//RSA加密
$x = $rsa->encrypt( $data );
//RSA解密
$y = $rsa->decrypt( $x );
var_dump( $x, $y );

```

### Request 请求数据生成及验签
```php
use UNNTech\Encrypt\Request;

$data = [
    'order_id' => 123,
    'money'    => 1001.23,
];

$req = Request::instance(['secret'=>'secret_key', 'signType'=>'SHA256'])::headers(['app'=>'IOS', 'access_token'=>'token'])::generate($data, 'array');
var_dump($req);
$request = json_encode($req);
dv($request);

$c = Request::verifySign($req);
if($c){
    echo "Verify Sign Success. <BR>\n";
}else{
    echo "Verify Sign Fail. <BR>\n";
}

```

### Response 数据格式化输出
```php
use UNNTech\Encrypt\Response;

$data = ['abc'=>123];
Response::instance(['secret' => 'secret_key', 'signType'=>'SHA256'])::success($data);

// 验证请求的数据是否合法
Response::instance(['secret' => 'secret_key'])::verifySign($request)

```

## 命名规范

`Encrypt` 遵循PSR命名规范和PSR-4自动加载规范。

## 参与开发

直接提交PR或者Issue即可

## 版权信息

Encrypt 遵循 MIT 开源协议发布，并提供免费使用。

本项目包含的第三方源码和二进制文件之版权信息另行标注。

版权所有Copyright © 2025 by Jason Lin All rights reserved。

