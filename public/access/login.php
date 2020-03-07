<?php
date_default_timezone_set('UTC');
error_reporting(E_ALL);
ini_set('display_errors', '1');
require './vendor/autoload.php';
require './loginConfig.php';
function getIp()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
           return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
           return $_SERVER['REMOTE_ADDR'];
        }
    }
$reason = 'NA';
$fails = 1;
$re = '/^[0-9]{6}\z/';
if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['fp']) && isset($_POST['tfaCode']) && preg_match_all($re, $_POST['tfaCode'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $tfaCode = $_POST['tfaCode'];
  $secret = $auth->getSecret($email);
  $id = (int)$auth->getUID(strtolower($email));
  $browserIP = getIp();
  $fingerprint = (string)$_POST['fp'];
  if($tfa->verifyCode($secret,$tfaCode)){
    $checkLogin = $auth->login($email, $password);
    if($checkLogin['error']){
      $reason = $checkLogin['message'];
    }
    else {
      $fails = 0;
    }
    echo json_encode($checkLogin);
  }
  else{
    $return['error'] = true;
    $return['message'] = "Invalid Authentication Code";
    $reason = $return['message'];
    echo json_encode($return);
  }

}
else{
    $return['error'] = true;
    $return['message'] = "Invalid Login Details";
    $reason = $return['message'];
    echo json_encode($return);
  }

require '../config.php';
$conn->prepare("DELETE FROM usersLogin WHERE usersLogin.timestamp < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL $loginRecorderDaysLimit DAY))")->execute();
$q = $conn->prepare("INSERT INTO usersLogin VALUES ($id, NOW(), $fails, '$browserIP', '$fingerprint', '$reason')")->execute();



die();




?>