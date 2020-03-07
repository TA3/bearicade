<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

function userSupplied($id){
  if (isset($_GET['u']) && is_numeric($_GET['u']) && strpos($_GET['u'], '.') === false && $_GET['u'] != 1) {
    (int)$uid = $_GET['u'];
    return $uid;
  }
  else{
    return $id;
  }
}

function generateKey($conn,$domain,$uid) {
  $key = substr(md5(openssl_random_pseudo_bytes(20)), -32);
  $hash = password_hash($key, PASSWORD_BCRYPT);
  $query = "UPDATE apiAccess SET key_hash = '$hash', domain='$domain' WHERE uid = $uid";
  if(!$conn->prepare($query)->execute())
    $res = array('error' => 'could not generate key');
  else{
    $res["key"] =  base64_encode($uid). "." . $key;
  }
  return $res;
}

function checkActions(){}

function verifyKey($conn, $uid, $key) {
  if($hash = $conn->query("SELECT key_hash FROM apiAccess WHERE uid = $uid")->fetchColumn()){
    if (password_verify($key, $hash)) {
      return true;
    } else {
      return false;
    }
  }
  else{
    return false;
  }
} 
?>