<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require __DIR__ . '/vendor/autoload.php';
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;
use JsonSchema\Constraints\Factory;
$jsonSchema = <<<'JSON'
{"$schema":"http://json-schema.org/draft-04/schema#","type":"object","properties":{"userInfo":{"type":"object","properties":{"appCodeName":{"type":"string"},"appName":{"type":"string"},"vendor":{"type":"string"},"platform":{"type":"string"},"userAgent":{"type":"string"}},"required":["appCodeName","appName","vendor","platform","userAgent"]},"time":{"type":"object","properties":{"startTime":{"type":"integer"},"currentTime":{"type":"integer"}},"required":["startTime","currentTime"]},"clicks":{"type":"object","properties":{"clickCount":{"type":"integer"},"clickDetails":{"type":"array","items":[{"type":"array","items":[{"type":"integer"},{"type":"integer"},{"type":"string"},{"type":"integer"}]},{"type":"array","items":[{"type":"integer"},{"type":"integer"},{"type":"string"},{"type":"integer"}]},{"type":"array","items":[{"type":"integer"},{"type":"integer"},{"type":"string"},{"type":"integer"}]}]}},"required":["clickCount","clickDetails"]},"mouseMovements":{"type":"array","items":[{"type":"array","items":[{"type":"integer"},{"type":"integer"},{"type":"integer"}]},{"type":"array","items":[{"type":"integer"},{"type":"integer"},{"type":"integer"}]},{"type":"array","items":[{"type":"integer"},{"type":"integer"},{"type":"integer"}]},{"type":"array","items":[{"type":"integer"},{"type":"integer"},{"type":"integer"}]},{"type":"array","items":[{"type":"integer"},{"type":"integer"},{"type":"integer"}]}]},"mouseScroll":{"type":"array","items":{}},"contextChange":{"type":"array","items":{}}},"required":["userInfo","time","clicks","mouseMovements","mouseScroll","contextChange"]}
JSON;
$jsonSchemaObject = json_decode($jsonSchema);
$schemaStorage = new SchemaStorage();
$schemaStorage->addSchema('file://mySchema', $jsonSchemaObject);
$jsonValidator = new Validator( new Factory($schemaStorage));
$userAgentManipulated = 1;
$jsonValid = 0;
if (isset($_COOKIE['authID']) && isset($_POST['user_behaviour']) && isset($_POST['pathname'])) {
    require $_SERVER['DOCUMENT_ROOT'].'/config.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/access/vendor/autoload.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/access/loginConfig.php';
    (int)$id = $auth->getSessionUID($_COOKIE['authID']);
    if ($id) {
        try {
            $pathname = $_POST['pathname'];
            $json = $_POST['user_behaviour'];
            $data = json_decode($json);
            $jsonArr = json_decode($json, true);
            
            $jsonValidator->validate($data, $jsonSchemaObject);
            if($_SERVER['HTTP_USER_AGENT'] === $jsonArr['userInfo']['userAgent']){
                $userAgentManipulated = 0;
            }
            if ($jsonValidator->isValid()) {
                $jsonValid = 1;
            }
            $q = $conn->prepare("INSERT INTO userBehaviour VALUES ($id, NOW(), '$json', $userAgentManipulated, $jsonValid, '$pathname')");
            $q->execute();
            if($q->errorCode() == 0){
                $return['error'] = false;
                echo json_encode($return);
            }
            else {
                $return['error'] = true;
                $return['message'] = "Could not access [user behaviour]. Contact administrators" ;
                echo json_encode($return);
            }
        } catch(Exception $e){
            $return['error'] = true;
            $return['message'] = "User Behaviour error. Contact administrators";
            echo json_encode($return);
        }
    }
    else{
        $return['error'] = true;
        $return['message'] = "Permission Error: Could not access user account. Contact administrators";
        echo json_encode($return);
    }
}
else{
    $return['error'] = true;
    $return['message'] = "Permission Denied";
    echo json_encode($return);
}
?>