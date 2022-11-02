<?php

session_start();
if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 7){
    echo "<script>alert('Usuário não reconhecido.');</script>";
    header("Location: ../../index.php ");
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
        header('Location: ../../../../views/supervisor/supervisorPage.php');
    }   
} else {
    header('Location: ../../../../views/supervisor/supervisorPage.php');
}

try
{
    if ($type == 'allow') 
    {
        //$today = date('d-m-Y');
        $query = "UPDATE internship_reports SET supervisor_signature_internship_report = now(), 
                                                denied_internship_report = DEFAULT
                WHERE id_internship_reports = $idDec";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        
        $_SESSION['feedback'] = 'successAllowedReport';
        $_SESSION['btn'] = 1;
        
        $query = "SELECT ir.*, id.fk_advisor, p.name_person FROM internship_data id, internship_reports ir, person p WHERE ir.id_internship_reports = $idDec AND p.id_person = id.fk_student AND ir.fk_internship_data = id.id_internship_data";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $return = $stmt->fetch(PDO::FETCH_ASSOC); 
        $type_report = $return['type_internship_report'];
        $messageText = "O(A) ".$type_report." foi aprovado(a) pelo(a) supervisor(a) do(a) estagiário(a) ".$return['name_person']."!\nEntre na sua conta do Sistema de Controle de Estágio da CTI e leia o respectivo relatório.";

        $query = "SELECT p.email_person FROM person p WHERE id_person = ".$return['fk_advisor']."";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $return = $stmt->fetch(PDO::FETCH_ASSOC); 

        $messageEmail =  $return['email_person'];
       // exit();
     }
    else if ($type == 'deny') 
    {
        $query = "UPDATE internship_reports SET denied_internship_report = TRUE
                                   WHERE id_internship_reports = $idDec";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        $_SESSION['feedback'] = 'successDeniedReport';
        $_SESSION['btn'] = 1;
        
        $query = "SELECT ir.*, p.email_person FROM internship_reports ir, person p, internship_data id WHERE ir.id_internship_reports = $idDec AND  p.id_person = id.fk_student AND ir.fk_internship_data = id.id_internship_data";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $return = $stmt->fetch(PDO::FETCH_ASSOC);         
        $type_report = $return['type_internship_report'];

        $messageText = "O(A) ".$return['type_internship_report']." foi negado(a) por seu(sua) supervisor(a)! \n[Escreva aqui as modificações que o estagiário deve fazer no relatório]";

        $messageEmail =  $return['email_person'];
    }
    
    $messageSubject = "Estado do(a) ".$type_report;
    $mail_body = "Estado do(a) ".$type_report." \n\n$messageText"; // Sem HTML
    $mail_body = urlencode($mail_body); //transforma os caracteres de string para serem aceitos no URL;

    $_SESSION['email'] = 1;
    header("Location: https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=".$messageEmail."&su=".$messageSubject."&body=".$mail_body);        

}
catch (Exception $ex)
{
    if ($type == 'allow') 
    {
        echo $ex->getMessage();
        $_SESSION['feedback'] = 'errorAllowedReport';
        $_SESSION['btn'] = 1;
    }
    else if ($type == 'deny') 
    {
        $_SESSION['feedback'] = 'errorDeniedReport';
        $_SESSION['btn'] = 1;
        echo $ex->getMessage();
    }
   // header('Location: ../../../../views/supervisor/supervisorPage.php');
}
