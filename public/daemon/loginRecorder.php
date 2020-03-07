<?php
//AUTHORIZED USER ACCESS ONLY
try {
if (isset($_POST['d']) && $d = json_decode($_POST['d'])) {
    if (isset($d->{'fingerprint'}) ) {

    }
else {
    die('error');
}
    $browserIP = $auth->getIp;
    $fingerprint = $d->{'fp'};
    require '../config.php';
    $conn->prepare("DELETE FROM usersLogin WHERE usersLogin.timestamp < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL $loginRecorderDaysLimit DAY))")->execute();
    $count = $conn->query("SELECT COUNT(*) AS count FROM usersLogin WHERE usersLogin.id = $id AND usersLogin.fingerprint = '$fingerprint' AND usersLogin.ip = '$browserIP'")->fetchColumn();
    if($count > 0){
        $q = $conn->query("UPDATE usersLogin set fails = fails + 1 WHERE id = $id AND `usersLogin`.fingerprint = '$fingerprint' AND `usersLogin`.ip = '$browserIP'");
    }
    else {
        $q = $conn->query("INSERT INTO usersLogin VALUES ($id, NOW(), 1, '$browserIP', '$fingerprint')");
    }
   
    if ($q) {
        echo 'success';
    }
else {
    $conn->close();
    die('error');
}
$conn->close();
}

} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
?>