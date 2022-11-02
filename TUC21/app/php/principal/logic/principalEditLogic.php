<?php

session_start();

if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 10){
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
      header('Location: ../../../../views/principal/principalPage.php');
      exit();
    }   
} else {
    header('Location: ../../../../views/principal/principalPage.php');
    exit();
}

foreach($_POST as $key => $value) {
  $value = cleanString($value);
  if(!empty($value) || $key == "mailbox_university") {
    $_POST[$key] = $value;
  }
  else {
    header('Location: ../../../../views/principal/principalEdit.php?type='.$type.'&id='.codeId($idDec));
    exit();
  }
}

try
{
  if ($type == 'person' || $type == 'profile') 
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

    if (verifyCPF($_POST['cpf-person']))
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
    
      $_SESSION['feedback'] = 'successEdit';
      $_SESSION['btn'] = 1;
      

      $query = "UPDATE change_data SET allowed = FALSE, edited = TRUE, last_edition_date = DEFAULT WHERE fk_id = $idDec";
      $stmt = $conn->prepare($query);
      $stmt->execute();
    
      $_SESSION['feedback'] = 'successEdit';
      $_SESSION['btn'] = 1;
    
      header('Location: ../../../../views/principal/principalPage.php');
      exit();
    }
    else
    {
      $idHex = codeId($idDec);
      echo "<script>alert('CPF inválido! Redigite.');</script>";
      echo "<script>window.location.replace('../../../../views/principal/principalEdit.php?type=$type&id=$idHex')</script>";
      exit();
    }
  
  } else if($type == 'university') {
      function verifyCNPJ($cnpj)
      {
          $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
          
          // Valida tamanho
          if (strlen($cnpj) != 14)
              return false;

          // Verifica se todos os digitos são iguais
          if (preg_match('/(\d)\1{13}/', $cnpj))
              return false;	

          // Valida primeiro dígito verificador
          for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
          {
              $soma += $cnpj[$i] * $j;
              $j = ($j == 2) ? 9 : $j - 1;
          }

          $resto = $soma % 11;

          if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
              return false;

          // Valida segundo dígito verificador
          for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
          {
              $soma += $cnpj[$i] * $j;
              $j = ($j == 2) ? 9 : $j - 1;
          }

          $resto = $soma % 11;

          return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
      }

      if (verifyCNPJ($_POST['cnpj_university']))
      {
        $query = 'UPDATE university SET cnpj_university = :cnpj_university, 
        name_university = :name_university, state_registration_university = :state_registration_university, 
        corporate_name_university = :corporate_name_university, legal_representative_university = :legal_representative_university, 
        activity_branch_university = :activity_branch_university, address_university = :address_university, 
        district_university = :district_university, cep_university = :cep_university, mailbox_university = :mailbox_university, 
        city_university = :city_university, state_university = :state_university, telephone_university = :telephone_university, 
        home_page_university = :home_page_university, email_university = :email_university WHERE id_university = :id_university' ;
        
        $stmt = $conn->prepare($query);
      
        $stmt->bindValue(':cnpj_university', $_POST['cnpj_university']);
        $stmt->bindValue(':name_university', $_POST['name_university']);
        $stmt->bindValue(':state_registration_university', $_POST['state_registration_university']);
        $stmt->bindValue(':corporate_name_university', $_POST['corporate_name_university']);
        $stmt->bindValue(':legal_representative_university', $_POST['legal_representative_university']);
        $stmt->bindValue(':activity_branch_university', $_POST['activity_branch_university']);
        $stmt->bindValue(':address_university', $_POST['address_university']);
        $stmt->bindValue(':district_university', $_POST['district_university']);
        $stmt->bindValue(':cep_university', $_POST['cep_university']);
        $stmt->bindValue(':mailbox_university', $_POST['mailbox_university']);
        $stmt->bindValue(':city_university', $_POST['city_university']);
        $stmt->bindValue(':state_university', $_POST['state_university']);
        $stmt->bindValue(':telephone_university', $_POST['telephone_university']);
        $stmt->bindValue(':home_page_university', $_POST['home_page_university']);
        $stmt->bindValue(':email_university', $_POST['email_university']);
        $stmt->bindValue(':id_university', $idDec);
        
        $stmt->execute();
      
        $_SESSION['feedback'] = 'successEdit';
        $_SESSION['btn'] = 2;
  
        $stmt->execute();

        $query = "UPDATE change_data_universities SET allowed = FALSE, edited = TRUE, last_edition_date = DEFAULT WHERE fk_id = $idDec";
        $stmt = $conn->prepare($query);
        $stmt->execute();
      
        $_SESSION['feedback'] = 'successEdit';
        $_SESSION['btn'] = 1;
        
        header('Location: ../../../../views/principal/principalPage.php');
        exit();
    }
    else
    {
      $idHex = codeId($idDec);
      echo "<script>alert('CNPJ inválido! Redigite.');</script>";
      echo "<script>window.location.replace('../../../../views/principal/principalEdit.php?type=$type&id=$idHex')</script>";
      exit();
    }
      
  }
}
catch (Exception $ex)
{
  $idHex = codeId($idDec);
  $_SESSION['feedback'] = 'errorEdit';
  $_SESSION['btn'] = 1;
  echo "<script>alert('Erro ao tentar alterar os dados!');</script>";
  echo "<script>window.location.replace('../../../../views/principal/principalEdit.php?type=$type&id=$idHex')</script>";
  exit();
}