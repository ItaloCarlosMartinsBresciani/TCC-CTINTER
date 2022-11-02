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

$type = $_POST["report-name"];
//$nameCompany = $_POST["name-company"];

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

//Recuperando email do estagiário
$query= "SELECT name_person, email_person FROM person WHERE id_person = '".$_SESSION['idUser']."';";

$stmt = $conn->prepare($query);

$stmt->execute();

$return = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(count($return))
{
    $emailSender = $return[0]['email_person']; //email do estudante   
    $nameSender = $return[0]['name_person']; 
}

//selecionando o id da empresa 
$query= "SELECT fk_company FROM internship_data WHERE id_internship_data = '".$_POST['id-internship-data']."';";

$stmt = $conn->prepare($query);

$stmt->execute();

$return = $stmt->fetch(PDO::FETCH_ASSOC);

$idCompany = $return["fk_company"];

//selecionando nome da empresa
$query= "SELECT name_company FROM company WHERE id_company = '".$idCompany."';";

$stmt = $conn->prepare($query);

$stmt->execute();

$return = $stmt->fetch(PDO::FETCH_ASSOC);

$nameCompany = $return["name_company"];

//Inserção na pasta de uploads --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
try{

    // Pasta onde o file-report vai ser salvo
    $_UP['pasta'] = 'reports_upload/';
    
    
    // Tamanho máximo do file-report (em Bytes)
    $_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb
    
    // Array com as extensões permitidas
    $_UP['extensoes'] = array('pdf', 'odt', 'doc', 'docx');
    
    // Renomeia o file-report? (Se true, o file-report será salvo como .jpg e um nome único)
    $_UP['renomeia'] = true;
    
    // Array com os tipos de erros de upload do PHP
    $_UP['erros'][0] = 'Não houve erro';
    $_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
    $_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
    $_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
    $_UP['erros'][4] = 'Não foi feito o upload do arquivo';
    
    // Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
    if ($_FILES['file-report']['error'] != 0) {
    die("Não foi possível fazer o upload, erro:<br />" . $_UP['erros'][$_FILES['file-report']['error']]);
    exit; // Para a execução do script
    }
    
    // Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar
    
    // Faz a verificação da extensão do file-report
    $extensao = strtolower(end(explode('.', $_FILES['file-report']['name'])));
    if (array_search($extensao, $_UP['extensoes']) === false) {
    echo "Por favor, envie arquivo com as seguintes extensões: pdf, odt, doc, docx";
    }
    
    // Faz a verificação do tamanho do file-report
    else if ($_UP['tamanho'] < $_FILES['file-report']['size']) {
    echo "O arquivo enviado é muito grande, envie arquivos de até 2MB.";
    }
    
    // O file-report passou em todas as verificações, hora de tentar movê-lo para a pasta
    else {
        // Primeiro verifica se deve trocar o nome do file-report
        if ($_UP['renomeia'] == true) {
            // Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .pdf
            $today = date('d-m-Y');
            $nome_final = str_replace(' ','',$nameSender).'_'.str_replace(' ','',$type).'_'.str_replace(' ','',$nameCompany)."_".$today.'.'.$extensao;//$return["name_internship_report"].'.pdf';
            //nome + tipo + data
        } else {
            // Mantém o nome original do file-report
            $nome_final = $_FILES['file-report']['name'];
        }
        
        // Depois verifica se é possível mover o file-report para a pasta escolhida
        if (move_uploaded_file($_FILES['file-report']['tmp_name'], $_UP['pasta'] . $nome_final)) {
            // Upload efetuado com sucesso, exibe uma mensagem e um link para o file-report
            echo "<script>alert('Upload efetuado com sucesso!');</script>";
            //echo '<br/><a href="' . $_UP['pasta'] . $nome_final . '">Clique aqui para acessar o arquivo</a>';
        } else {
            // Não foi possível fazer o upload, provavelmente a pasta está incorreta
            echo "<script>alert('Não foi possível enviar o arquivo, tente novamente');</script>";
            exit();
        }    
    }
}
catch(Exception $e)
{
    echo $e->getMessage();
    exit();
}

//Colocar as informações enviadas no Modal no Banco de dados ---------------------------------------------------------------------------------------------------------------------------------------------------------------

try{
    $query= "SELECT fk_supervisor FROM internship_data WHERE id_internship_data = '".$_POST['id-internship-data']."';";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC);

    $idSupervisor = $return['fk_supervisor'];

    $query= "SELECT name_person, email_person FROM person WHERE id_person = '".$idSupervisor."';";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(count($return))
    {
        $nameSupervisor = $return[0]['name_person'];  
        $emailSupervisor = $return[0]['email_person']; 
    }

    $query= "SELECT id_internship_data FROM internship_data WHERE fk_student = '".$_SESSION["idUser"]."';";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $query= "INSERT INTO public.internship_reports(
        id_internship_reports, link_internship_report, date_internship_report, version_internship_report, type_internship_report, supervisor_signature_internship_report, advisor_signature_internship_report, coordinator_signature_internship_report, fk_internship_data)
        VALUES (DEFAULT, :link_internship_report, DEFAULT, 1, :type_internship_report, NULL, NULL, NULL, :fk_internship_data);";

    $stmt = $conn->prepare($query);


    $stmt->bindValue(':link_internship_report', $nome_final);
    $stmt->bindValue(':type_internship_report', $_POST["report-name"]);
    $stmt->bindValue(':fk_internship_data', $return[0]["id_internship_data"]);

    $stmt->execute();
}
catch (Exception $e)
{
    echo $e->getMessage();
    exit();
}

//Variáveis
$messageEmail = $emailSupervisor; //email do orientador que vai receber o relatório
$messageSubject = "Estagio CTI";
$link = "http://localhost/UNESP-Internship/TUC21/app/php/email/".$_UP['pasta'].$nome_final;
$messageText = "Olá supervisor(a) ".$nameSupervisor."! \nO(A) estagiário(a) ".$nameSender." acabou de fazer o upload do(a) ".$_POST["report-name"].".\nPara visualizar e aprovar este documento, entre em sua página no Sistema de Controle de Estágios da CTI.";


if(!empty($messageEmail) && !empty($messageText) && !empty($messageSubject)) 
{ 
    try 
    {
        $mail_body = "Envio do(a) ".$_POST["report-name"]." \n\n$messageText \n\nClique no link para ter uma visualização prévia do documento: \n$link \n\nAtenciosamente, \n".$nameSender."."; 
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
            echo "<script>location= '../../../views/intern/internPage.php';</script>";
            exit();
        } 
    }
}
else 
{
    echo "<script>alert('Um dos campos do e-mail não foi preenchido.');</script>";
    echo "<script>location= '../../../views/intern/internPage.php';</script> ";
    exit();
}


