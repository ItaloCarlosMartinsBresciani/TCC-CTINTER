<?php
    session_start();

    if(!isset($_SESSION['isAuth']) || $_SESSION['idUser'] != -1){
        echo "<script>alert('Usuário não reconhecido.');</script>";
        header("Location: ../../index.php ");
        exit();
    }
    
    

    require_once('../../app/db/connect.php');
    require_once('../../app/php/functions.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informação - Admin</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../public/bootstrap/bootstrap.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../../public/css/sidebar.css">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a39639353a.js" crossorigin="anonymous"></script>
</head>
<body>
  <div id="wrapper" class="p-0">
      <div id="navbar-wrapper">
          <nav class="navbar navbar-inverse">
              <div class="container-fluid">
                  <div class="navbar-header">
                      <a href="adminPage.php" class="navbar-brand" style="font-size: 24px;"><i class="fas fa-arrow-circle-left"></i></a>
                  </div>
              </div>
          </nav>
    </div>

    <?php
      if (isset($_GET['type']) && isset($_GET['id'])) {
        $type = cleanString($_GET['type']);

        try {
          $idHex = cleanString($_GET['id']);

          $idDec = decodeId($idHex);
        }
        catch (TypeError) {
          header('Location: adminPage.php');
        }   
      } else {
      header('Location: adminPage.php');
      }
    ?>

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 py-2" id="content">                        
                        <?php
                            if ($type == 'person') 
                            {
                              // Edição de Pessoas
                              
                           
                              $query = "SELECT * FROM person p, company_employee c WHERE p.id_person = :id AND c.fk_id = :id";
                              $stmt = $conn->prepare($query);

                              $stmt->bindValue(':id', $idDec);

                              $stmt->execute();

                              $return = $stmt->fetch(PDO::FETCH_ASSOC);
                              if ($return){
                                $pass = 1;
                              }
                              else{
                                $query = "SELECT * FROM person p, university_employee u WHERE p.id_person = :id AND u.fk_id = :id";

                                $stmt = $conn->prepare($query);

                                $stmt->bindValue(':id', $idDec);
  
                                $stmt->execute();
  
                                $return = $stmt->fetch(PDO::FETCH_ASSOC);

                              }
                             


                
                              if($return['valid'])
                              {                                    
                                    echo '<h2 class="py-2">Informações - '.$return['name_person'].' 
                                            <a href="adminEdit.php?type=person&id='.$idHex.'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                                                </svg>
                                            </a>
                                        </h2>     
                                    ';

                                                             
                                if($return['business_sector_professor']) 
                                {
                                    
                                        
                                    echo "
                                    <div class='row'>
                                        <div class='col-lg-6 col-sm-12'>
                                            <label for='name-person' class='lead fw-normal'>Nome:</label>
                                            <input type='text' id='name-person' class='form-control' name='name-person' value='".$return['name_person']."' disabled><br>
                                            <label for='email-person' class='lead fw-normal'>E-mail:</label>
                                            <input type='email' id='email-person' class='form-control' name='email-person' value='".$return['email_person']."' disabled><br>
                                            <label for='telephone-person' class='lead fw-normal'>Tefelone:</label>
                                            <input type='tel' id='telephone-person' class='form-control' name='telephone-person' value='".$return['telephone_person']."' disabled><br>
                                            <label for='business-sector-professor' class='lead fw-normal'>Curso atuante:</label>
                                            <input type='text' id='business-sector-professor' class='form-control' name='business-sector-professor' value='".$return['business_sector_professor']."' disabled><br>
                                        </div>
                                        <div class='col-lg-6 col-sm-12'>
                                            <label for='cpf-person' class='lead fw-normal'>CPF:</label>
                                            <input type='text' id=''cpf-person' class='form-control' name='cpf-person' value='".$return['cpf_person']."' disabled><br>
                                            <label for='rg-person' class='lead fw-normal'>RG:</label>
                                            <input type='text' id='rg-person' class='form-control' name='rg-person' value='".$return['rg_person']."' disabled><br>
                                            <label for='treatment-person' class='lead fw-normal'>Tratamento:</label>
                                            <input type='text' id='treatment-person' class='form-control' placeholder='Ex: Doutor' name='treatment-person' value='".$return['treatment_person']."' disabled><br>
                                        </div> 
                                        </div> 
                                    ";
                                }
                                else if ($pass = 1)
                                {
                                    $query = "SELECT * FROM person WHERE id_person = :id";
                                    $stmt = $conn->prepare($query);
      
                                    $stmt->bindValue(':id', $idDec);
      
                                    $stmt->execute();
      
                                    $return = $stmt->fetch(PDO::FETCH_ASSOC);
                                    echo "
                                        <div class='row'>
                                            <div class='col-lg-6 col-sm-12'>
                                                <label for='name-person' class='lead fw-normal'>Nome:</label>
                                                <input type='text' id='name-person' class='form-control' name='name-person' value='".$return['name_person']."' disabled><br>
                                                <label for='email-person' class='lead fw-normal'>E-mail:</label>
                                                <input type='email' id='email-person' class='form-control' name='email-person' value='".$return['email_person']."' disabled><br>
                                                <label for='telephone-person' class='lead fw-normal'>Tefelone:</label>
                                                <input type='tel' id='telephone-person' class='form-control' name='telephone-person' value='".$return['telephone_person']."' disabled><br>
                                            </div>
                                            <div class='col-lg-6 col-sm-12'>
                                                <label for='cpf-person' class='lead fw-normal'>CPF:</label>
                                                <input type='text' id=''cpf-person' class='form-control' name='cpf-person' value='".$return['cpf_person']."' disabled><br>
                                                <label for='rg-person' class='lead fw-normal'>RG:</label>
                                                <input type='text' id='rg-person' class='form-control' name='rg-person' value='".$return['rg_person']."' disabled><br>
                                                <label for='treatment-person' class='lead fw-normal'>Tratamento:</label>
                                                <input type='text' id='treatment-person' class='form-control' placeholder='Ex: Doutor' name='treatment-person' value='".$return['treatment_person']."' disabled><br>
                                            </div> 
                                            </div> 
                                        ";
                                }
                            }
                            
                             $query = "SELECT * FROM person p, student u WHERE p.id_person = :id AND u.fk_id = :id";

                              $stmt = $conn->prepare($query);

                              $stmt->bindValue(':id', $idDec);

                              $stmt->execute();

                              $return = $stmt->fetch(PDO::FETCH_ASSOC);
                              
                
                              if($return['valid'])
                              {                                    
                                    echo '<h2 class="py-2">Informações - '.$return['name_person'].' 
                                            <a href="adminEdit.php?type=person&id='.$idHex.'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                                                </svg>
                                            </a>
                                        </h2>     
                                    ';

                                                             
                                if($return['business_sector_student']) 
                                {
                                    echo "
                                <div class='row'>
                                      <div class='col-lg-6 col-sm-12'>
                                          <label for='name-person' class='lead fw-normal'>Nome:</label>
                                          <input type='text' id='name-person' class='form-control' name='name-person' value='".$return['name_person']."' disabled><br>
                                          <label for='email-person' class='lead fw-normal'>E-mail:</label>
                                          <input type='email' id='email-person' class='form-control' name='email-person' value='".$return['email_person']."' disabled><br>
                                          <label for='telephone-person' class='lead fw-normal'>Tefelone:</label>
                                          <input type='tel' id='telephone-person' class='form-control' name='telephone-person' value='".$return['telephone_person']."' disabled><br>
                                          <label for='business-sector-student' class='lead fw-normal'>Curso atuante:</label>
                                          <input type='text' id='business-sector-student' class='form-control' name='business-sector-student' value='".$return['business_sector_student']."' disabled><br>
                                      </div>
                                      <div class='col-lg-6 col-sm-12'>
                                          <label for='cpf-person' class='lead fw-normal'>CPF:</label>
                                          <input type='text' id=''cpf-person' class='form-control' name='cpf-person' value='".$return['cpf_person']."' disabled><br>
                                          <label for='rg-person' class='lead fw-normal'>RG:</label>
                                          <input type='text' id='rg-person' class='form-control' name='rg-person' value='".$return['rg_person']."' disabled><br>
                                          <label for='treatment-person' class='lead fw-normal'>Tratamento:</label>
                                          <input type='text' id='treatment-person' class='form-control' placeholder='Ex: Doutor' name='treatment-person' value='".$return['treatment_person']."' disabled><br>
                                      </div> 
                                      </div> 
                                ";
                                }
                              }

                              

                            } 
                            else if ($type == 'university') {
                                // Edição de Instituições de ensino
                              
                                $query = 'SELECT * FROM university WHERE id_university = :id';

                                $stmt = $conn->prepare($query);

                                $stmt->bindValue(':id', $idDec);

                                $stmt->execute();

                                $return = $stmt->fetch(PDO::FETCH_ASSOC);
                                
                                if($return['valid']) 
                                {
                                    echo '<h2 class="py-2">Informações - '.$return['name_university'].'
                                            <a href="adminEdit.php?type=university&id='.$idHex.'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                                                </svg>
                                            </a>
                                        </h2>
                                    ';
                                        
                                    $form = 
                                    "
                                        <div class='row'>
                                        <div class='col-lg-6 col-sm-12'>
                                            <label for='cnpj_university' class='lead fw-normal'>CNPJ:</label>
                                            <input type='text' id='cnpj_university' class='form-control' name='cnpj_university' value='".$return['cnpj_university']."' disabled><br>
                                            <label for='name_university' class='lead fw-normal'>Nome:</label>
                                            
                                            <input type='text' id='name_university' class='form-control' name='name_university' value='".$return['name_university']."' disabled><br>
                                            
                                            <label for='state_registration_university' class='lead fw-normal'>Inscrição estadual:</label>
                                            <input type='text' id='state_registration_university' class='form-control' name='state_registration_university' value='".$return['state_registration_university']."' disabled><br>
                                            <label for='corporate_name_university' class='lead fw-normal'>Razão social:</label>
                                            <input type='text' id='corporate_name_university' class='form-control' name='corporate_name_university' value='".$return['corporate_name_university']."' disabled><br>
                                            <label for='legal_representative_university' class='lead fw-normal'>Representante legal:</label>
                                            <input type='text' id='legal_representative_university' class='form-control' name='legal_representative_university' value='".$return['legal_representative_university']."' disabled><br>
                                            <label for='activity_branch_university' class='lead fw-normal'>Ramo de atividade:</label>
                                            <input type='text' id='activity_branch_university' class='form-control' name='activity_branch_university' value='".$return['activity_branch_university']."' disabled><br>
                                            <label for='address_university' class='lead fw-normal'>Endereço:</label>
                                            <input type='text' id='address_university' class='form-control' name='address_university' value='".$return['address_university']."' disabled><br>
                                            <label for='home_page_university' class='lead fw-normal'>Homepage:</label>
                                            <input type='text' id='home_page_university' class='form-control' name='home_page_university' value='".$return['home_page_university']."' disabled><br>
                                        </div>
                                        <div class='col-lg-6 col-sm-12'>
                                            <label for='district_university' class='lead fw-normal'>Bairro:</label>
                                            <input type='text' id='district_university' class='form-control' name='district_university' value='".$return['district_university']."' disabled ><br>
                                            <label for='cep_university' class='lead fw-normal'>CEP:</label>
                                            <input type='text' id='cep_university' class='form-control' name='cep_university' value='".$return['cep_university']."' disabled><br>
                                            <label for='mailbox_university' class='lead fw-normal'>Caixa postal:</label>
                                            <input type='text' id='mailbox_university' class='form-control' name='mailbox_university' value='".$return['mailbox_university']."' disabled><br>
                                            <label for='city_university' class='lead fw-normal'>Cidade:</label>
                                            <input type='text' id='city_university' class='form-control' name='city_university' value='".$return['city_university']."' disabled ><br>
                                            <label for='state_university' class='lead fw-normal'>Estado:</label>
                                            <select  class='form-control' name='state_university' id='state_university' disabled>
                                                <option selected value='".$return['state_university']."'>".$return['state_university']."</option>
                                            </select> <br>
                                            <label for='telephone_university' class='lead fw-normal'>Telefone:</label>
                                            <input type='tel' id='telephone_university' class='form-control' name='telephone_university' value='".$return['telephone_university']."' disabled><br>
                                            <label for='email_university' class='lead fw-normal'>E-mail:</label>
                                            <input type='text' id='email_university' class='form-control' name='email_university' value='".$return['email_university']."' disabled><br> 

                                        </div>
                                        </div>
                                    ";   

                                    echo $form;     
                                } else {
                                    header('Location: adminPage.php');
                                }
                                                  
                            } else if ($type == 'company') {
                              // Edição de Empresas

                              $query = 'SELECT * FROM company WHERE id_company = :id';

                              $stmt = $conn->prepare($query);

                              $stmt->bindValue(':id', $idDec);

                              $stmt->execute();

                              $return = $stmt->fetch(PDO::FETCH_ASSOC);
                              
                              if($return['valid']) 
                              {
                                echo '<h2 class="py-2">Informações - '.$return['name_company'].'
                                        <a href="adminEdit.php?type=company&id='.$idHex.'">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                                            </svg>
                                        </a>
                                    </h2>
                                ';
                                    
                                $form = 
                                "
                                <div class='row'>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='corporate-name-company' class='lead fw-normal'>Nome corporativo:</label>
                                    <input type='text' id='corporate-name-company' class='form-control'  name='corporate-name-company' value='".$return['corporate_name_company']."' disabled><br>
                                    
                                    <label for='cnpj-company' class='lead fw-normal'>CNPJ:</label>
                                    <input type='text' id='cnpj-company' class='form-control'  name='cnpj-company' value='".$return['cnpj_company']."' disabled><br>
                                    
                                    <label for='legal-representative-company' class='lead fw-normal'>Representante legal:</label>  
                                    <input type='text' id='legal-representative-company' class='form-control' name='legal-representative-company' value='".$return['legal_representative_company']."' disabled><br>
                                    
                                    <label for='email-company' class='lead fw-normal'>E-mail:</label>
                                    <input type='email' id='email-company' maxlenght='50' class='form-control' name='email-company' value='".$return['email_company']."' disabled><br>

                                    <label for='telephone-company' class='lead fw-normal'>Telefone:</label>  
                                    <input type='text' id='telephone-company' class='form-control' name='telephone-company' value='".$return['telephone_company']."' disabled><br>
                                    
                                    <label for='branch-line-company' class='lead fw-normal'>Ramal:</label>
                                    <input type='text' id='branch-line-company' class='form-control'  name='branch-line-company' value='".$return['branch_line_company']."' disabled><br>
                                    
                                    <label for='activity-branch-company' class='lead fw-normal'>Ramo de atuação:</label>  
                                    <input type='text' id='activity-branch-company' class='form-control' name='activity-branch-company' value='".$return['activity_branch_company']."' disabled><br>

                                    <label for='state-registration-company' class='lead fw-normal'>Inscrição estadual:</label>
                                    <input type='text' id='state-registration-company' class='form-control'  name='state-registration-company' value='".$return['state_registration_company']."' disabled><br>
                                   
                                   
                                </div>
                                <div class='col-lg-6 col-sm-12'>

                                    <label for='cep-company' class='lead fw-normal'>CEP:</label>  
                                    <input type='text' id='cep-company' class='form-control' name='cep-company' value='".$return['cep_company']."'  onblur='searchCep(this.value);' disabled><br>
                                    
                                    <label for='address-company' class='lead fw-normal'>Endereço:</label>  
                                    <input type='text' id='address-company' class='form-control' name='address-company' value='".$return['address_company']."' disabled><br>

                                    <label for='number-company' class='lead fw-normal'>Número:</label>  
                                    <input type='text' id='number-company' class='form-control' name='number-company' value='".$return['number_company']."' disabled><br>
                                
                                    
                                    <label for='district-company' class='lead fw-normal'>Bairro:</label>
                                    <input type='text' id='district-company' class='form-control'  name='district-company' value='".$return['district_company']."' disabled><br>
                        
                                    <label for='city-company' class='lead fw-normal'>Cidade:</label>
                                    <input type='text' id='city-company' class='form-control'  name='city-company' value='".$return['city_company']."' disabled><br>

                                    
                                    <label for='state-company' class='lead fw-normal'>Estado:</label>
                                    <input type='text' id='state-company' class='form-control'  name='state-company' value='".$return['state_company']."' disabled><br>

                                    <label for='home-page-company' class='lead fw-normal'>Home page:</label>
                                    <input type='text' id='home-page-company' class='form-control'  name='home-page-company' value='".$return['home_page_company']."' disabled><br>
                            
                                  
                                
                                </div>
                                
                                 
                                
                            </div>
                                ";   

                                echo $form;     
                            }
                            } else {
                              header('Location: adminPage.php');
                            } 
                        ?>

                </div>
            </div>
        </div> 
    </div>
    <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Bootstrap -->
    <script src="../../public/bootstrap/bootstrap.min.js"></script>

    <script src="../../js/admin.js"></script>
</body>
</html>