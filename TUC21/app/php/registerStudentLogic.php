<?php

use Google\Service\CloudTrace\Span;

session_start();
    
    require_once('functions.php');
    require_once('../db/connect.php');

    $key = cleanString($_GET['key']);

    $id_coordinator = decodeId(cleanString($_GET['id_coordinator']));

    $inf[] = "name-student";
    $inf[] = "email-student";
    $inf[] = "birthday-person";
    $inf[] = "telephone-student";
    $inf[] = "cpf-student";
    $inf[] = "rg-student";
    $inf[] = "treatment-student";
    $inf[] = "ra-student";
    $inf[] = "course-code-student";
    $inf[] = "year-entry-student";
    $inf[] = "business-sector-student";
    $inf[] = "period-student";
    $inf[] = "complement-student";
    $inf[] = "address-student";
    $inf[] = "number-student";
    $inf[] = "district-student";
    $inf[] = "city-student";
    $inf[] = "cep-student";
  

    foreach($inf as $value) {  //yes daddy -> sagrado
         
        $_SESSION[$value] = cleanString($_SESSION[$value]);   
        
        
        if(empty($_SESSION[$value]) && $value != "complement-student") { //se depois de limpar não há informação dentro
            echo "<script>alert('Os dados digitados não são válidos! Tente o cadastro novamente!');</script>";
            echo "<script>location = '../../views/putStudentInformation.php?key=".$_GET['key']."&id_coordinator=".$id_coordinator."';</script>";
            exit();
        }
    } 

    // Cadastro do Aluno
    try
    {
        $query = 'INSERT INTO person VALUES(DEFAULT, :cpf_person, :name_person, :email_person, :telephone_person, :birthday_person, :rg_person, DEFAULT, DEFAULT, :treatment_person, 1, DEFAULT, :who_edited, :who_invited, DEFAULT, TRUE);';

        $stmt = $conn->prepare($query);

        $stmt->bindValue(':cpf_person', $_SESSION["cpf-student"]);
        $stmt->bindValue(':name_person', $_SESSION["name-student"]);
        $stmt->bindValue(':email_person', $_SESSION["email-student"]);
        $stmt->bindValue(':telephone_person', $_SESSION["telephone-student"]);
        $stmt->bindValue(':rg_person', $_SESSION["rg-student"]);
        $stmt->bindValue(':treatment_person', $_SESSION["treatment-student"]);
        $stmt->bindValue(':who_edited', $_SESSION["name-student"]);
        $stmt->bindValue(':birthday_person', $_SESSION["birthday-person"]);
        $stmt->bindValue(':who_invited', "Professor");

        $stmt->execute();

        //Obtendo id estudante
        $query = 'SELECT id_person FROM person WHERE email_person = :email';

        $stmt = $conn->prepare($query);

        $stmt->bindValue(':email', $_SESSION["email-student"]);

        $stmt->execute();

        $return = $stmt->fetch(PDO::FETCH_ASSOC); 
        
        $idStudent = $return['id_person'];
        
        
        // Obtendo id Universidade
        $query = 'SELECT fk_university FROM university_employee WHERE fk_id = '.$id_coordinator.'';

        $stmt = $conn->prepare($query);

        $stmt->execute();

        $return = $stmt->fetch(PDO::FETCH_ASSOC); 
        
        $idUniversity = $return['fk_university']; 


    
        
        // Cadastro de estudante na tabela de estudantes da universidade
        $query = 'INSERT INTO student VALUES('.$idStudent.', :ra_student, :business_sector_student, :period_student, DEFAULT, :address_student, :number_student, :district_student, :city_student, :cep_student, :course_code_student, null, null, null, :year_entry_student, null, null, '.$idUniversity.', '.$id_coordinator.', :complement_student, null, null, null, null, null, null, null, null, null, null, null, null);';

        $stmt = $conn->prepare($query);

        $stmt->bindValue(':ra_student', $_SESSION["ra-student"]);
        $stmt->bindValue(':course_code_student', $_SESSION["course-code-student"]);
        $stmt->bindValue(':year_entry_student', $_SESSION["year-entry-student"]);
        $stmt->bindValue(':business_sector_student', $_SESSION["business-sector-student"]);
        $stmt->bindValue(':period_student', $_SESSION["period-student"]);
        $stmt->bindValue(':address_student', $_SESSION["address-student"]);
        $stmt->bindValue(':number_student', $_SESSION["number-student"]);
        $stmt->bindValue(':district_student', $_SESSION["district-student"]);
        $stmt->bindValue(':city_student', $_SESSION["city-student"]);
        $stmt->bindValue(':cep_student', $_SESSION["cep-student"]);
        $stmt->bindValue(':complement_student', $_SESSION["complement-student"]);
       

        $stmt->execute();
        
        // Cadastro de estudante na tabela de edição
        $query = "INSERT INTO change_data VALUES (DEFAULT, FALSE, NULL, DEFAULT, DEFAULT, ".$idStudent.")";

        $stmt = $conn->prepare($query);

        $stmt->execute();

        echo "
            <script>
                alert('Dados do Estudante cadastrados com sucesso!');
            </script>
        ";
       

    }
    catch (Exception $e)
    {        
        //enviar email com o erro (".$e->getMessage().")*/
        echo "<script>alert('".$e->getMessage()."');</script>";
        echo $e->getMessage();
        echo "<script>alert('Erro no Cadastro!');</script>";
        exit();

        $query = 'SELECT id_person FROM person WHERE cpf_person = :cpf_person;';
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':cpf_person', $_SESSION["cpf-student"]);
        $stmt->execute();        
        $return = $stmt->fetch(PDO::FETCH_ASSOC); 
        $id = $return['id_person'];
        
        $query = 'DELETE FROM change_data WHERE fk_id = :id_student;';
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':id_student', $id);
        $stmt->execute();
        
        $query = 'DELETE FROM student WHERE  fk_id = :id_student;';
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':id_student', $id);
        $stmt->execute();
        
        $query = 'DELETE FROM person WHERE id_person = :id_student;';
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':id_student', $id);
        $stmt->execute();

        echo "<script>location = '../../views/putStudentInformation.php?key=".$_GET['key']."&id_coordinator=".codeId($id_coordinator)."';</script>";
        $_SESSION["section"] = 6;
        exit();
    }
   

    // Exclusão do Token
    $query = 'DELETE FROM tokens WHERE token = :token';

    $stmt = $conn->prepare($query);

    $stmt->bindValue(':token', $key);

    $stmt->execute();

    echo "<script>location = '../../index.php';</script>";
    exit();