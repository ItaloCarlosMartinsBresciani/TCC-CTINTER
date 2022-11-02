<?php
session_start();
//echo "<script>alert('aaaa');</script>";
if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 9){
    echo "<script>alert('Usuário não reconhecido.');</script>";
    echo "<script>location= '../../../index.php';</script>";
    exit();
}

$_SESSION["email"] = 1;

require_once '../../db/connect.php'; 
require_once '../functions.php';
require_once '../../../vendor/autoload.php';

$type = $_GET['type'];

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

//Recuperando email do coordenador
$query= "SELECT email_person FROM person WHERE id_person = '".$_SESSION['idUser']."';";

$stmt = $conn->prepare($query);

$stmt->execute();

$return = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(count($return))
{
    $emailSender = $return[0]['email_person']; //email do coordenador   
}

if($type == 'invitation')
{
    //Gerando accesskey
    $acessKey = bin2hex(random_bytes(32));

    $query = 'INSERT INTO tokens VALUES(DEFAULT, :acessKey, :validDate);';
        
    $stmt = $conn->prepare($query);

    $validDate = $expires = date("U") + (3600 * 24 * 7);

    $stmt->bindValue(':acessKey', $acessKey); 
    $stmt->bindValue(':validDate', $validDate);

    $stmt->execute();

    //Variáveis
    $idCoordinator = $_SESSION['idUser'];
    $privateLink = 'http://localhost/UNESP-Internship/TUC21/views/putStudentInformation.php?key='.$acessKey.'&id_coordinator='.codeId($idCoordinator); // Link único de acesso
    $messageEmail = cleanString($_POST['message-email']); //email do aluno que vai receber o link
    $messageSubject = cleanString($_POST['message-subject']);
    $messageText = cleanString($_POST['message-text']);


    if(!empty($messageEmail) && !empty($messageText) && !empty($messageSubject)) 
    { 
        try 
        {
            $mail_body = "Cadastrar Aluno \n\n $messageText: \n\n $privateLink"; // Sem HTML
            $mail_body = urlencode($mail_body); //transforma os caracteres de string para serem aceitos no URL

            //Enviar
            header("Location: https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=".$messageEmail."&su=".$messageSubject."&body=".$mail_body);
            
        }  
        catch (Exception $e) 
        {     
            echo "<script>alert('".$e->getMessage()."');</script>";  
            $_SESSION['feedback'] = 'errorEmail';
            if($_SESSION['access_level'] == 2)
            {
                echo "<script>location= '../../../views/coordinator/coordinatorPage.php';</script>";
                exit();
            } 
        }
    }
    else 
    {
        echo "<script>alert('Usuário não reconhecido.');</script>";
        echo "<script>location= '../../../index.php';</script> ";
        exit();
    } 
}
else if ($type == 'editionRequest')
{
    $messageEmail = cleanString($_POST['message-email']); //email do diretor que vai receber a solicitação de edição
    $messageSubject = cleanString($_POST['message-subject']);
    $messageText = cleanString($_POST['message-text']);

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
                echo "<script>location = 'coordinatorPage.php';</script>";
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
            if($_SESSION['access_level'] == 2)
            {
                echo "<script>location= '../../../views/coordinator/coordinatorPage.php';</script>";
                exit();
            } 
        }
    }
    else 
    {
        echo "<script>alert('Um dos campos do e-mail não foi preenchido.');</script>";
        echo "<script>location= '../../../views/coordinator/components/profileCoordinator.php';</script> ";
        exit();
    } 
}
else if($type == 'invalidate_internship')
{
    $id_internship = decodeId($_GET['id']);
    try{   
        //Atualizando dados na tabela de informações de estágio
        $query = "UPDATE internship_data SET validated_coordinator = FALSE, valid = FALSE WHERE id_internship_data = ".$id_internship;

        $stmt = $conn->prepare($query);

        $stmt->execute();

        //recuperando email do estagiário
        $query = "SELECT p.email_person FROM person p, internship_data i WHERE i.fk_student = p.id_person AND i.id_internship_data = ".$id_internship."";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $return = $stmt->fetch(PDO::FETCH_ASSOC);

        $messageEmail = $return["email_person"]; //email do estagiário que vai receber a mensagem de invalidação de seu estágio
        $messageSubject = "Status dos dados do estágio";
    
        $mail_body = "Os dados recém cadastrados foram invalidados pelo(a) coordenador(a), entre na sua conta e adeque-os. \n\nCorreções: digite as devidas correções."; // Sem HTML
        $mail_body = urlencode($mail_body); //transforma os caracteres de string para serem aceitos no URL
        
        $_SESSION['feedback'] = 'successInvalidateInternship';
        $_SESSION['btn'] = 1;
    
        //Enviar
        header("Location: https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=".$messageEmail."&su=".$messageSubject."&body=".$mail_body);

    }
    catch (Exception $ex)
    {
        $_SESSION['feedback'] = 'errorValidateInternship';
        $_SESSION['btn'] = 1;
        echo "<script>location = 'companyPage.php';</script>";
        echo $ex->getMessage();
        exit();
    }
}
else if($type == 'validate_internship')
{
    $id_internship = decodeId($_GET['id']);
    try{            
        //Atualizando dados na tabela de informações de estágio
        $query = "UPDATE internship_data SET validated_coordinator = TRUE, valid = TRUE WHERE id_internship_data = ".$id_internship."";

        $stmt = $conn->prepare($query);

        $stmt->execute();
        
        //recuperando email do estagiário
        $query = "SELECT p.email_person FROM person p, internship_data i WHERE i.fk_student = p.id_person AND i.id_internship_data = ".$id_internship."";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $return = $stmt->fetch(PDO::FETCH_ASSOC);


        $messageEmail = $return["email_person"]; //email do orientador do estagiário
        $messageSubject = "Status dos dados do estágio";

        $mail_body = "Os dados recém cadastrados do seu estágio foram validados por todas as entidades responsáveis. \nVocê já pode acessar e manipular suas informações no Sistema de Controle de Estágios da CTI!"; // Sem HTML
        $mail_body = urlencode($mail_body); //transforma os caracteres de string para serem aceitos no URL

        $_SESSION['feedback'] = 'successValidateInternship';
        $_SESSION['btn'] = 1;

        //Enviar
        header("Location: https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=".$messageEmail."&su=".$messageSubject."&body=".$mail_body);
    }

    catch (Exception $ex)
    {
        $_SESSION['feedback'] = 'errorValidateInternship';
        $_SESSION['btn'] = 1;
        echo "<script>location = 'companyPage.php';</script>";
        //echo $ex->getMessage();
        exit();
    }
}




