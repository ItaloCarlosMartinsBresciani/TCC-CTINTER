<?php

session_start();

require_once '../../db/connect.php'; 
require_once('../functions.php');
require_once '../../../vendor/autoload.php';
require_once 'google.php';
require_once '../admin/admin.php';

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
   
    // ------------------------------------------------ //
    if($data->email == ADMIN['email']) {
      $_SESSION['isAuth'] = true;
      $_SESSION['idUser'] = -1;
      
      header('Location: http://localhost/UNESP-Internship/TUC21/views/admin/adminPage.php');
      exit();
    }
    // ------------------------------------------------ // 

    $query = 'SELECT id_person, email_person, valid, access_level FROM person WHERE email_person = :email';
    
    $stmt = $conn->prepare($query);

    $stmt->bindValue(':email', $data->email);

    $stmt->execute();
    $return = $stmt->fetchAll(PDO::FETCH_ASSOC); 

    if(count($return) == 0) {
      // More data is necessary
      $query = 'SELECT id_company FROM company WHERE email_company = :email';
    
      $stmt = $conn->prepare($query);

      $stmt->bindValue(':email', $data->email);

      $stmt->execute();
      $return = $stmt->fetchAll(PDO::FETCH_ASSOC); 
      if(count($return) != 0)
      {
        $_SESSION['isAuth'] = true;
        $_SESSION['idUser'] = $return[0]['id_company'];
        header('Location: http://localhost/UNESP-Internship/TUC21/views/company/companyPage.php');
        exit();
      }
      else
      {
        $_SESSION['googleAuth'] = true;
        $_SESSION['name'] = $data->name;
        $_SESSION['email'] = $data->email;
        
        //$_SESSION['sub'] = $data->sub;
        
        echo "<script>alert('Você não está cadastrado no sistema');</script>"; //echo "<span>Você não está cadastrado no sistema!</span>";
        echo "<script>location= 'http://localhost/UNESP-Internship/TUC21/index.php';</script>";
        //colocar botão de enviar solicitação de convite
        //echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#sendEmailModal'>Enviar solicitação de cadastro</button>";
    
      }

    } else {
      if($return[0]['valid']) {
        $_SESSION['isAuth'] = true;
        $_SESSION['access_level'] = $return[0]['access_level'];
        $_SESSION['idUser'] = $return[0]['id_person'];
        
        switch($return[0]['access_level']){
          case 10: //Diretor
            header('Location: http://localhost/UNESP-Internship/TUC21/views/principal/principalPage.php');
            exit();
          case 7:  //supervisor
            header('Location: http://localhost/UNESP-Internship/TUC21/views/supervisor/supervisorPage.php');
            exit();  
          case 2: //Professor
            header('Location: http://localhost/UNESP-Internship/TUC21/views/professor/professorPage.php');
            exit();
          case 1: //Aluno (estagiário)
            header('Location: http://localhost/UNESP-Internship/TUC21/views/student/studentPage.php');
            exit();
        }

        header('Location: ../../../views/home.php');
        exit();
      }
      else {
        header('Location: ../../../views/components/feedback/situation.html');
        exit();
      }
    }
} else {
  // Invalid ID token
  header('Location: ../../../views/components/feedback/error.html');
  exit();
}
