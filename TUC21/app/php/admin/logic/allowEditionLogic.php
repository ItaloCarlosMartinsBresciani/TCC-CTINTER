<?php

session_start();

if(!isset($_SESSION['isAuth']) || $_SESSION['idUser'] != -1){
    //header("Location: ../../../../index.php ");
    //exit();
}

require_once('../../functions.php');
require_once('../../../db/connect.php');

if (isset($_GET['type']) && isset($_GET['id']) && isset($_GET['category'])) {
    $type = cleanString($_GET['type']);
    $category = cleanString($_GET['category']);

    try {
        $id = cleanString($_GET['id']);

        $idDec = decodeId($id);
    }
    catch (Exception $e) {
       // header('Location: ../../../../views/admin/adminPage.php');
    }   
} else {
   // header('Location: ../../../../views/admin/adminPage.php');
}

try
{
    if ($category == 'person')
    {
        if ($type == 'allow') 
        {
            $query = "UPDATE change_data SET pending_allowance = FALSE, allowed = TRUE WHERE fk_id = $idDec";
            $stmt = $conn->prepare($query);
            $stmt->execute();

           // $_SESSION['feedback'] = 'successAllowedEdition';
            $_SESSION['btn'] = 1;
            
            $messageText = "Sua solicitação de edição dos dados pessoais foi aceita!\nEntre em sua conta no Sistema de Controle de Estágio da CTI, acesse seu perfil e edite seus dados!";
            header('Location: ../../../../views/admin/adminPage.php');
        }
        else if ($type == 'deny') 
        {
            $query = "UPDATE change_data SET pending_allowance = FALSE, allowed = FALSE WHERE fk_id = $idDec";
            $stmt = $conn->prepare($query);
            $stmt->execute();

            //$_SESSION['feedback'] = 'successDeniedEdition';
            $_SESSION['btn'] = 1;

            $messageText = "Sua solicitação de edição dos dados pessoais no Sistema de Controle de Estágio da CTI foi negada!";
            header('Location: ../../../../views/admin/adminPage.php');
        }
        
        $query = "SELECT email_person FROM person WHERE id_person = $idDec";

        $stmt = $conn->prepare($query);

        $stmt->execute();

        $return = $stmt->fetch(PDO::FETCH_ASSOC); 

        $messageEmail =  $return['email_person'];
    }
    else if ($category == 'university')
    {
        if ($type == 'allow') 
        {
            $query = "UPDATE change_data_universities SET pending_allowance = FALSE, allowed = TRUE WHERE fk_id = $idDec";
            $stmt = $conn->prepare($query);
            $stmt->execute();

            //$_SESSION['feedback'] = 'successAllowedEdition';
            $_SESSION['btn'] = 1;
            
            $messageText = "Sua solicitação de edição dos dados da universidade foi aceita!\nEntre em sua conta no Sistema de Controle de Estágio da CTI, acesse seu perfil e edite seus dados!";
            header('Location: ../../../../views/admin/adminPage.php');
        }
        else if ($type == 'deny') 
        {
            $query = "UPDATE change_data_universities SET pending_allowance = FALSE, allowed = FALSE WHERE fk_id = $idDec";
            $stmt = $conn->prepare($query);
            $stmt->execute();

            //$_SESSION['feedback'] = 'successDeniedEdition';
            $_SESSION['btn'] = 1;

            $messageText = "Sua solicitação de edição dos dados da universidade no Sistema de Controle de Estágio da CTI foi negada!";
            header('Location: ../../../../views/admin/adminPage.php');
        }

        $query = "SELECT p.email_person FROM person p, university u WHERE u.fk_principal = p.id_person AND u.id_university = $idDec";

        $stmt = $conn->prepare($query);

        $stmt->execute();

        $return = $stmt->fetch(PDO::FETCH_ASSOC); 

        $messageEmail =  $return['email_person'];
    }
    else if ($category == 'company')
    {
        if ($type == 'allow') 
        {
            $query = "UPDATE change_data_companies SET pending_allowance = FALSE, allowed = TRUE WHERE fk_id = $idDec";
            $stmt = $conn->prepare($query);
            $stmt->execute();

            //$_SESSION['feedback'] = 'successAllowedEdition';
            $_SESSION['btn'] = 1;
            
            $messageText = "Sua solicitação de edição dos dados da universidade foi aceita!\nEntre em sua conta no Sistema de Controle de Estágio da CTI, acesse seu perfil e edite seus dados!";
            header('Location: ../../../../views/admin/adminPage.php');
        }
        else if ($type == 'deny') 
        {
            $query = "UPDATE change_data_companies SET pending_allowance = FALSE, allowed = FALSE WHERE fk_id = $idDec";
            $stmt = $conn->prepare($query);
            $stmt->execute();

            //$_SESSION['feedback'] = 'successDeniedEdition';
            $_SESSION['btn'] = 1;

            $messageText = "Sua solicitação de edição dos dados da universidade no Sistema de Controle de Estágio da CTI foi negada!";
            header('Location: ../../../../views/admin/adminPage.php');
        }

        $query = "SELECT c.email_company FROM company c WHERE c.id_company = $idDec";

        $stmt = $conn->prepare($query);

        $stmt->execute();

        $return = $stmt->fetch(PDO::FETCH_ASSOC); 

        $messageEmail =  $return['email_company'];
    }

    
    $messageSubject = "Solicitação de edição";
    $mail_body = "Estado de Solicitação de Edição \n\n$messageText"; // Sem HTML
    $mail_body = urlencode($mail_body); //transforma os caracteres de string para serem aceitos no URL;

    header("Location: https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=".$messageEmail."&su=".$messageSubject."&body=".$mail_body);        

}
catch (Exception $ex)
{
    if ($type == 'allow') 
    {
        $_SESSION['feedback'] = 'errorAllowedEdition';
        $_SESSION['btn'] = 1;
    }
    else if ($type == 'deny') 
    {
        $_SESSION['feedback'] = 'errorDeniedEdition';
        $_SESSION['btn'] = 1;
    }
    header('Location: ../../../../views/admin/adminPage.php');
}
