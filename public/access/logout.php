<?php
require './vendor/autoload.php';
require './loginConfig.php';
if (isset($_COOKIE['authID'])) {
    $auth->logout($_COOKIE['authID']);
}
unset($_COOKIE['authID']);
header("Location: ./");
die();
?>