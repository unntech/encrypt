<?php
require_once "../vendor/autoload.php";

use UNNTech\Encrypt\AES;
use UNNTech\Encrypt\WebToken;

$aes = new AES('FF41490A17037CABE3253B785A2194EF');

$ciphertext = $aes->encrypt('cbbaa77e-8447-4373-8d48-4f0f1d14280c');

$plaintext = $aes->decrypt($ciphertext);
var_dump($ciphertext, $plaintext);

$cipher = $aes->getCipher();
//var_dump($cipher);

$c = AES::instance('EF41490A17037CABE3253B785A2194EE')->encrypt('cbbaa77e-8447-4373-8d48-4f0f1d14280c');
$p = AES::instance()->decrypt($c);
var_dump($c, $p);

$token = WebToken::instance('FF41490A17037CABE3253B785A2194EF')->getToken(['sub'=>123], 600, true);
var_dump($token);
$data = WebToken::instance()->verifyToken($token);
var_dump($data);