
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


## 命名规范

`Encrypt` 遵循PSR-2命名规范和PSR-4自动加载规范。

## 参与开发

直接提交PR或者Issue即可

## 版权信息

Encrypt 遵循 MIT 开源协议发布，并提供免费使用。

本项目包含的第三方源码和二进制文件之版权信息另行标注。

版权所有Copyright © 2025 by Jason Lin All rights reserved。

