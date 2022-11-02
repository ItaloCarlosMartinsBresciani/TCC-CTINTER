<?php
session_start();

if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 10){
    echo "<script>alert('Usuário não reconhecido.');</script>";
    echo "<script>location= '../../../index.php';</script>";
    exit();
}
//session_start();

require_once '../../db/connect.php'; 
require_once '../functions.php';
require_once '../../../vendor/autoload.php';

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

//Recuperando tipo de e-mail
$type = $_GET['type'];

//Recuperando email do diretor
$query= "SELECT email_person FROM person WHERE id_person = '".$_SESSION['idUser']."';";

$stmt = $conn->prepare($query);

$stmt->execute();

$return = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(count($return))
{
    $emailSender = $return[0]['email_person']; //email do diretor   
}


if($type == 'editionRequest')
{
    //Variáveis
    $idPrincipal = $_SESSION['idUser'];
    $messageEmail = cleanString($_POST['message-email']); //email do admin que vai receber a solicitação de edição
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
                $query = "UPDATE change_data SET allowed = FALSE, pending_allowance = TRUE, edited = FALSE WHERE fk_id = ".$idPrincipal."";

                $stmt = $conn->prepare($query);

                $stmt->execute();

                echo "<script>alert('Tabela atualizada com sucesso!');</script>";

            }
            catch (Exception $ex)
            {
                echo "<script>alert('".$error_message."');</script>";
                echo "<script>location = 'principalPage.php';</script>";
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
            if($_SESSION['access_level'] == 10)
            {
                echo "<script>location= '../../../views/principal/principalPage.php';</script>";
                exit();
            } 
        }
    }
    else 
    {
        echo "<script>alert('Um dos campos do e-mail não foi preenchido.');</script>";
        echo "<script>location= '../../../views/principal/components/profilePrincipal.php';</script> ";
        exit();
    } 

}
else if($type == 'editionRequestUniversity')
{
    //Variáveis
    $messageEmail = cleanString($_POST['message-email']); //email do admin que vai receber a solicitação de edição
    $messageSubject = cleanString($_POST['message-subject']);
    $messageText = cleanString($_POST['message-text']);

    if(!empty($messageEmail) && !empty($messageText) && !empty($messageSubject)) 
    { 
        try 
        {
            //Inserindo na tabela de edição
            try{            
                $error_message = "Erro na atualização da tabela de pedidos para edição da universidade!";

                $query = "SELECT id_university FROM university WHERE fk_principal = ".$_SESSION['idUser']."";

                $stmt = $conn->prepare($query);

                $stmt->execute();

                $return = $stmt->fetch(PDO::FETCH_ASSOC);

                $id_university = $return['id_university'];

                //Atualizando dados na tabela de edição
                $query = "UPDATE change_data_universities SET allowed = FALSE, pending_allowance = TRUE, edited = FALSE WHERE fk_id = ".$id_university."";

                $stmt = $conn->prepare($query);

                $stmt->execute();

                echo "<script>alert('Tabela atualizada com sucesso!');</script>";

            }
            catch (Exception $ex)
            {
                echo "<script>alert('".$error_message."');</script>";
                echo "<script>location = 'principalPage.php';</script>";
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
            if($_SESSION['access_level'] == 10)
            {
                echo "<script>location= '../../../views/principal/principalPage.php';</script>";
                exit();
            } 
        }
    }
    else 
    {
        echo "<script>alert('Um dos campos do e-mail não foi preenchido.');</script>";
        echo "<script>location= '../../../views/principal/components/universityPrincipal.php';</script> ";
        exit();
    } 

}
else if ($type == 'invitation')
{
    //Gerando accesskey
    $acessKey = bin2hex(random_bytes(32));

    $query = 'INSERT INTO tokens VALUES(DEFAULT, :acessKey, :validDate);';
        
    $stmt = $conn->prepare($query);

    $validDate = $expires = date("U") + (3600 * 24 * 7);

    $stmt->bindValue(':acessKey', $acessKey); 
    $stmt->bindValue(':validDate', $validDate);

    $stmt->execute();

    /* pegar id do diretor

    $query = 'SELECT fk_id FROM university_employee WHERE fk_university ='.$idUniversity.'';
        
    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC);*/ 

    //Variáveis
    $idPrincipal = $_SESSION['idUser'];
    $privateLink = 'http://localhost/UNESP-Internship/TUC21/views/putProfessorInformation.php?key='.$acessKey.'&id_principal='.codeId($idPrincipal); // Link único de acesso
    $messageEmail = cleanString($_POST['message-email']); //email do professor que vai receber o link
    $messageSubject = cleanString($_POST['message-subject']);
    $messageText = cleanString($_POST['message-text']);


    if(!empty($messageEmail) && !empty($messageText) && !empty($messageSubject)) 
    { 
        try 
        {
        
            $mail_body = "Cadastrar Professor \n\n $messageText: \n\n $privateLink"; // Sem HTML
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
                echo "<script>location= '../../../views/principal/principalPage.php';</script>";
                exit();
            } 
        }
    }
    else 
    {
        echo "<script>alert('Um dos campos do e-mail não foi preenchido.');</script>";
        echo "<script>location= '../../../views/principal/components/profilePrincipal.php';</script> ";
        exit();
    } 
}
else if ($type == 'coordinatorInvitation')
{
    //Gerando accesskey
    $acessKey = bin2hex(random_bytes(32));

    $query = 'INSERT INTO tokens VALUES(DEFAULT, :acessKey, :validDate);';
        
    $stmt = $conn->prepare($query);

    $validDate = $expires = date("U") + (3600 * 24 * 7);

    $stmt->bindValue(':acessKey', $acessKey); 
    $stmt->bindValue(':validDate', $validDate);

    $stmt->execute();

    //adquirindo o id do professor a partir do e-mail
    $query = "SELECT id_person FROM person WHERE email_person = :email";
        
    $stmt = $conn->prepare($query);
    
    $stmt->bindValue(':email', cleanString($_POST['message-email']));

    $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC);

    //mudando a função do professor para coordenador
    $query = "UPDATE university_employee SET role_university_employee = 'Coordenador' WHERE fk_id = ".$return["id_person"];
        
    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC);

    // mudando nível de acessp de professor(2) para coordenador(9)
    // $query = "UPDATE person SET access_level = 9 WHERE fk_id = ".$return["id_person"];
        
    // $stmt = $conn->prepare($query);

    // $stmt->execute();

    // $return = $stmt->fetch(PDO::FETCH_ASSOC);

    //Variáveis
    $idPrincipal = $_SESSION['idUser'];
   // $privateLink = 'http://localhost/UNESP-Internship/TUC21/views/putCoordinatorInformation.php?key='.$acessKey.'&id_principal='.$idPrincipal; // Link único de acesso
    $messageEmail = cleanString($_POST['message-email']); //email do professor que vai receber o link
    $messageSubject = cleanString($_POST['message-subject']);
    $messageText = cleanString($_POST['message-text']);


    if(!empty($messageEmail) && !empty($messageText) && !empty($messageSubject)) 
    { 
        try 
        {
        
            $mail_body = "Cadastrar Coordenador \n\n$messageText"; // Sem HTML
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
                echo "<script>location= '../../../views/principal/principalPage.php';</script>";
                exit();
            } 
        }
    }
    else 
    {
        echo "<script>alert('Um dos campos do e-mail não foi preenchido.');</script>";
        echo "<script>location= '../../../views/principal/components/profilePrincipal.php';</script> ";
        exit();
    } 
}
else 
{
    echo "<script>alert('Usuário não reconhecido.');</script>";
    echo "<script>location= '../../../index.php';</script> ";
    exit();
} 


