<?php
require_once "../vendor/autoload.php";

use UNNTech\Encrypt\Request;
use UNNTech\Encrypt\Requests;
use UNNTech\Encrypt\Response;
use UNNTech\Encrypt\Responses;

$secret = 'C2103D89F56F4CDE4B5C1D9DFFDDF22A84998BB2FA1C4DC333231F61019ECCFB';
$publicKey = 'MFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAEo6l15dXWTG0wpdOiIGYn+qqh1k2pDGTojjsnkN3IyYg5Q+H4PbbxeUPs5luiii2j4YhEGWQ3GyqH3sw7Bn1oyQ==';
$privateKey = 'MHQCAQEEICheg+rKRY5dU8yVoJfmziVPMaXhlEY/hzhxKNfA554xoAcGBSuBBAAKoUQDQgAEo6l15dXWTG0wpdOiIGYn+qqh1k2pDGTojjsnkN3IyYg5Q+H4PbbxeUPs5luiii2j4YhEGWQ3GyqH3sw7Bn1oyQ==';


$data = ['abc'=>123];

//Responses::instance(['private_key' => $privateKey, 'public_key' => $publicKey, 'signType'=>'ECDSA'])->encrypted()->encryption('ECIES')->success($data);

Response::instance(['private_key' => $privateKey, 'public_key' => $publicKey, 'signType'=>'ECDSA'])::encrypted()::encryption('ECIES');
Response::success($data);

//$req = Requests::instance(['secret' => $secret, 'signType'=>'SHA256'])->headers(['access_token'=>'token'])->generate($data, 'object');

$req = Request::instance(['secret' => $secret, 'signType'=>'SHA256'])::headers(['access_token'=>'token'])::generate($data);
var_dump($req);
echo "<BR>\n";
$res = json_decode($req,true);
$c = Request::verifySign($res);
if($c){
    echo "Verify Sign Success. <BR>\n";
}else{
    echo "Verify Sign Fail. <BR>\n";
}
