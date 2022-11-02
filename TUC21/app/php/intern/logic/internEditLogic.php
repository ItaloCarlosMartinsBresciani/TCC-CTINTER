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
      header('Location: ../../../../views/intern/internPage.php');
    }   
} else {
    header('Location: ../../../../views/intern/internPage.php');
}

foreach($_POST as $key => $value) {
    $value = cleanString($value);

    if(!empty($value) || $key == 'scholarship-value-internship-data') {
        $_POST[$key] = $value;
    }
    else {
        echo "<script>alert('Há dados não preenchidos!');</script>";
        echo "<script>window.location.replace('../../../../views/intern/internEdit.php?type=$type&id=$id')</script>";
        exit();
    }
}

try
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

    if ($type == 'person') 
    {
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

            header('Location: ../../../../views/intern/internPage.php');
            exit();
        }
        else
        {
            $idHex = codeId($idDec);
            $_SESSION['feedback'] = 'errorEdit';
            $_SESSION['btn'] = 1;
            echo "<script>alert('CPF inválido! Redigite.');</script>";
            echo "<script>window.location.replace('../../../../views/intern/internEdit.php?type=$type&id=$idHex')</script>";
        }
    }else if ($type == 'internship')
    {
      $id_internship = $idDec;
  
      //consistência 
      $current_date = date("Y");
      $start_date = date_create($_POST['start-date-internship-data']);
      $end_date = date_create($_POST['end-date-internship-data']);
      
      
    //   $h1 = new DateTime($_POST['start-time-internship-data']);
    //   $h2 = new DateTime($_POST['end-time-internship-data']);
    //   $h3 = new DateTime($_POST['lunch-time']);
      
    //   $diff = $h2->diff($h1, true); 
    //   $diff = $diff->format("%h:%i");
    //   $h4 = new DateTime($diff);                
    //   $diff2 = $h4->diff($h3, true);  
    //   $diff2 = $diff2->format("%h%i");
  
    //   if($_POST['start-time-internship-data'] >= $_POST['end-time-internship-data'])  
    //   {
    //       echo "<script>alert('Horário de estágio inválido!');</script>";
    //   }
    //   if(intval($diff2) > 600)  
    //   {
    //       echo "<script>alert('Horário de estágio excede o máximo diário de 6 horas, descontando o horário de almoço!');</script>";
    //   }
      if($_POST['start-date-internship-data'] >= $_POST['end-date-internship-data'])
      {
          echo "<script>alert('Período de estágio inválido!');</script>";
      }
      else if(($_POST['end-date-internship-data'] - $_POST['start-date-internship-data']) > 2)
      {
          echo "<script>alert('Período de estágio excede o máximo exigido por lei (2 anos)!');</script>";
      }
      else if(date_format($start_date, "Y") < $current_date - 2 || date_format($start_date,"%Y") > $current_date)
      {
          echo "<script>alert('Período de estágio inválido!');</script>";
      }
      else
      {
        //inserção dados do estágio
        $query = 'UPDATE internship_data
        SET role_internship_data = :role_internship_data,
        course_internship_data = :course_internship_data,
        area_internship_data = :area_internship_data,
        week_hours_internship_data = :week_hours_internship_data,
        lunch_time = :lunch_time,
        total_hours_internship_data = :total_hours_internship_data,
        start_date_internship_data = :start_date_internship_data,
        end_date_internship_data = :end_date_internship_data,
        -- start_time_internship_data = :start_time_internship_data,
        -- end_time_internship_data = :end_time_internship_data,
        scholarship_internship_data = :scholarship_internship_data,';
  
        if (!empty($_POST["scholarship-value-internship-data"]))
        {
          $query = $query.'scholarship_value_internship_data = :scholarship_value_internship_data,';
        }
        else
        {          
          $query = $query.'scholarship_value_internship_data = 0,';
        }
        $query = $query.'description_internship_data = :description_internship_data         
                        WHERE id_internship_data = '.$id_internship.' AND finished = FALSE';   
  
        $stmt = $conn->prepare($query);
  
        $stmt->bindValue(':role_internship_data', $_POST["role-internship-data"]);
        $stmt->bindValue(':course_internship_data', $_POST["course-internship-data"]);
        $stmt->bindValue(':area_internship_data', $_POST["area-internship-data"]);
        $stmt->bindValue(':total_hours_internship_data', $_POST["total-hours-internship-data"]);
        $stmt->bindValue(':week_hours_internship_data', $_POST["week-hours-internship-data"]);
        $stmt->bindValue(':lunch_time', $_POST["lunch_time"]);
        $stmt->bindValue(':start_date_internship_data', $_POST["start-date-internship-data"]);
        $stmt->bindValue(':end_date_internship_data', $_POST["end-date-internship-data"]);
        // $stmt->bindValue(':start_time_internship_data', $_POST["start-time-internship-data"]);
        // $stmt->bindValue(':end_time_internship_data', $_POST["end-time-internship-data"] );   
        $stmt->bindValue(':scholarship_internship_data', $_POST["scholarship-internship-data"] ); 
        
        if (!empty($_POST["scholarship-value-internship-data"]))
          $stmt->bindValue(':scholarship_value_internship_data', $_POST["scholarship-value-internship-data"]);
      
        $stmt->bindValue(':description_internship_data', $_POST["description-internship-data"] );        
  
        $stmt->execute();
    
        $query = "UPDATE change_data_internship SET allowed = FALSE, edited = TRUE, last_edition_date = DEFAULT WHERE fk_id = $idDec";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        $_SESSION['feedback'] = 'successEdit';
        $_SESSION['btn'] = 1;
            
        header('Location: ../../../../views/intern/internPage.php');
        exit();
      } 
    }
}
catch (Exception $ex)
{
  $idHex = codeId($idDec);
  echo "<script>alert('Erro ao tentar alterar os dados!');</script>";
  echo "<script>window.location.replace('../../../../views/intern/internEdit.php?type=$type&id=$idHex')</script>";
  $_SESSION['feedback'] = 'errorEdit';
  $_SESSION['btn'] = 1;
}