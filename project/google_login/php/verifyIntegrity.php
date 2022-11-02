<?php

session_start(); 

require_once 'connect.php'; 
require_once '../vendor/autoload.php';
require_once 'google.php';

$client = new Google_Client(['client_id' => GOOGLE['clientId']]);  
$payload = $client->verifyIdToken($_POST['idtoken']);

if ($payload) {
  $domain = $payload['hd'];

  if($domain == 'unesp.br') {
    // Retorna todas as informações do usuário em formato JSON
    // header('location: https://oauth2.googleapis.com/tokeninfo?id_token='.$_POST['idtoken']);

    $json = file_get_contents("https://oauth2.googleapis.com/tokeninfo?id_token=".$_POST['idtoken']);
    $data = json_decode($json);

    $query = "SELECT id FROM google_user WHERE email = :email";

    $stmt = $conn -> prepare($query); 

    $stmt -> bindValue(":email", $data -> email); 

    $stmt -> execute(); 

    $return =  $stmt -> fetchAll(PDO::FETCH_ASSOC);

    if(count($return) == 0) {
      $query = "INSERT INTO google_user VALUES(DEFAULT, :sub, :name, :email, DEFAULT)";

      $stmt = $conn -> prepare($query); 
  
      $stmt -> bindValue(":sub", $data -> sub); // Exclusive User Id
      $stmt -> bindValue(":name", $data -> name); 
      $stmt -> bindValue(":email", $data -> email); 

      $stmt -> execute(); 

      if($stmt) { 

        $query = "SELECT id FROM google_user WHERE email = :email";

        $stmt = $conn -> prepare($query); 

        $stmt -> bindValue(":email", $data -> email); 

        $stmt -> execute(); 

        $return =  $stmt -> fetch(PDO::FETCH_ASSOC);

        $_SESSION['isAuth'] = true;
        $_SESSION['idUser'] = $return['id'];
      }
      else {
        // Error
      }
    }
    else {

        $query = "SELECT id, sub FROM google_user WHERE email = :email";

        $stmt = $conn -> prepare($query); 

        $stmt -> bindValue(":email", $data -> email); 

        $stmt -> execute(); 

        $return =  $stmt -> fetch(PDO::FETCH_ASSOC);

        if($return['sub'] == $data -> sub) {

          $_SESSION['isAuth'] = true;
          $_SESSION['idUser'] = $return['id'];
          
        }
        else {
          // Error 
        }

    }

    exit();
  }
  else {
    // Invalid Email 
  }

} else {
  // Invalid ID token
}
