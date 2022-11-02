<?php

use Google\Service\CloudTrace\Span;

session_start();
    
    require_once('functions.php');
    require_once('../db/connect.php');

    $id_intern = $_POST["id-student"];
    
    $id_supervisor = $_POST['id-supervisor'];
    // Cadastro do Supervisor
    try
    {
        $query = 'UPDATE internship_data SET fk_supervisor = '.$id_supervisor.' WHERE fk_student = '.$id_intern.'';

        $stmt = $conn->prepare($query);

        $stmt->execute();

        echo "
            <script>
                alert('Indicação do supervisor realizada com sucesso!');
            </script>
        ";

        
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
        echo "<script>alert('Erro na Indicação do Supervisor!');</script>";
        echo "<script>location = '../../views/company/companyPage.php';</script>";
        exit();
    }

   echo "<script>location = '../../views/company/companyPage.php';</script>";
   exit();

?>