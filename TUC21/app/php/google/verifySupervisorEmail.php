<?php

  session_start();

  require_once '../../db/connect.php'; 
  require_once('../functions.php');
  require_once '../../../vendor/autoload.php';
  require_once 'google.php';
  require_once '../admin/admin.php';

  if(isset($_GET['key'])){          //ema    <-- o ema sagrado
      $key = cleanString($_GET['key']);

      $query = 'SELECT token, valid_date FROM tokens WHERE token = :acessKey';
  
      $stmt = $conn->prepare($query);

      $stmt->bindValue(':acessKey', $key);

      $stmt->execute();
      $return = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      $validDate = date("U", $return[0]['valid_date']);

      if(count($return) < 1) {
          header("Location: ../index.php ");
          exit();
      } else if ($validDate < date('U', time())) {       
          // Exclusão do Token

          $query = 'DELETE FROM tokens WHERE token = :token';

          $stmt = $conn->prepare($query);

          $stmt->bindValue(':token', $key);

          $stmt->execute();

          header('Location: ../index.php');
          exit();
        }

  }
  else {
    header('Location: ../index.php');
    exit();
  }  
  
  $token = cleanString($_POST['credential']);
  
  if(!isset($_COOKIE['g_csrf_token']) || $_COOKIE['g_csrf_token'] !== $_POST['g_csrf_token'] || !isset($_POST['credential'])) {
    header('Location: ../../../index.php');
    exit();
  }
  
  $client = new Google_Client(['client_id' => GOOGLE['clientId']]);  
  
  //https://github.com/googleapis/google-api-php-client/issues/1172
  $jwt = new \Firebase\JWT\JWT; 
  $jwt::$leeway = 5;
  
  do {
    $attempt = 0;
    try {
        $payload = $client->verifyIdToken($token);
        $retry = false;
    } catch (Firebase\JWT\BeforeValidException $e) {
        $attempt++;
        $retry = $attempt < 2;
    }
  } while ($retry);
  
    if ($payload) {
      $json = file_get_contents("https://oauth2.googleapis.com/tokeninfo?id_token=".$token);
      $data = json_decode($json);

      $_SESSION['name-supervisor'] = $data->name;
      $_SESSION['email-supervisor'] = $data->email;
      $_SESSION['done'] = 1;
      header('Location: ../../../views/putSupervisorInformation.php?key='.$_GET["key"].'&id_company='.$_GET["id_company"]);

    } else {
        // Invalid ID token
        header('Location: ../../../views/components/feedback/error.html');
        exit();
    }

?>
