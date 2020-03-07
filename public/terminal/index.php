
<?php
//error_reporting( E_ALL );
//ini_set( 'display_errors', 'OFF' );
require __DIR__ . '/vendor/autoload.php';
require '../config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/access/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/access/loginConfig.php';
use phpseclib\Crypt\RSA;
use phpseclib\Net\SSH2;
use phpseclib\Net\SFTP;
header("Content-Type: application/json");
$res = array('error' => false);
$output = array();



//sorting functions
function sortfilename ($a, $b) { return (strcmp ($a['filename'],$b['filename']));}
//chech start with
function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}



if (isset($_COOKIE['authID']) && isset($_GET["type"]) && $_GET["type"] == "ssh"){

    if (isset($_COOKIE['authID']) && isset($_GET["command"]) && $_GET["command"] != "" && $_GET["command"] != null  && isset($_GET["server"]) && $_GET["server"] != "" && $_GET["server"] != null) {
        (int)$id = $auth->getSessionUID($_COOKIE['authID']);
        (string)$command = urldecode($_GET["command"]);
        (string)$server = $_GET["server"];
        /*Activity Logging a_ */
        (string)$a_type="Terminal";
        (string)$a_comment='[Attempt] Command:"'. $command . '". Sent to: "'. $server .'"';
        
        $server = $conn->query("SELECT cast(ip AS CHAR) AS ip, port from servers WHERE name = '$server'")->fetchAll(PDO::FETCH_ASSOC)[0];
        if(!$server['ip']){
            $res = array('error' => 'Invalid request');
            $a_comment .= "\n[Error] Incorrect Server";
            logActivity($id, $a_type, $a_comment);
            echo json_encode($res);
            $conn = null;
            die();
        }
        
        if($username = $conn->query("SELECT * FROM users WHERE id = $id")->fetchAll(PDO::FETCH_ASSOC)[0]["username"]){
            $path = (isset($_GET['path']) ? $_GET['path'] : '/home/'.$username);
            if($path == "" || !(startsWith($path, '/home/'.$username))){
                if($path != "" || !(startsWith($path, '/home/'.$username))){
                    $a_comment .= "\n[Security] Path Manipulation: $path";
                }
                $path = '/home/'.$username;
                
            }
            if (stripos($command, "cd ") !== false) {
                $res = array('error' => 'Invalid request');
                $a_comment .= "\n[Security] Incorrect Server";
                logActivity($id, $a_type, $a_comment);
                echo json_encode($res);
                $conn = null;
                die();
            }
            $key = new RSA();
            $key->loadKey(file_get_contents('/keys_rsa/' . $username));

            //Remote server's ip address or hostname
            $ssh = new SSH2($server["ip"].':'.$server["port"]);

            if (!$ssh->login($username, $key)) {
                $a_comment .= "\n[Error] Login Failed";
                logActivity($id, $a_type, $a_comment);
                exit('Login Failed');
            }

            $ssh->exec("cd " . $path . ";" . $command , function ($str) {
                    //$strOneLine = str_replace(array("\r","\n")," ",$str);
                    array_push($GLOBALS['output'], $str);
                    flush();
                    ob_flush();
            });
            $res['output']= implode(" ", $output);
            $a_comment .= "\n[Success] Command Ran";
            logActivity($id, $a_type, $a_comment);
        }
        else{
            $res = array('error' => 'Invalid request');
            $a_comment .= "\n[Security] Invalid Request";
            logActivity($id, $a_type, $a_comment);
        }

    }
    else {
        $res = array('error' => 'Invalid request');
    }
}



