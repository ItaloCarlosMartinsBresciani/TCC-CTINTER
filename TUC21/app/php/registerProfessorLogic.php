<?php

use Google\Service\CloudTrace\Span;

session_start();
    
    require_once('functions.php');
    require_once('../db/connect.php');

    
    $key = cleanString($_GET['key']);

    $id_coded = cleanString($_GET['id_principal']);
    $id_principal = decodeId($id_coded);
    

    $inf[] = "name-professor";
    $inf[] = "email-professor";
    $inf[] = "telephone-professor";
    $inf[] = "cpf-professor";
    $inf[] = "rg-professor";
    $inf[] = "treatment-professor";
    

    foreach($inf as $value) {  //yes daddy -> sagrado 
        $_SESSION[$value] = cleanString($_SESSION[$value]);  
        
        
        if(empty($_SESSION[$value])) { //se depois de limpar não há informação dentro
            echo "<script>alert('Os dados digitados não são válidos! Tente o cadastro novamente!');</script>";
            echo "<script>location = '../../views/putProfessorInformation.php?key=".$_GET['key']."&id_principal=".$id_principal."&error=1';</script>";
            exit();
        }
    } 

    // Cadastro do Professor
    try
    {
        $query = 'INSERT INTO person VALUES(DEFAULT, :cpf_person, :name_person, :email_person, :telephone_person, NULL, :rg_person, DEFAULT, DEFAULT, :treatment_person, 2, DEFAULT, :who_edited, :who_invited, DEFAULT, TRUE);';

        $stmt = $conn->prepare($query);

        $stmt->bindValue(':cpf_person', $_SESSION["cpf-professor"]);
        $stmt->bindValue(':name_person', $_SESSION["name-professor"]);
        $stmt->bindValue(':email_person', $_SESSION["email-professor"]);
        $stmt->bindValue(':telephone_person', $_SESSION["telephone-professor"]);
        $stmt->bindValue(':rg_person', $_SESSION["rg-professor"]);
        $stmt->bindValue(':treatment_person', $_SESSION["treatment-professor"]);
        $stmt->bindValue(':who_edited', $_SESSION["name-professor"]);
        $stmt->bindValue(':who_invited', "Diretor");

        $stmt->execute();

        //Obtendo id Professor
        
        $query = 'SELECT id_person FROM person WHERE email_person = :email';

        $stmt = $conn->prepare($query);

        $stmt->bindValue(':email', $_SESSION["email-professor"]);

        $stmt->execute();

        $return = $stmt->fetch(PDO::FETCH_ASSOC); 
        
        $idProfessor = $return['id_person'];

        // Obtendo id Universidade
        $query = 'SELECT id_university FROM university WHERE fk_principal = :fk_principal';

        $stmt = $conn->prepare($query);

        $stmt->bindValue(':fk_principal', $id_principal); 

        $stmt->execute();

        $return = $stmt->fetch(PDO::FETCH_ASSOC); 
        
        $idUniversity = $return['id_university'];


        // Cadastro de professor na tabela de funcionários da universidade
        $query = 'INSERT INTO university_employee VALUES('.$idProfessor.', :role_university_employee, :cpf_person, :business_sector_professor, TRUE, '.$idUniversity.');';

        $stmt = $conn->prepare($query);

        $stmt->bindValue(':cpf_person', $_SESSION["cpf-professor"]);
        $stmt->bindValue(':business_sector_professor', $_SESSION["business-sector-professor"]);
        $stmt->bindValue(':role_university_employee', "Professor");

        $stmt->execute();

        // Cadastro do professor na tabela de edição de informações (change_data)
        $query = "INSERT INTO change_data VALUES (DEFAULT, FALSE, NULL, DEFAULT, DEFAULT, ".$idProfessor.")";

        $stmt = $conn->prepare($query);

        $stmt->execute();

        echo "
            <script>
                alert('Dados do Professor cadastrados com sucesso!');
            </script>
        ";
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
        exit();
        //echo "<script>alert('".$e->getMessage()."');</script>";
        echo "<script>alert('Erro no Cadastro!');</script>";
        echo "<script>location = '../../views/putProfessorInformation.php?key=".$_GET['key']."';</script>";
        exit();
    }

   echo "<script>location = '../../index.php';</script>";
   exit();