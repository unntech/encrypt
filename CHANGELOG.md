CHANGELOG
=========

### v1.0.6 `2025-06-23`
* 修改AES加密`iv`参数值为随机产生，并放于密文头部
* `ECIES`, `RASIES` 数据加密改用 `AES-256-CFB`
* 增加`WebToken`类，用于签发及验签token

### v1.0.5 `2025-06-17`
* Response 和 Request JSON encode 时 增加 json_encode_flags 设定，可自定义flags参数，增加平台对接兼容性

### v1.0.4 `2025-06-16`
* Response 增加`return_data`属性，可配置response是直接输出还是返回数组

### v1.0.3 `2025-06-15`
* ECDSA 增加数组签名 signArray 和验签 verifySignArray 方法
* 增加 Responses 和 Requests 方便实例化调用，适用一个程序中需要不同参数多个请求或输出，无需重复设置参数

### v1.0.2 `2025-06-01`
* AES（Advanced Encryption Standard，高级加密标准）
* Encode 增加 base64Url 编码

### v1.0.1 `2025-05-28`
* ECDSA 椭圆曲线数字签名
* ECIES（Elliptic Curve Integrated Encryption Scheme，椭圆曲线集成加密方案）
* RSA 验签、加解密
* RSAIES （Integrated Encryption Scheme）RSA集成加密方案
* Request 请求数据签名生成及验签
* Response API接口数据结构生成及加解密验签