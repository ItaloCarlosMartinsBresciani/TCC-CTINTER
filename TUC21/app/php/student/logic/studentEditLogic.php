<?php

session_start();

if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 1){
    header("Location: ../../../../index.php ");
    exit();
}

require_once('../../functions.php');
require_once('../../../db/connect.php');

if (isset($_GET['type']) && isset($_GET['id'])) {
    $type = cleanString($_GET['type']);

    try {
      $id = cleanString($_GET['id']);

      $idDec = decodeId($id);
    }
    catch (Exception $e) {
      header('Location: ../../../../views/student/studentPage.php');
    }   
} else {
    header('Location: ../../../../views/student/studentPage.php');
}

foreach($_POST as $key => $value) {
  $value = cleanString($value);

  if(!empty($value)) {
    $_POST[$key] = $value;
  }
  else {
    header('Location: ../../../../views/student/studentEdit.php?type='.$type.'&id='.codeId($idDec));
    exit();
  }
}

try
{
  if ($type == 'person') 
  {
    function verifyCPF( $cpf)
    {
      if (strpos($cpf, "-") !== false)
      {
          $cpf = str_replace("-", "", $cpf);
      }
      if (strpos($cpf, ".") !== false)
      {
          $cpf = str_replace(".", "", $cpf);
      }
      $sum = 0;
      $cpf = str_split( $cpf );
      $cpftrueverifier = array();
      $cpfnumbers = array_splice( $cpf , 0, 9 );
      $cpfdefault = array(10, 9, 8, 7, 6, 5, 4, 3, 2);
      for ( $i = 0; $i <= 8; $i++ )
      {
          $sum += $cpfnumbers[$i]*$cpfdefault[$i];
      }
      $sumresult = $sum % 11;  
      if ( $sumresult < 2 )
      {
          $cpftrueverifier[0] = 0;
      }
      else
      {
          $cpftrueverifier[0] = 11-$sumresult;
      }
      $sum = 0;
      $cpfdefault = array(11, 10, 9, 8, 7, 6, 5, 4, 3, 2);
      $cpfnumbers[9] = $cpftrueverifier[0];
      for ( $i = 0; $i <= 9; $i++ )
      {
          $sum += $cpfnumbers[$i]*$cpfdefault[$i];
      }
      $sumresult = $sum % 11;
      if ( $sumresult < 2 )
      {
          $cpftrueverifier[1] = 0;
      }
      else
      {
          $cpftrueverifier[1] = 11 - $sumresult;
      }
      $returner = false;
      if ( $cpf == $cpftrueverifier )
      {
          $returner = true;
      }


      $cpfver = array_merge($cpfnumbers, $cpf);

      if ( count(array_unique($cpfver)) == 1 || $cpfver == array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0) )

      {

          $returner = false;

      }
      return $returner;
    }

    if(verifyCPF($_POST['cpf-person']))
    {
      $query = 'UPDATE person SET cpf_person = :cpf_person, name_person = :name_person, 
      email_person = :email_person, telephone_person = :telephone_person, 
      rg_person = :rg_person, treatment_person = :treatment_person, last_edition_date = DEFAULT,
      who_edited = :who_edited
      WHERE id_person = :id_person' ;
    
      $stmt = $conn->prepare($query);
    
      $stmt->bindValue(':cpf_person', $_POST['cpf-person']);
      $stmt->bindValue(':name_person', $_POST['name-person']);
      $stmt->bindValue(':email_person', $_POST['email-person']);
      $stmt->bindValue(':telephone_person', $_POST['telephone-person']);
      $stmt->bindValue(':rg_person', $_POST['rg-person']);
      $stmt->bindValue(':treatment_person', $_POST['treatment-person']);
      $stmt->bindValue(':who_edited', "Diretor");
      $stmt->bindValue(':id_person', $idDec);
    
      $stmt->execute();

      $query = 'UPDATE student SET address_student = :address_student, district_student = :district_student,
      city_student = :city_student, cep_student = :cep_student
      WHERE fk_id = :id_person';

      $stmt = $conn->prepare($query);

      $stmt->bindValue(':address_student', $_POST['address_student']);
      $stmt->bindValue(':district_student', $_POST['district_student']);
      $stmt->bindValue(':city_student', $_POST['city_student']);
      $stmt->bindValue(':cep_student', $_POST['cep_student']);
      $stmt->bindValue(':id_person', $idDec);

      $stmt->execute();

      $query = "UPDATE change_data SET allowed = FALSE, edited = TRUE, last_edition_date = DEFAULT WHERE fk_id = $idDec";
      $stmt = $conn->prepare($query);
      $stmt->execute();
    
      $_SESSION['feedback'] = 'successEdit';
      $_SESSION['btn'] = 1;
      
     // $stmt->execute();
    
      header('Location: ../../../../views/student/studentPage.php');
      exit();
    }
    else
    {
      $idHex = codeId($idDec);
      $_SESSION['feedback'] = 'errorEdit';
      $_SESSION['btn'] = 1;
      echo "<script>alert('CPF inválido! Redigite.');</script>";
      echo "<script>window.location.replace('../../../../views/student/studentEdit.php?type=$type&id=$idHex')</script>";
    }
  } 
}
catch (Exception $ex)
{
  $idHex = codeId($idDec);
  echo "<script>alert('Erro ao tentar alterar os dados!');</script>";
  echo "<script>window.location.replace('../../../../views/student/studentEdit.php?type=$type&id=$idHex')</script>";
  $_SESSION['feedback'] = 'errorEdit';
  $_SESSION['btn'] = 1;
}