<?php

use Google\Service\CloudTrace\Span;

session_start();
    
    require_once('functions.php');
    require_once('../db/connect.php');

    
    $key = cleanString($_GET['key']);

    $id_company = cleanString($_GET['id_company']);
    
    //echo "<script>alert('".$id_principal."');</script>";

    $inf[] = "name-supervisor";
    $inf[] = "email-supervisor";
    $inf[] = "telephone-supervisor";
    $inf[] = "cpf-supervisor";
    $inf[] = "rg-supervisor";
    $inf[] = "treatment-supervisor";
    $inf[] = "cic-supervisor";
    $inf[] = "role-supervisor";

    foreach($inf as $value) { 
        $_SESSION[$value] = cleanString($_SESSION[$value]);  
        
        
        if(empty($_SESSION[$value])) { //se depois de limpar não há informação dentro
            echo "<script>alert('Os dados digitados não são válidos! Tente o cadastro novamente!');</script>";
            echo "<script>location = '../../views/putSupervisorInformation.php?key=".$_GET['key']."&id_company=".$id_company."&error=1';</script>";
            exit();
        }
    } 

    // Cadastro do Supervisor
    try
    {
        //Obtendo Id da Empresa
        $query = 'SELECT name_company FROM company WHERE id_company = '.$id_company.'';

        $stmt = $conn->prepare($query);

        $stmt->execute();

        $return = $stmt->fetch(PDO::FETCH_ASSOC); 

        $name_company = $return['name_company'];

        //Inserindo supervisor na tabela person
        $query = 'INSERT INTO person VALUES(DEFAULT, :cpf_person, :name_person, :email_person, :telephone_person, NULL, :rg_person, DEFAULT, DEFAULT, :treatment_person, 7, DEFAULT, :who_edited, :who_invited, DEFAULT, TRUE);';

        $stmt = $conn->prepare($query);

        $stmt->bindValue(':cpf_person', $_SESSION["cpf-supervisor"]);
        $stmt->bindValue(':name_person', $_SESSION["name-supervisor"]);
        $stmt->bindValue(':email_person', $_SESSION["email-supervisor"]);
        $stmt->bindValue(':telephone_person', $_SESSION["telephone-supervisor"]);
        $stmt->bindValue(':rg_person', $_SESSION["rg-supervisor"]);
        $stmt->bindValue(':treatment_person', $_SESSION["treatment-supervisor"]);
        $stmt->bindValue(':who_edited', $_SESSION["name-supervisor"]);
        $stmt->bindValue(':who_invited', $name_company);

        $stmt->execute();

        //Obtendo id Supervisor
        
        $query = 'SELECT id_person FROM person WHERE email_person = :email';

        $stmt = $conn->prepare($query);

        $stmt->bindValue(':email', $_SESSION["email-supervisor"]);

        $stmt->execute();

        $return = $stmt->fetch(PDO::FETCH_ASSOC); 
        
        $idSupervisor = $return['id_person'];

        // Cadastro de supervisor na tabela de funcionários da empresa
        $query = "INSERT INTO company_employee VALUES(".$idSupervisor.", 'Supervisor', :role_company_employee, :cic_company_employee, DEFAULT, TRUE, TRUE,".$id_company.");";

        $stmt = $conn->prepare($query);

        $stmt->bindValue(':cic_company_employee', $_SESSION["cic-supervisor"]);
        $stmt->bindValue(':role_company_employee', $_SESSION["role-supervisor"]);

        $stmt->execute();

        // Cadastro do supervisor na tabela de edição de informações (change_data)
        $query = "INSERT INTO change_data VALUES (DEFAULT, FALSE, NULL, DEFAULT, DEFAULT, ".$idSupervisor.")";

        $stmt = $conn->prepare($query);

        $stmt->execute();

        echo "
            <script>
                alert('Dados do Supervisor cadastrados com sucesso!');
            </script>
        ";

        echo "<script>location = '../../views/supervisor/supervisorPage.php';</script>";
        
    }
    catch (Exception $e)
    {
        echo "<script>alert('".$e->getMessage()."');</script>";
        echo "<script>alert('Erro no Cadastro!');</script>";
        echo "<script>location = '../../views/putSupervisorInformation.php?key=".$_GET['key']."&id_company=".$id_company."';</script>";
        exit();
    }
   

    //Exclusão do Token

   // $query = 'DELETE FROM tokens WHERE token = :token';

    //$stmt = $conn->prepare($query);

   // $stmt->bindValue(':token', $key);

    //$stmt->execute();

   echo "<script>location = '../../index.php';</script>";
   exit();