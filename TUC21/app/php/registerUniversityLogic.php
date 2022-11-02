<?php

use Google\Service\CloudTrace\Span;

session_start();
    
    require_once('functions.php');
    require_once('../db/connect.php');

    $key = cleanString($_GET['key']);

    $inf[] = "name-principal";
    $inf[] = "email-principal";
    $inf[] = "telephone-principal";
    $inf[] = "cpf-principal";
    $inf[] = "rg-principal";
    $inf[] = "treatment-principal";
    $inf[] = "cnpj-university";
    $inf[] = "name-university";
    $inf[] = "state-registration-university";
    $inf[] = "corporate-name-university";
    $inf[] = "legal-representative-university";
    $inf[] = "activity-branch-university";
    $inf[] = "address-university";
    $inf[] = "number-address-university";
    $inf[] = "home-page-university";
    $inf[] = "district-university";
    $inf[] = "cep-university";
    $inf[] = "mailbox-university";
    $inf[] = "city-university";
    $inf[] = "state-university";
    $inf[] = "telephone-university";
    $inf[] = "email-university";

    foreach($inf as $value) {           
        $_SESSION[$value] = cleanString($_SESSION[$value]);
        
        if(empty($_SESSION[$value]) && $value != "mailbox-university") { //se depois de limpar não há informação dentro
            echo "<script>alert('Todos os campos devem ser preenchidos corretamente! Faça o cadastro novamente!');</script>";
            echo "<script>location = '../../views/putUniversityInformation.php?key=".$_GET['key']."&error=1';</script>";
            exit();
        }
    } 

    try
    {
        // Cadastro do Diretor
        $query = 'INSERT INTO person VALUES(DEFAULT, :cpf_person, :name_person, :email_person, :telephone_person, NULL, :rg_person, DEFAULT, DEFAULT, :treatment_person, 10, DEFAULT, :who_edited, :who_invited, DEFAULT, TRUE);';

        $stmt = $conn->prepare($query);

        $stmt->bindValue(':cpf_person', $_SESSION["cpf-principal"]);
        $stmt->bindValue(':name_person', $_SESSION["name-principal"]);
        $stmt->bindValue(':email_person', $_SESSION["email-principal"]);
        $stmt->bindValue(':telephone_person', $_SESSION["telephone-principal"]);
        $stmt->bindValue(':rg_person', $_SESSION["rg-principal"]);
        $stmt->bindValue(':treatment_person', $_SESSION["treatment-principal"]);
        $stmt->bindValue(':who_edited', $_SESSION["name-principal"]);
        $stmt->bindValue(':who_invited', "Administrador");

        $stmt->execute();

        // Obtendo id Diretor
        $query = 'SELECT id_person FROM person WHERE email_person = :email';

        $stmt = $conn->prepare($query);

        $stmt->bindValue(':email', $_SESSION["email-principal"]);

        $stmt->execute();

        $return = $stmt->fetch(PDO::FETCH_ASSOC); 
        
        $idPrincipal = $return['id_person'];


        // Cadastro da Instituição de Ensino
        $query = 'INSERT INTO university VALUES(DEFAULT, :cnpj_university, :name_university, :state_registration_university, 
        :corporate_name_university, :legal_representative_university, :activity_branch_university, :address_university, :district_university, 
        :cep_university, :mailbox_university, :city_university, :state_university, :telephone_university, DEFAULT, TRUE, DEFAULT, :who_edited, :number_address_university,
        :home_page_university, :email_university, DEFAULT, :fk_principal);';

        $stmt = $conn->prepare($query);

        $stmt->bindValue(':cnpj_university', $_SESSION['cnpj-university']);
        $stmt->bindValue(':name_university', $_SESSION['name-university']);
        $stmt->bindValue(':state_registration_university', $_SESSION['state-registration-university']);
        $stmt->bindValue(':corporate_name_university', $_SESSION['corporate-name-university']);
        $stmt->bindValue(':legal_representative_university', $_SESSION['legal-representative-university']);
        $stmt->bindValue(':activity_branch_university', $_SESSION['activity-branch-university']);
        $stmt->bindValue(':address_university', $_SESSION['address-university']);
        $stmt->bindValue(':district_university', $_SESSION['district-university']);
        $stmt->bindValue(':cep_university', $_SESSION['cep-university']);
        $stmt->bindValue(':mailbox_university', $_SESSION['mailbox-university']);
        $stmt->bindValue(':city_university', $_SESSION['city-university']);
        $stmt->bindValue(':state_university', $_SESSION['state-university']);
        $stmt->bindValue(':telephone_university', $_SESSION['telephone-university']);
        $stmt->bindValue(':who_edited', $_SESSION["name-principal"]);
        $stmt->bindValue(':home_page_university', $_SESSION['home-page-university']);
        $stmt->bindValue(':email_university', $_SESSION['email-university']);
        $stmt->bindValue(':number_address_university', $_SESSION['number-address-university']);
        $stmt->bindValue(':fk_principal', $idPrincipal);

        $stmt->execute();

        // Obtendo id Universidade
        $query = 'SELECT id_university FROM university WHERE fk_principal = :fk_principal';

        $stmt = $conn->prepare($query);

        $stmt->bindValue(':fk_principal', $idPrincipal); 

        $stmt->execute();

        $return = $stmt->fetch(PDO::FETCH_ASSOC); 
        
        $idUniversity = $return['id_university'];
        
        // Cadastro de diretor na tabela de funcionários da universidade (university_employee)
        $query = 'INSERT INTO university_employee VALUES('.$idPrincipal.', :role_university_employee, :cpf_person, NULL, TRUE, '.$idUniversity.');';

        $stmt = $conn->prepare($query);

        $stmt->bindValue(':cpf_person', $_SESSION["cpf-principal"]);
        $stmt->bindValue(':role_university_employee', "Diretor");

        $stmt->execute();

        $query = "INSERT INTO change_data VALUES (DEFAULT, FALSE, NULL, DEFAULT, DEFAULT, ".$idPrincipal.")";

        $stmt = $conn->prepare($query);

        $stmt->execute();
        
        // Cadastro do diretor na tabela de edição de informações (change_data)
        $query = "INSERT INTO change_data_universities VALUES (DEFAULT, FALSE, NULL, DEFAULT, DEFAULT, ".$idUniversity.")";

        $stmt = $conn->prepare($query);

        $stmt->execute();
        
        // echo "<script>alert('Inseriu na tabela change_data')</script>";

        echo "
            <script>
                alert('Dados cadastrados com sucesso!');
            </script>
        ";
    }
    catch (Exception $e)
    {
        //enviar email com o erro (".$e->getMessage().")*/
        echo "<script>alert('Erro no Cadastro!');</script>";
        echo $e->getMessage();

        $query = 'SELECT id_university FROM university WHERE cnpj_university = :cnpj_university;';
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':cnpj_university', $_SESSION["cnpj-university"]);
        $stmt->execute();        
        $return = $stmt->fetch(PDO::FETCH_ASSOC); 
        $id_university = $return['id_university'];
        
        $query = 'DELETE FROM change_data_universities WHERE fk_id = :id_university;';
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':id_university', $id_university);
        $stmt->execute();
        
        $query = 'DELETE FROM university WHERE cnpj_university = :cnpj_university;';
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':cnpj_university', $_SESSION["cnpj-university"]);
        $stmt->execute();

        //Deletando das tabelas relacionadas à tabela person
        $query = 'SELECT id_person FROM person WHERE cpf_person = :cpf_principal;';
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':cpf_principal', $_SESSION["cpf-principal"]);
        $stmt->execute();
        $return = $stmt->fetch(PDO::FETCH_ASSOC); 
        $id_principal = $return['id_person'];
        
        $query = 'DELETE FROM change_data WHERE fk_id = :id_person;';
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':id_person', $id_principal);
        $stmt->execute();

        $query = 'DELETE FROM university_employee WHERE fk_id = :id_person;';
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':id_person', $id_principal);
        $stmt->execute();

        $query = 'DELETE FROM person WHERE cpf_person = :cpf_person;';
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':cpf_person', $_SESSION["cpf-principal"]);
        $stmt->execute();
        exit();
        echo "<script>location = '../../views/putUniversityInformation.php?key=".$_GET['key']."';</script>";
        exit();
    }
   

    // Exclusão do Token

    $query = 'DELETE FROM tokens WHERE token = :token';

    $stmt = $conn->prepare($query);

    $stmt->bindValue(':token', $key);

    $stmt->execute();

    echo "<script>location = '../../index.php';</script>";
    exit();

   // header("Location: ../../index.php");