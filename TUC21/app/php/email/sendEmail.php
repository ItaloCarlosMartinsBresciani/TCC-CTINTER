<?php

session_start();

require_once '../../db/connect.php'; 
require_once ('../functions.php');
require_once '../../../vendor/autoload.php';
require_once 'envEmail.php';

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

$messageEmail = cleanString($_POST['message-email']); 
$messageText = cleanString($_POST['message-text']); 
$messageSubject = cleanString($_POST['message-subject']);
$messageName = ''; 

$acessKey = bin2hex(random_bytes(32));

$query = 'INSERT INTO tokens VALUES(DEFAULT, :acessKey, :validDate);';
    
$stmt = $conn->prepare($query);

$validDate = $expires = date("U") + (3600 * 24 * 7);

$stmt->bindValue(':acessKey', $acessKey); 
$stmt->bindValue(':validDate', $validDate);

$stmt->execute();

if(isset($_SESSION['isAuth']) && $_SESSION['idUser'] == -1)
{

    if ($_SESSION['type'] == 'university')
    {
        $privateLink = 'http://localhost/UNESP-Internship/TUC21/views/putUniversityInformation.php?key='.$acessKey; // Link único de acesso
        $messageTitle = 'Cadastrar Universidade';
    }

    if($_SESSION['type'] == 'company')
    {
        $privateLink = 'http://localhost/UNESP-Internship/TUC21/views/putCompanyInformation.php?key='.$acessKey; // Link único de acesso
        $messageTitle = 'Cadastrar Empresa';
    }

    if(!empty($messageEmail) && !empty($messageText) && !empty($messageSubject)) { 
        try {
            //Server settings
            $mail->isSMTP();                                        //Send using SMTP
            $mail->Host       = $ENV_MAIL_HOST;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                               //Enable SMTP authentication
            $mail->Username   = $ENV_MAIL_USER;                     //SMTP username
            $mail->Password   = $ENV_MAIL_PASSWORD;                 //SMTP password
            $mail->SMTPSecure = $mail::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = $ENV_MAIL_PORT;                     //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            //Recipients
            $mail->setFrom($ENV_MAIL_USER, 'Controle de Estagio - UNESP'); // Nome
            $mail->addAddress($messageEmail, $messageName);             // email + Nome de quem recebe
            $mail->addReplyTo($ENV_MAIL_USER, 'Administrador'); // Email + Nome para resposta
        
            //Content
            $mail->isHTML(true); //Set email format to HTML
            $mail->Subject = "Estagio UNESP"; // Assunto
            $mail->Body = 
                "
                <html lang='pt-br'>
                    <head>
                        <meta charset='UTF-8'>
                        <style>    
                            p {
                                font-size: 12px;
                                margin-bottom: 15px;
                                width: 100%;
                                padding-bottom: 10px;
                                color: black;
                            }
    
                            button {
                                background: #007bff;
                                color: rgb(255, 255, 255);
                                font-size: 14px;
                                cursor: pointer;
                                padding: 10px 15px;
                                outline: none;
                                border-radius: 8px;
                                border: none;
                            }
                        </style>
                    </head>
                    <body>
                        <h1>$messageTitle</h1>
    
                        <p>$messageText</p>
    
                        <a href='$privateLink'> 
                            <button>Cadastrar</button>
                        </a>
                    </body>
                </html>
                ";
            
            $mail->AltBody = "$messageText: $privateLink"; // Sem HTML
    
            $mail->send();
    
            $_SESSION['feedback'] = 'successEmail';

                header('Location: ../../../views/admin/adminPage.php');
        } 
        catch (Exception $e) {
            $_SESSION['feedback'] = 'errorEmail';
            if($_SESSION['idUser'] == -1)
            {
                //header('Location: ../../../views/admin/adminPage.php');
                //exit();
                echo $e->getMessage();
            }            
        }
    }   
    else {
        echo 'erro';
    }
}
else {
    echo "<script>alert('Usuário não reconhecido.');</script>";
    header("Location: ../../../index.php ");
    exit();
} 