else if(isset($_COOKIE['authID']) && isset($_GET["type"]) && $_GET["type"] == "sftp"){

    if (isset($_COOKIE['authID']) && isset($_GET["command"]) && $_GET["command"] != "" && $_GET["command"] != null) {
        (int)$id = $auth->getSessionUID($_COOKIE['authID']);
        (string)$command = urldecode($_GET["command"]);
        
        /*Activity Logging a_ */
        (string)$a_type="File Manager";
        $a_comment='[Attempt] Command:"'. $command.'"';
        
        if($username = $conn->query("SELECT * FROM users WHERE id = $id")->fetchAll(PDO::FETCH_ASSOC)[0]["username"]){
            $key = new RSA();
            $key->loadKey(file_get_contents('/keys_rsa/' . $username));
            $host_ip = shell_exec("/sbin/ip route|awk '/default/ { print $3 }'");
            $host_ip = str_replace("\n", "", $host_ip);
            $host_ip = str_replace("\r", "", $host_ip);
            $sftp = new SFTP($host_ip . ':22');
            if (!$sftp->login($username, $key)) {
                $a_comment .= "\n [Error] Login Failed for" . $username. "@" . $host_ip;
                logActivity($id, $a_type, $a_comment);
                exit('Login Failed for ' . $username. "@" . $host_ip );
            }
            if($command == "ls"){
                $path = (isset($_GET['path']) ? $_GET['path'] : '/home/'.$username);
                if($path == "" || !(startsWith($path, '/home/'.$username))){
                    if($path != "" || !(startsWith($path, '/home/'.$username))){
                        $a_comment .= "\n[Security] Path Manipulation: $path";
                    }
                    $path = '/home/'.$username;
                }
                //$sftp->setListOrder('filename');
                $files = $sftp->rawlist($path);
                uasort($files,'sortfilename');
                $res['output'] = $files;
                $res['wd'] = $path;
                $a_comment .= "\n[Success] $path";
                logActivity($id, $a_type, $a_comment);

            }
            else if($command == "get" && isset($_GET['path'])){

                $path = (isset($_GET['path']) ? $_GET['path'] : '');
                if($path == "" || !(startsWith($path, '/home/'.$username))){
                    if($path != "" || !(startsWith($path, '/home/'.$username))){
                        $a_comment .= "\n[Security] Path Manipulation: $path";
                    }
                    $path = '';
                }
                $download = (isset($_GET['download']) ? true : false);

                if(!$sftp->file_exists($path)){
                    $res = array('error' => 'Invalid request');
                    $a_comment .= "\n[Error] File not existing";
                    logActivity($id, $a_type, $a_comment);
                    echo json_encode($res);
                    $conn = null;
                    die();
                }
                else{
                    logActivity($id, $a_type, $a_comment);
                }
                $file = $sftp->get($path);
                $filetype = $sftp->filetype($path);
                $fileowner = $sftp->fileowner($path);
                $res['output'] = base64_encode($file);
                $res['filetype'] = $filetype;
                $res['fileowner'] = $fileowner;
                
            }
            else if($command == "put" && isset($_GET['path']) && isset($_GET['data'])){

                $path = (isset($_GET['path']) ? $_GET['path'] : '');
                if($path == "" || !(startsWith($path, '/home/'.$username))){
                    if($path != "" || !(startsWith($path, '/home/'.$username))){
                        $a_comment .= "\n[Security] Path Manipulation: $path";
                    }
                    $res = array('error' => 'Invalid request');
                    echo json_encode($res);
                    $conn = null;
                    die();
                }
                //var_dump(($_GET['data']));
                //var_dump(base64_decode($_GET['data']));
                //because PHP thinks its encoded to it will convert + to spaces so we revert this process
                (string)$data = base64_decode(str_replace(' ', '+', $_GET['data']));
                $new = (isset($_GET['new']) ? true : false);
                if(!$sftp->file_exists($path) && !$new){
                    $res = array('error' => 'Invalid request');
                    $a_comment .= "\n[Error] File not existing";
                    logActivity($id, $a_type, $a_comment);
                    echo json_encode($res);
                    $conn = null;
                    die();
                }
                else if($new){
                    $sftp->touch($path);
                    logActivity($id, $a_type, "\n[Success] created file");
                }
                //var_dump($data);
                $file = $sftp->put($path,$data);
                $a_comment .= "\n[Success] written to: $path";
                logActivity($id, $a_type, $a_comment);
                $res['success'] = $file;
            }
            else if($command == "upload" && isset($_GET['path'])){
                $path = (isset($_GET['path']) ? $_GET['path'] : '');
                if($path == "" || !(startsWith($path, '/home/'.$username))){
                    if($path != "" || !(startsWith($path, '/home/'.$username))){
                        $a_comment .= "\n[Security] Path Manipulation: $path";
                    }
                    $res = array('error' => 'Invalid request');
                    echo json_encode($res);
                    $conn = null;
                    die();
                }
                
                $target_dir = "$path/";
                $target_file = $target_dir . basename($_FILES["filepond"]["name"]);
                
                if ($sftp->put( $target_file,$_FILES["filepond"]["tmp_name"], SFTP::SOURCE_LOCAL_FILE)) {
                    $res["output"] = "The file ". basename( $_FILES["filepond"]["name"]). " has been uploaded.";
                    $res = array('error' =>  false);
                } else {
                    $res["error"] = "Sorry, there was an error uploading your file.". $target_file;
                }
            }
            else if($command == "delete" && isset($_GET['path'])){

                $path = (isset($_GET['path']) ? $_GET['path'] : '');
                if($path == "" || !(startsWith($path, '/home/'.$username))){
                    $res = array('error' => 'Invalid request');
                    $a_comment .= "\n[Security] Possible Path Manipulation: $path";
                    logActivity($id, $a_type, $a_comment);
                    echo json_encode($res);
                    $conn = null;
                    die();
                }
                if(!$sftp->file_exists($path)){
                    $res = array('error' => 'Invalid request');
                    $res['message'] = "File/Directory does not exist";
                    $a_comment .= "\n[Error] File not existing";
                    logActivity($id, $a_type, $a_comment);
                    echo json_encode($res);
                    $conn = null;
                    die();
                }
                //var_dump($data);
                $pathtype = $sftp->filetype($path);
                if($pathtype == "file"){
                    $file = $sftp->delete($path);
                    $res['isDeleted'] = $file;
                    if($file){
                        $a_comment .= "\n[Success] $path";
                        $res['message'] = "File deleted";
                    }
                    else{
                        $a_comment .= "\n[Error] $path";
                        $res['message'] = "File could not be deleted";
                    }
                }
                else if($pathtype == "dir"){
                    $file = $sftp->rmdir($path);
                    $res['isDeleted'] = $file;
                    if($file){
                        $a_comment .= "\n[Success] $path";
                        $res['message'] = "Directory removed";
                    }
                    else{
                        $a_comment .= "\n[Error] $path";
                        $res['message'] = "Directory could not be removed";
                    }
                }
                else {
                    $a_comment .= "\n[Error] Unknow type: $path";
                    $res['message'] = "Unknow type: could not delete";
                    $res = array('error' => 'Invalid request');
                }
                $res['deleted'] = $file;
                logActivity($id, $a_type, $a_comment);
            }
            else{
                $res = array('error' => 'Invalid request');
                $a_comment .= "\n[Error] Invalid Command";
                logActivity($id, $a_type, $a_comment);
            }

        }


    }
    else {
        $res = array('error' => 'Invalid request');
    }
}
//echo $output;

echo json_encode($res);
$conn = null;
die();
?>
