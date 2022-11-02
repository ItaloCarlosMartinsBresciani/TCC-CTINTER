<?php
session_start();
//echo "<script>alert('aaaa');</script>";
if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 1){
    echo "<script>alert('Usuário não reconhecido.');</script>";
    echo "<script>location= '../../../index.php';</script>";
    exit();
}

require_once '../../db/connect.php'; 
require_once '../functions.php';
require_once '../../../vendor/autoload.php';

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

//Recuperando email do aluno
$query= "SELECT email_person FROM person WHERE id_person = '".$_SESSION['idUser']."';";

$stmt = $conn->prepare($query);

$stmt->execute();

$return = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(count($return))
{
    $emailSender = $return[0]['email_person']; //email do aluno   
}

//Variáveis
$messageEmail = cleanString($_POST['message-email']); //email do professor que vai receber o pedido de edição
$messageSubject = cleanString($_POST['message-subject']);
$messageText = cleanString($_POST['message-text']);

//echo "<script>alert('".$_POST['message-email']."');</script>";

if(!empty($messageEmail) && !empty($messageText) && !empty($messageSubject)) 
{ 
    try 
    {
        //Inserindo na tabela de edição
        try{            
            $error_message = "Erro na atualização da tabela de pedidos para edição!";
            //Atualizando dados na tabela de edição
            $query = "UPDATE change_data SET allowed = FALSE, pending_allowance = TRUE, edited = FALSE WHERE fk_id = ".$_SESSION['idUser']."";

            $stmt = $conn->prepare($query);

            $stmt->execute();

            echo "<script>alert('Tabela atualizada com sucesso!');</script>";

            
        }
        catch (Exception $ex)
        {
            echo "<script>alert('".$error_message."');</script>";
            echo "<script>location = 'studentPage.php';</script>";
            exit();
        }

        $mail_body = "Solicitação de Edição \n\n $messageText"; // Sem HTML
        $mail_body = urlencode($mail_body); //transforma os caracteres de string para serem aceitos no URL

        //Enviar
        header("Location: https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=".$messageEmail."&su=".$messageSubject."&body=".$mail_body);
        
    }  
    catch (Exception $e) 
    {     
        echo "<script>alert('".$e->getMessage()."');</script>";  
        $_SESSION['feedback'] = 'errorEmail';
        if($_SESSION['access_level'] == 1)
        {
            echo "<script>location= '../../../views/student/studentPage.php';</script>";
            exit();
        } 
    }
}
else 
{
    echo "<script>alert('Um dos campos do e-mail não foi preenchido.');</script>";
    echo "<script>location= '../../../views/student/components/profileStudent.php';</script> ";
    exit();
} 