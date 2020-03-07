<?php
require './vendor/autoload.php';
require './loginConfig.php';
if (isset($_COOKIE['authID'])) {
  $auth->logout($_COOKIE['authID']);
  unset($_COOKIE['authID']);
}
$secret = $tfa->createSecret();
$qrImage = $tfa->getQRCodeImageAsDataUri('Bob Ross', $secret);
?>