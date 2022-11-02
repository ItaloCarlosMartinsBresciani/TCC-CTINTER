<?php
session_start();
//echo "<script>alert('aaaa');</script>";
if(!isset($_SESSION['isAuth'])){
    echo "<script>alert('Usuário não reconhecido.');</script>";
    echo "<script>location= '../../../index.php';</script>";
    exit();
}

require_once '../../db/connect.php'; 
require_once '../functions.php';
require_once '../../../vendor/autoload.php';

$type = $_GET['type'];

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

//Recuperando email da companhia
$query= "SELECT email_person FROM person WHERE id_person = '".$_SESSION['idUser']."';";

$stmt = $conn->prepare($query);

$stmt->execute();

$return = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(count($return))
{
    $emailSender = $return[0]['email_person']; //email do professor   
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
    $idCompany = $_SESSION['idUser'];
    $privateLink = 'http://localhost/UNESP-Internship/TUC21/views/putSupervisorInformation.php?key='.$acessKey.'&id_company='.$idCompany; // Link único de acesso
    $messageEmail = cleanString($_POST['message-email']); //email do aluno que vai receber o link
    $messageSubject = cleanString($_POST['message-subject']);
    $messageText = cleanString($_POST['message-text']);


    if(!empty($messageEmail) && !empty($messageText) && !empty($messageSubject)) 
    { 
        try 
        {
            $mail_body = "Cadastrar Supervisor \n\n $messageText: \n\n $privateLink"; // Sem HTML
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
                echo "<script>location= '../../../views/company/companyPage.php';</script>";
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
    $messageEmail = cleanString($_POST['message-email']); //email do admin que vai receber a solicitação de edição
    $messageSubject = cleanString($_POST['message-subject']);
    $messageText = cleanString($_POST['message-text']);

    if(!empty($messageEmail) && !empty($messageText) && !empty($messageSubject)) 
    { 
        try 
        {
            //Inserindo na tabela de edição
            try{            
                //Atualizando dados na tabela de edição
                $query = "UPDATE change_data_companies SET allowed = FALSE, pending_allowance = TRUE, edited = FALSE WHERE fk_id = ".$_SESSION['idUser']."";

                $stmt = $conn->prepare($query);

                $stmt->execute();

                echo "<script>alert('Tabela atualizada com sucesso!');</script>";

            }
            catch (Exception $ex)
            {
                echo "<script>alert('Erro na atualização da tabela de pedidos para edição!Erro na atualização da tabela de pedidos para edição!');</script>";
                echo "<script>location = 'companyPage.php';</script>";
                exit();
            }

            $mail_body = "Solicitação de Edição \n\n$messageText"; // Sem HTML
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
                echo "<script>location= '../../../views/company/companyPage.php';</script>";
                exit();
            } 
        }
    }
    else 
    {
        echo "<script>alert('Um dos campos do e-mail não foi preenchido.');</script>";
        echo "<script>location= '../../../views/company/components/profileCompany.php';</script> ";
        exit();
    } 
}





