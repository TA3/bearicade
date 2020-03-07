<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once $_SERVER['DOCUMENT_ROOT'].'/access/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/access/loginConfig.php';
if (isset($_COOKIE['authID'])) {
    (int)$id = $auth->getSessionUID($_COOKIE['authID']);
    if ($id) {
        try {
            require '../config.php';
            $conn->prepare("UPDATE users SET `lastOnline` = current_timestamp WHERE id = $id")->execute();
            $conn = null;
        }
        catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
    else {
        $res = array('error' => 'Invalid user: Permission Denied');
        $conn = null;
        header("Content-Type: application/json");
        echo json_encode($res);
        die();
    }
}
else{
    $res = array('error' => 'Permission Denied');
    $conn = null;
    header("Content-Type: application/json");
    echo json_encode($res);
    die();
}

?>