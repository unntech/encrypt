<?php
require_once "../vendor/autoload.php";

use UNNTech\Encrypt\RSA;

$publicKey = 'MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAL/ZHd/ZhGZmM1Y/Jo8caIhrJkfDOWIFxC+aBK5acLy82Hqu2cFzZ3N+Wx0zDN8Q45EnOkiOnfeRtDQl/XdsL8kCAwEAAQ==';
$privateKey = 'MIIBVAIBADANBgkqhkiG9w0BAQEFAASCAT4wggE6AgEAAkEAv9kd39mEZmYzVj8mjxxoiGsmR8M5YgXEL5oErlpwvLzYeq7ZwXNnc35bHTMM3xDjkSc6SI6d95G0NCX9d2wvyQIDAQABAkEAmjqjZ6foZqHWt4lBKF/AMZtiROLPKNxV4abCCKCwbSlHuvW7X/XVAoOo+2WHxz7LZRlqOvjLx142965XP0JyAQIhAOLqGn90YO+l6rlNZ9dIBzAtJNKtCawCHHXIXSE1eidhAiEA2HBbfcridOLOodhuOOZpTYcRXSioaXqm+oqpcowzqWkCIDcnAWPDLKBy6lc5qiiYOC8Meeu+5R/qr3ItTf15WwRhAiBNC4n1+F+uXgJSKHnr9VEs0NTEhbGVgpyn+O4ioXfOaQIgQ4HPmI9EwIaHsQamrarAkXN3IQhroic1xL/clQQDebw=';

$rsa = new RSA($publicKey, $privateKey);
$key = $rsa->createKey();
var_dump($key);

echo "<BR>\n<BR>\n";
$data = "Hello World!";
$sign = $rsa->sign($data);
echo "Sign: " . $sign . "<BR>\n";
$c = $rsa->verifySign($data, $sign);
if($c){
    echo "Verify Sign Success. <BR>\n";
}else{
    echo "Verify Sign Fail. <BR>\n";
}
$res = $rsa->encrypt_ies($data);
var_dump($res);
