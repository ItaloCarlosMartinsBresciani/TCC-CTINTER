<?php
session_start();

if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 7){
    echo "<script>alert('Usuário não reconhecido.');</script>";
    echo "<script>location= '../../../index.php';</script>";
    exit();
}

require_once('functions.php');
require_once('../db/connect.php');

try{

    $type = $_POST["report-name"];
    $id_internship = decodeId($_POST["id-internship"]); //report

    $query= "SELECT name_person, email_person FROM person WHERE id_person = ".$_SESSION["idUser"].";";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC);

    if($return)
    {
        $nameSupervisor =  $return['name_person']; 
    }
    
    // Pasta onde o file-report vai ser salvo
    $_UP['pasta'] = 'email/reports_upload/';
    
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
        echo "<script>alert('Por favor, envie arquivo com as seguintes extensões: pdf, odt, doc, docx');</script>";
    }
    
    // Faz a verificação do tamanho do file-report
    else if ($_UP['tamanho'] < $_FILES['file-report']['size']) {
        echo "<script>alert('O arquivo enviado é muito grande, envie arquivos de até 2MB.');</script>";

    }
    
    // O file-report passou em todas as verificações, hora de tentar movê-lo para a pasta
    else {
        // Primeiro verifica se deve trocar o nome do file-report
        if ($_UP['renomeia'] == true) {
            // Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .pdf
            $nome_antigo = explode('.', $_FILES['file-report']['name']);
            $nome_final = $nome_antigo[0].'_'.str_replace(' ','',$nameSupervisor).'.'.$extensao;//$return["name_internship_report"].'.pdf';
            //nome + tipo + data
        } else {
            // Mantém o nome original do file-report
            $nome_final = $_FILES['file-report']['name'];
        }
        
        // Depois verifica se é possível mover o file-report para a pasta escolhida
        if (move_uploaded_file($_FILES['file-report']['tmp_name'], $_UP['pasta'] . $nome_final)) {
            // Upload efetuado com sucesso, exibe uma mensagem e um link para o file-report
            //echo "<script>alert('Upload efetuado com sucesso!');</script>";     
            $_SESSION['section'] = 1;  
            $_SESSION["link"] = $nome_final;
            echo "<script>location= '../../views/visualizeReport.php?type=".$type."&id=".codeId($id_internship)."';</script> ";             
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
?>