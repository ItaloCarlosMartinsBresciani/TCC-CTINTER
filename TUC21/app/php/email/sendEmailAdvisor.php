<?php
session_start();

if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 2){
    echo "<script>alert('Usuário não reconhecido.');</script>";
    echo "<script>location= '../../../index.php';</script>";
    exit();
}

require_once '../../db/connect.php'; 
require_once '../functions.php';
require_once '../../../vendor/autoload.php';

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

$id_intern = $_POST['id-student'];
$id_company = $_POST['id-company'];
$course = $_POST['course-student'];

//Recuperando email do advisor
$query= "SELECT email_person FROM person WHERE id_person = '".$_SESSION['idUser']."';";

$stmt = $conn->prepare($query);

$stmt->execute();

$return = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(count($return))
{
    $emailSender = $return[0]['email_person']; //email do aluno   
}

$query= "SELECT email_person FROM person WHERE id_person = ".$id_intern."";

$stmt = $conn->prepare($query);

$stmt->execute();

$return = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(count($return))
{
    $messageEmail = $return[0]['email_person']; //email do aluno   
}

//Variáveis
$messageSubject = cleanString($_POST['message-subject']);
$messageText = cleanString($_POST['message-text']);


if(!empty($messageEmail) && !empty($messageText ) && !empty($messageSubject)) 
{ 
    try 
    {
        //Inserindo na tabela de edição
        try{   
            $mensagem = "Erro no cadastro do banco de dados!";      
            $query = "INSERT INTO internship_data (course_internship_data, fk_student, fk_advisor, fk_company) VALUES (:course_internship_data, :fk_student, :fk_advisor, :fk_company)";      
            
            $stmt = $conn->prepare($query);
            
            //$stmt->bindValue(':name_internship_data', $name);
            $stmt->bindValue(':course_internship_data', $course);
            $stmt->bindValue(':fk_student', $id_intern);
            $stmt->bindValue(':fk_advisor', $_SESSION["idUser"]);
            $stmt->bindValue(':fk_company', $id_company);

            $stmt->execute();

            echo "<script>alert('Orientando adicionado com sucesso!');</script>";
        }
        catch (Exception $ex)
        {
            echo "<script>alert('".$mensagem."');</script>";
            echo $ex->getMessage();

            //echo "<script>location = 'professorPage.php';</script>";
            //exit();
        }

        $mail_body = "Cadastro como estagiário \n\n$messageText"; // Sem HTML
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