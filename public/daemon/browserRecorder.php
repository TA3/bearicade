<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
require_once $_SERVER['DOCUMENT_ROOT'].'/access/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/access/loginConfig.php';
if (isset($_COOKIE['authID'])) {
    (int)$id = $auth->getSessionUID($_COOKIE['authID']);
    if ($id) {
        try {

            if (isset($_POST['d']) && $d = $_POST['d']["userSessionInfo"]) {
                //var_dump($d['browserCookies']);
                if (isset($d['browserCookies']) && isset($d['browserLanguage']) && isset($d['browserName']) && isset($d['browserPlatform']) && isset($d['browserVersion']) && isset($d['historyLength']) && isset($d['javaEnabled']) && isset($d['referrer']) && isset($d['screenSize']) && isset($d['screenSize']) && isset($d['fingerprint']) ) {
                
                }
                else {
                    $auth->logout($_COOKIE['authID']);
                    unset($_COOKIE['authID']);
                    echo `
                        Vue.toasted.show("ERROR: Browser denied access e.g(disable adblock), you have been logged out.", {
                            position: "top-right",
                            singleton: true,
                            duration: 10000,
                            action: {
                                text: 'Close',
                                onClick: (e, toastObject) => {
                                    toastObject.goAway(0);
                                }
                            },
                        });
                        setTimeout(function(){
                            window.location.href="/";
                        },5000)
                    `;
                    die();
                }
                $timestamp = date('Y-m-d h:i:s', time());
                $browserCookies = $d['browserCookies'];
                $browserLanguage = $d['browserLanguage'];
                $browserName = $d['browserName'];
                $browserPlatform = $d['browserPlatform'];
                $browserVersion = $d['browserVersion'];
                $browserIP = $_SERVER['REMOTE_ADDR'];
                $historyLength = $d['historyLength'];
                $javaEnabled = (int)$d['javaEnabled'];
                $referrer = $d['referrer'];
                $screenSize = $d['screenSize'];
                $fingerprint = $d['fingerprint'];
                require '../config.php';
                //$conn->prepare("DELETE FROM usersBrowser WHERE timestamp NOT IN ( SELECT timestamp FROM ( SELECT timestamp FROM usersBrowser WHERE id = $id ORDER BY timestamp DESC LIMIT $browserRecorderLimit ) AS A )")->execute();
                $count = $conn->query("SELECT COUNT(*) AS count FROM usersBrowser WHERE usersBrowser.id = $id AND usersBrowser.browserCookies = '$browserCookies' AND usersBrowser.browserIP = '$browserIP' AND usersBrowser.fingerprint = '$fingerprint'")->fetchColumn();
                if($count > 0){}
                else{
                    $q = $conn->prepare("INSERT INTO usersBrowser VALUES ($id, '$timestamp', '$browserCookies', '$browserLanguage', '$browserName', '$browserPlatform', '$browserVersion', '$browserIP', '$historyLength', '$javaEnabled', '$referrer', '$screenSize', '$fingerprint', 1)")->execute();

                    if ($q) {
                        echo 'success';
                    }
                    else {
                        print_r($conn->errorInfo());
                        print_r("INSERT INTO usersBrowser VALUES ($id, '$timestamp', '$browserCookies', '$browserLanguage', '$browserName', '$browserPlatform', '$browserVersion', '$browserIP', '$historyLength', '$javaEnabled', '$referrer', '$screenSize', '$fingerprint', 1)");
                        $conn = null;
                        die('error');
                    }
                }

            $conn = null;

            }
            else {

                die('no post');
            }

            } catch (Exception $e) {
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