<?php

session_start();

if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 2){
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
        header('Location: ../../../../views/professor/professorPage.php');
    }   
} else {
    header('Location: ../../../../views/professor/professorPage.php');
}

try
{
    if ($type == 'allow') 
    {
        $query = "UPDATE change_data SET pending_allowance = FALSE, allowed = TRUE WHERE fk_id = $idDec";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        $_SESSION['feedback'] = 'successAllowedEdition';
        $_SESSION['btn'] = 1;
        
        $messageText = "Sua solicitação de edição dos dados pessoais foi aceita!\nEntre em sua conta no Sistema de Controle de Estágio da CTI, acesse seu perfil e edite seus dados!";
    }
    else if ($type == 'deny') 
    {
        $query = "UPDATE change_data SET pending_allowance = FALSE, allowed = FALSE WHERE fk_id = $idDec";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        $_SESSION['feedback'] = 'successDeniedEdition';
        $_SESSION['btn'] = 1;

        $messageText = "Sua solicitação de edição dos dados pessoais no Sistema de Controle de Estágio da CTI foi negada!";
    }

    $query = "SELECT email_person FROM person WHERE id_person = $idDec";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC); 

    $messageEmail =  $return['email_person'];
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
    header('Location: ../../../../views/professor/professorPage.php');
}
