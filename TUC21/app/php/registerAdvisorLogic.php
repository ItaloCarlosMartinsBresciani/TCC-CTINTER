<?php

session_start();
    
    require_once('functions.php');
    require_once('../db/connect.php');
    
    $inf[] = "cic-advisor";
    $inf[] = "department-advisor";

    $id_advisor = cleanString($_GET["id_advisor"]); 

    foreach($inf as $value) {  
        $_SESSION[$value] = cleanString($_SESSION[$value]);  
        
        
        if(empty($_SESSION[$value])) { //se depois de limpar não há informação dentro
            echo "<script>alert('Os dados digitados não são válidos! Tente o cadastro novamente!');</script>";
            echo "<script>location = '../../views/putAdvisorInformation.php?id_advisor=".$_GET["id_advisor"]."&error=1';</script>";
            exit();
        }
    } 

    // Cadastro do Orientador
    try
    {
        //Obtendo id da Universidade
        $query = 'SELECT fk_university FROM university_employee WHERE fk_id = :fk_advisor';

        $stmt = $conn->prepare($query);

        $stmt->bindValue(':fk_advisor', $id_advisor); 

        $stmt->execute();

        $return = $stmt->fetch(PDO::FETCH_ASSOC); 
        
        $idUniversity = $return['fk_university'];
        
        //Cadastrando professor como orientador
        $query = 'INSERT INTO advisor VALUES('.$id_advisor.', :cic_advisor, :department_advisor, DEFAULT, TRUE, '.$idUniversity.')';
        $stmt = $conn->prepare($query);

        $stmt->bindValue(':cic_advisor', $_SESSION["cic-advisor"]);
        $stmt->bindValue(':department_advisor', $_SESSION["department-advisor"]);

        $stmt->execute();

        echo "
            <script>
                alert('Dados do Professor cadastrados com sucesso!');
            </script>
        ";
        echo "<script>location = '../../views/professor/professorPage.php';</script>";
    }
    catch (Exception $e)
    {
        
        echo "<script>alert('".$e->getMessage()."');</script>";
        echo "<script>alert('Erro no Cadastro!');</script>";
        echo "<script>location = '../../views/putAdvisorInformation.php?id_advisor=".$id_advisor."';</script>";
        exit();
    }

   echo "<script>location = '../../index.php';</script>";
   exit();