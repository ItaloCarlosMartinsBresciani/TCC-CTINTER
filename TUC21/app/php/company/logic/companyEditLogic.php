<?php

    session_start();

     
    require_once('../../functions.php');
    require_once('../../../db/connect.php');

    if(!isset($_SESSION['isAuth'])){
        header("Location: ../../../../index.php ");
        exit();
    }

    if (isset($_GET['id'])) {    
        try {
          $id = cleanString($_GET['id']);
    
          $idDec = decodeId($id);
        }
        catch (Exception $e) {
          header('Location: ../../../../views/company/companyPage.php');
        }   
    } else {
        header('Location: ../../../../views/company/companyPage.php');
    }
    
    foreach($_POST as $key => $value) {
        $value = cleanString($value);
      
        if(!empty($value)) {
          $_POST[$key] = $value;
        }
        else {
          header('Location: ../../../../views/company/companyEdit.php?type='.$type.'&id='.codeId($idDec));
          exit();
        }
      }
     
    
    function verifyCNPJ($cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
        
        // Valida tamanho
        if (strlen($cnpj) != 14)
            return false;

        // Verifica se todos os digitos são iguais
        if (preg_match('/(\d)\1{13}/', $cnpj))
            return false;	

        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
        {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
            return false;

        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
        {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }
    try
    {
      if (verifyCNPJ($_POST['cnpj_company']))
      {
        $query = 'UPDATE company SET name_company = :name_company, cnpj_company = :cnpj_company, address_company = :address_company, 
        district_company = :district_company, cep_company = :cep_company, 
        city_company = :city_company, state_company = :state_company, telephone_company = :telephone_company, 
        email_company = :email_company , number_company = :number_company,
        branch_line_company = :branch_line_company, last_edition_date = DEFAULT
        WHERE id_company = :id_company';
        
        $stmt = $conn->prepare($query);

        $stmt->bindValue(':id_company', $idDec);
        $stmt->bindValue(':name_company', $_POST['name_company']);
        $stmt->bindValue(':cnpj_company', $_POST['cnpj_company']);
        $stmt->bindValue(':email_company', $_POST['email_company']);
        $stmt->bindValue(':address_company', $_POST['address_company']);
        $stmt->bindValue(':number_company', $_POST['number_company']);
        $stmt->bindValue(':district_company', $_POST['district_company']);
        $stmt->bindValue(':city_company', $_POST['city_company']);
        $stmt->bindValue(':state_company', $_POST['state_company']);
        //$stmt->bindValue(':state_registration_company', $_POST['state_registration_company']);
        $stmt->bindValue(':cep_company', $_POST['cep_company']);
        $stmt->bindValue(':telephone_company', $_POST['telephone_company']);
        $stmt->bindValue(':branch_line_company', $_POST['branch_line_company']);
        //$stmt->bindValue(':legal_representative_company', $_POST['legal_representative_company']);
        //$stmt->bindValue(':activity_branch_company', $_POST['activity_branch_company']);
        //$stmt->bindValue(':corporate_name_company', $_POST['corporate_name_company']);
        //$stmt->bindValue(':home_page_company', $_POST['home_page_company']);
        //$stmt->bindValue(':mailbox_company', $_POST['mailbox_company']);
        
        $stmt->execute();
      
        $_SESSION['feedback'] = 'successEdit';
        $_SESSION['btn'] = 2;
  
        $stmt->execute();

        $query = "UPDATE change_data_companies SET allowed = FALSE, edited = TRUE, last_edition_date = DEFAULT WHERE fk_id = $idDec";
        $stmt = $conn->prepare($query);
        $stmt->execute();
      
        $_SESSION['feedback'] = 'successEdit';
        $_SESSION['btn'] = 1;
        
        header('Location: ../../../../views/company/companyPage.php');
        exit();
        }
        else
        {
        $idHex = codeId($idDec);
        echo "<script>alert('CNPJ inválido! Redigite.');</script>";
        echo "<script>window.location.replace('../../../../views/company/companyEdit.php?type=$type&id=$idHex')</script>";
        }
      
    }
    catch (Exception $ex)
    {
        $idHex = codeId($idDec);
        $_SESSION['feedback'] = 'errorEdit';
        $_SESSION['btn'] = 1;
        echo "<script>alert('Erro ao tentar alterar os dados!');</script>";
        exit();
        echo "<script>window.location.replace('../../../../views/company/companyEdit.php?type=$type&id=$idHex')</script>";
    }