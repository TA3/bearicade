<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../vendor/autoload.php';
use Respect\Validation\Validator as v;
require '../loginConfig.php';
require '../../config.php';

if (isset($_POST['name']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['cpassword']) && isset($_POST['tfaCode']) && isset($_POST['secret'])) {

  $name = $_POST['name'];
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $cpassword = $_POST['cpassword'];
  $tfaCode = $_POST['tfaCode'];
  $secret = $_POST['secret'];





   $nameV = v::regex("/^[A-Za-z,.-]{2,}\s[A-Za-z,.-]{2,}$/i")->length(2, 100)->validate($name);
   $usernameV = v::regex("/^[a-zA-Z0-9]+([_-]?[a-zA-Z0-9])*$/i")->length(4, 50)->validate($username);
   $emailV = v::email()->validate($email);
   $passwordV = v::equals($password)->validate($cpassword);
   $tfaCodeV = v::regex("/^[0-9]{6}$/i")->length(6, 6)->validate($tfaCode);
   $secretV = v::alnum()->length(32, 32)->noWhitespace()->uppercase()->validate($secret);

  //$return['vars'] = [$nameV, $emailV, $passwordV, $tfaCodeV, $secretV];
  //echo json_encode([$nameV, $emailV, $passwordV, $tfaCodeV, $secretV]);

   //FOR DEBUG

  $allowRegister =  ($nameV && $usernameV && $emailV && $passwordV && $tfaCodeV && $secretV);
  if($allowRegister){
     $tfaCorrect = $tfa->verifyCode($secret,$tfaCode);
     //$tfaCorrect = 1;
  }
  if($allowRegister && $tfaCorrect){
    $register = $auth->register($email, $password, $cpassword, $secret);
      if($register['error'] == false){
        (int)$newID = (int)$register['id'];
        $statement = $conn->prepare('INSERT INTO users (id, username, email, name) VALUES (?, ?, ?, ?)');
        //$statement->execute(['$newID', '$username', '$email', '$name']);
        if(!$statement->execute([$newID, $username, $email, $name])){
            $q = $dbh->prepare("DELETE FROM users WHERE id = ?");
            $response = $q->execute(array($newID));
            $return['error'] = true;
            $return['message'] = "Error. Registration failed.";
            echo json_encode($return);
            return;
        }
        $return['error'] = false;
        $return['message'] = $register['message'];
        

        echo json_encode($return);
      }
      else{
        $return['error'] = true;
        $return['message'] = $register['message'];
        echo json_encode($return);
      }
  }
  if(!$allowRegister){
    //SEND REPORT TO ADMIN - Modified Variables
    $return['error'] = true;
    $return['message'] = "Registration Failed, System adminstators contacted";
    echo json_encode($return);
    die();

  }


   if(!$tfaCorrect){
    $return['error'] = true;
    $return['message'] = "Registration Failed, Incorrect TFA code.";
    echo json_encode($return);
    die();
  }
}
else{
    $return['error'] = true;
    $return['message'] = "Invalid Registration Details";
    echo json_encode($return);
  }

?>