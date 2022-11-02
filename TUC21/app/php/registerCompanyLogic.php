<?php

use Google\Service\CloudTrace\Span;

session_start();
    
    require_once('functions.php');
    require_once('../db/connect.php');

    $key = cleanString($_GET['key']);

    $inf[] = "name-company";
    $inf[] = "email-company";
    $inf[] = "telephone-company";
    $inf[] = "telephone2-company";
    $inf[] = "section-company";
    $inf[] = "function-company";
    $inf[] = "cnpj-company";
    $inf[] = "branch-line-company";
    $inf[] = "cep-company";            
    $inf[] = "address-company";
    $inf[] = "number-company";
    $inf[] = "district-company";
    $inf[] = "city-company";
    $inf[] = "state-company";
    $inf[] = "state-registration-company";
    $inf[] = "legal-representative-company";
    $inf[] = "activity-branch-company";
    $inf[] = "corporate-name-company";
    $inf[] = "home-page-company";


    foreach($inf as $value) {            
        $_SESSION[$value] = cleanString($_SESSION[$value]); 
        
        if(empty($_SESSION[$value]) && $value != "mailbox-university") { //se depois de limpar não há informação dentro
            echo "<script>alert('Todos os campos devem ser preenchidos corretamente! Faça o cadastro novamente!');</script>";
            echo "<script>location = '../../views/putCompanyInformation.php?key=".$_GET['key']."&error=1';</script>";
            exit();
        }
    } 

    try
    {
        // Cadastro da Empresa
        $query = "INSERT INTO company VALUES (DEFAULT, :cnpj_company, :email_company, :name_company, :address_company, :number_company, :district_company, :city_company, 
        :state_company, :state_registration_company, :cep_company, :telephone_company, :telephone2_company, :section_company, :function_company, :branch_line_company, :legal_representative_company,
        :activity_branch_company, :corporate_name_company, :home_page_company, :mailbox_company, DEFAULT, :who_edited, DEFAULT, DEFAULT, NULL, TRUE)";

        $stmt = $conn->prepare($query);

    
        $stmt->bindValue(':cnpj_company', $_SESSION['cnpj-company']);        
        $stmt->bindValue(':email_company', $_SESSION['email-company']);
        $stmt->bindValue(':name_company', $_SESSION['name-company']);
        $stmt->bindValue(':address_company', $_SESSION['address-company']);        
        $stmt->bindValue(':number_company', $_SESSION['number-company']);
        $stmt->bindValue(':district_company', $_SESSION['district-company']);
        $stmt->bindValue(':city_company', $_SESSION['city-company']);        
        $stmt->bindValue(':state_company', $_SESSION['state-company']);
        $stmt->bindValue(':cep_company', $_SESSION['cep-company']);
        $stmt->bindValue(':telephone_company', $_SESSION['telephone-company']);
        $stmt->bindValue(':telephone2_company', $_SESSION['telephone2-company']);     
        $stmt->bindValue(':section_company', $_SESSION['section-company']);   
        $stmt->bindValue(':function_company', $_SESSION['function-company']); 
        $stmt->bindValue(':branch_line_company', $_SESSION['branch-line-company']);
        $stmt->bindValue(':state_registration_company', $_SESSION['state-registration-company']);
        $stmt->bindValue(':legal_representative_company', $_SESSION['legal-representative-company']);
       // $stmt->bindValue(':role_legal_representative_company', $_SESSION['role-legal-representative-company']);
        $stmt->bindValue(':activity_branch_company', $_SESSION['activity-branch-company']);
        $stmt->bindValue(':corporate_name_company', $_SESSION['corporate-name-company']);
        $stmt->bindValue(':home_page_company', $_SESSION['home-page-company']);
        $stmt->bindValue(':mailbox_company', $_SESSION['mailbox-company']);
        $stmt->bindValue(':who_edited', $_SESSION["name-company"]);
        

        $stmt->execute();

        //
        $query = "SELECT id_company FROM company WHERE email_company = :email_company";

        $stmt = $conn->prepare($query);

        $stmt->bindValue(':email_company', $_SESSION['email-company']);

        $stmt->execute();
                
        $return = $stmt->fetch(PDO::FETCH_ASSOC); 
        
        $idCompany = $return["id_company"];

        // Cadastro de empresa na tabela de edição de informações (change_data)
        $query = "INSERT INTO change_data_companies VALUES (DEFAULT, FALSE, NULL, DEFAULT, DEFAULT, ".$idCompany.")";

        $stmt = $conn->prepare($query);

        $stmt->execute();


        echo "
            <script>
                alert('Dados do(a) diretor(a) e da instituição cadastrados com sucesso!');
            </script>
        ";
    }
    catch (Exception $e)
    {
        //enviar email com o erro (".$e->getMessage().")*/
        echo "<script>alert('Erro no Cadastro!');</script>";
        echo $e->getMessage();
        
        echo "<script>location = '../../views/putCompanyInformation.php?key=".$_GET['key']."';</script>";
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