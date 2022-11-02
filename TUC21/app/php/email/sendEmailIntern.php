<?php
session_start();

if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 1){
    echo "<script>alert('Usuário não reconhecido.');</script>";
    echo "<script>location= '../../../index.php';</script>";
    exit();
}
//session_start();

require_once '../../db/connect.php'; 
require_once '../functions.php';
require_once '../../../vendor/autoload.php';

if (!isset($_GET['type']))
{
    if (isset($_GET['email_advisor']))
    {
        $emailAdvisor = cleanString($_GET['email_advisor']);
    }
    else
    {
        echo "<script>location= '../../../views/intern/internPage.php';</script>";
        exit();
    }
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    //Recuperando email do diretor
    $query= "SELECT name_person, email_person FROM person WHERE id_person = '".$_SESSION['idUser']."';";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(count($return))
    {
        $emailSender = $return[0]['email_person']; //email do estudante   
        $nameSender = $return[0]['name_person']; 
    }

    $query= "SELECT name_person FROM person WHERE email_person = '".$emailAdvisor."';";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(count($return))
    {
        $nameProfessor = $return[0]['name_person'];  //email do estudante   
    }

    //Variáveis
    $messageEmail = $_GET['email_advisor']; //email do admin que vai receber a solicitação de edição
    $messageSubject = "Estagio CTI";
    $messageText = "Olá ".$nameProfessor."! \nVenho através deste e-mail solicitar que o(a) senhor(a) seja o(a) orientador(a) do meu estágio.\n\nAtenciosamente,\n".$nameSender.".";

    if(!empty($messageEmail) && !empty($messageText) && !empty($messageSubject)) 
    { 
        try 
        {
            $mail_body = "Solicitação de Orientação \n\n$messageText"; 
            $mail_body = urlencode($mail_body); //transforma os caracteres de string para serem aceitos no URL

            //Enviar
            header("Location: https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=".$messageEmail."&su=".$messageSubject."&body=".$mail_body);
        }  
        catch (Exception $e) 
        {     
            echo "<script>alert('".$e->getMessage()."');</script>";  
            $_SESSION['feedback'] = 'errorEmail';
            if($_SESSION['access_level'] == 10)
            {
                echo "<script>location= '../../../views/student/internPage.php';</script>";
                exit();
            } 
        }
    }
    else 
    {
        echo "<script>alert('Um dos campos do e-mail não foi preenchido.');</script>";
        echo "<script>location= '../../../views/student/components/internPage.php';</script> ";
        exit();
    }
}
else //se tiver valor no type_request
{
    $type = cleanString($_GET['type']);
    

    if ($type == "profile")
    {
        //Recuperando email do estagiário
        $query= "SELECT name_person, email_person FROM person WHERE id_person = '".$_SESSION['idUser']."';";

        $stmt = $conn->prepare($query);

        $stmt->execute();

        $return = $stmt->fetch(PDO::FETCH_ASSOC);

        if(count($return))
        {
            $emailSender = $return['email_person']; //email do estagiário   
            $nameSender = $return['name_person']; 
        }

        //Variáveis
        $messageEmail = cleanString($_POST['message-email']); //email do orientador
        $messageSubject = cleanString($_POST['message-subject']); //assunto
        $messageText = cleanString($_POST['message-text']); //texto - mensagem
        


        if(!empty($messageEmail) && !empty($messageText) && !empty($messageSubject)) 
        {
            try{    
                
                //Atualizando dados na tabela de edição
                $query = "UPDATE change_data SET allowed = FALSE, pending_allowance = TRUE, edited = FALSE WHERE fk_id = ".$_SESSION['idUser']."";

                $stmt = $conn->prepare($query);

                $stmt->execute();
                
            }
            catch (Exception $ex)
            {
                echo "<script>alert('Erro na atualização da tabela de pedidos para edição!');</script>";
                echo "<script>location = 'internPage.php';</script>";
                exit();
            }

            $mail_body = "Solicitação de Edição \n\n $messageText"; // Sem HTML
            $mail_body = urlencode($mail_body); //transforma os caracteres de string para serem aceitos no URL

            //Enviar
            header("Location: https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=".$messageEmail."&su=".$messageSubject."&body=".$mail_body);
        }
        else 
        {
            echo "<script>alert('Um dos campos do e-mail não foi preenchido.');</script>";
            echo "<script>location= '../../../views/intern/internPage.php';</script> ";
            exit();
        }
        
    }
    else if ($type == "internship")
    {
        //Recuperando email do estagiário
        $query= "SELECT name_person, email_person FROM person WHERE id_person = '".$_SESSION['idUser']."';";

        $stmt = $conn->prepare($query);

        $stmt->execute();

        $return = $stmt->fetch(PDO::FETCH_ASSOC);

        if(count($return))
        {
            $emailSender = $return['email_person']; //email do estagiário   
            $nameSender = $return['name_person']; 
        }

        //Variáveis
        $messageEmail = cleanString($_POST['message-email']); //email do supervisor
        $messageSubject = cleanString($_POST['message-subject']); //assunto
        $messageText = cleanString($_POST['message-text']); //texto - mensagem
        


        if(!empty($messageEmail) && !empty($messageText) && !empty($messageSubject)) 
        {
            try{            

                $query = 'SELECT id_internship_data FROM internship_data WHERE fk_student = '.$_SESSION['idUser'].' and valid = TRUE';
                            
                $stmt = $conn->prepare($query);

                $stmt->execute();

                $return = $stmt->fetch(PDO::FETCH_ASSOC);  

                $id_internship = $return['id_internship_data'];

                //Atualizando dados na tabela de edição
                $query = "UPDATE change_data_internship SET allowed = FALSE, pending_allowance = TRUE, edited = FALSE WHERE fk_id = ".$id_internship."";

                $stmt = $conn->prepare($query);

                $stmt->execute();
                
            }
            catch (Exception $ex)
            {
                echo "<script>alert('Erro na atualização da tabela de pedidos para edição!');</script>";
                echo "<script>location = 'internPage.php';</script>";
                exit();
            }

            $mail_body = "Solicitação de Edição \n\n $messageText"; // Sem HTML
            $mail_body = urlencode($mail_body); //transforma os caracteres de string para serem aceitos no URL

            //Enviar
            header("Location: https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=".$messageEmail."&su=".$messageSubject."&body=".$mail_body);
        }
        else 
        {
            echo "<script>alert('Um dos campos do e-mail não foi preenchido.');</script>";
            echo "<script>location= '../../../views/intern/internPage.php';</script> ";
            exit();
        }
    }
}

