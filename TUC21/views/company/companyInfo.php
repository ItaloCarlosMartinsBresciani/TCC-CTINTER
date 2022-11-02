<?php
    session_start();

    if(!isset($_SESSION['isAuth'])){
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
    <title>Informação - Empresa</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../public/bootstrap/bootstrap.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../../public/css/sidebar.css">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a39639353a.js" crossorigin="anonymous"></script>
   
    <!-- JS -->
    <script type="text/javascript">

        function submit(){
            $('form').append('<input type="hidden"/>');
            $('form').submit();
        };
    </script>
</head>
<body>
  <div id="wrapper" class="p-0">
      <div id="navbar-wrapper">
          <nav class="navbar navbar-inverse">
              <div class="container-fluid">
                  <div class="navbar-header">
                      <a href="companyPage.php" class="navbar-brand" style="font-size: 24px;"><i class="fas fa-arrow-circle-left"></i></a>
                  </div>
              </div>
          </nav>
    </div>

    <?php
      if (isset($_GET['type']) && isset($_GET['id'])) { //o tipo de usuário que quer ver informação e seu id
        $type = cleanString($_GET['type']);

        try {
          $idHex = cleanString($_GET['id']);

          $idDec = decodeId($idHex);
        }
        catch (TypeError) {
            header('Location: companyPage.php');
        }   
      } else {
        header('Location: companyPage.php');
      }
    ?>

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 py-2" id="content">                        
                        <?php
                            if ($type == 'person') {
                              // Edição de Pessoas
                              
                              $query = 'SELECT * FROM person WHERE id_person = :id';

                              $stmt = $conn->prepare($query);

                              $stmt->bindValue(':id', $idDec);

                              $stmt->execute();

                              $return = $stmt->fetch(PDO::FETCH_ASSOC);
                              
                              if($return['valid']) {
                                
                                echo '<h2 class="py-2">Informações - '.$return['name_person'].' 
                                        <a href="companyEdit.php?type=person&id='.$idHex.'"> <!--botão de edição -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                                            </svg>
                                        </a>
                                    </h2>     
                                ';

                                $form = "
                                <div class='row'>
                                      <div class='col-lg-6 col-sm-12'>
                                          <label for='name-person' class='lead fw-normal'>Nome:</label>
                                          <input type='text' id='name-person' class='form-control' name='name-person' value='".$return['name_person']."' disabled><br>
                                          <label for='email-person' class='lead fw-normal'>E-mail:</label>
                                          <input type='email' id='email-person' class='form-control' name='email-person' value='".$return['email_person']."' disabled><br>
                                          <label for='telephone-person' class='lead fw-normal'>Telefone:</label>
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
                                
                                echo $form;
                              }

                            } 
                            else if($type == "intern"){
                                $query = 'SELECT * FROM person WHERE id_person = :id';

                                $stmt = $conn->prepare($query);

                                $stmt->bindValue(':id', $idDec);

                                $stmt->execute();

                                $return = $stmt->fetch(PDO::FETCH_ASSOC);

                                $name_person = $return['name_person'];
                                if($return['valid']) {
                                    
                                    echo '<h2 class="py-2">Informações - '.$return['name_person'].' </h2>     
                                    ';

                                    $form = "
                                    <div class='row'>
                                        <div class='col-lg-6 col-sm-12'>
                                            <label for='name-person' class='lead fw-normal'>Nome:</label>
                                            <input type='text' id='name-person' class='form-control' name='name-person' value='".$return['name_person']."' disabled><br>
                                            <label for='email-person' class='lead fw-normal'>E-mail:</label>
                                            <input type='email' id='email-person' class='form-control' name='email-person' value='".$return['email_person']."' disabled><br>
                                            <label for='telephone-person' class='lead fw-normal'>Telefone:</label>
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
                            
                                    echo $form;

                                }

                                $query = 'SELECT * FROM internship_data WHERE fk_student = :id';
                            
                                $stmt = $conn->prepare($query);

                                $stmt->bindValue(':id', $idDec);

                                $stmt->execute();

                                $return = $stmt->fetch(PDO::FETCH_ASSOC);  
                                
                                if($return)
                                {
                                    if($return['nature_internship_data'] == true)
                                    {
                                        
                                        echo '<h3 class="py-2">Informações - Estágio obrigatório de '.$name_person.' </h3>';
                                    }
                                    else
                                    {
                                        
                                    echo '<h3 class="py-2">Informações - Estágio não obrigatório de '.$name_person.' </h3>';
                                        
                                    }
                                    
                                    if($return['scholarship_internship_data'])
                                    {
                                        $scholarship = "<label for='scholarship-internship-data' class='lead fw-normal'>Possui bolsa?</label>
                                                        <input type='text' id='scholarship-internship-data' class='form-control' name='scholarship-internship-data' value='Sim' disabled><br>
                                                        <label for='scholarship-value-internship-data' class='lead fw-normal'>Valor da bolsa:</label>
                                                        <input type='number' id='scholarship-value-internship-data' class='form-control' name='scholarship-value-internship-data' value='".$return['scholarship_value_internship_data']."' disabled><br>
                                                        ";
                                    }
                                    else{
                                        $scholarship = "<label for='scholarship-internship-data' class='lead fw-normal'>Possui bolsa?</label>
                                                        <input type='text' id='scholarship-internship-data' class='form-control' name='scholarship-internship-data' value='Não' disabled><br>
                                                        ";
                                        $height = 225;
                                    }

                                    $form = "
                                    <div class='row'>
                                        <div class='col-lg-6 col-sm-12'>
                                            <label for='role-internship-data' class='lead fw-normal'>Função:</label>
                                            <input type='text' id='role-internship-data' class='form-control' name='role-internship-data' value='".$return['role_internship_data']."' disabled><br>
                                            <label for='course-internship-data' class='lead fw-normal'>Curso:</label>
                                            <input type='text' id='course-internship-data' class='form-control' name='course-internship-data' value='".$return['course_internship_data']."' disabled><br>
                                            <label for='area-internship-data' class='lead fw-normal'>Área:</label>                                            
                                            <input type='text' id='area-internship-data' class='form-control' name='area-internship-data' value='".$return['area_internship_data']."' disabled><br>
                                            <label for='week-hours-internship-data' class='lead fw-normal'>Horas semanais:</label>
                                            <input type='number' id='week-hours-internship-data' class='form-control' name='week-hours-internship-data' value='".$return['week_hours_internship_data']."' disabled><br>
                                            <label for='start-date-internship-data' class='lead fw-normal'>Data de Início:</label>
                                            <input type='date' id='start-date-internship-data' class='form-control' name='start-date-internship-data' value='".$return['start_date_internship_data']."' disabled><br>
                                            <label for='end-date-internship-data' class='lead fw-normal'>Data de término:</label>
                                            <input type='date' id='end-date-internship-data' class='form-control'  name='end-date-internship-data' value='".$return['end_date_internship_data']."' disabled><br>
                                            <label for='total-hours-internship-data' class='lead fw-normal'>Total de horas:</label>
                                            <input type='number' id='total-hours-internship-data' class='form-control'  name='total-hours-internship-data' value='".$return['total_hours_internship_data']."' disabled><br>
                                        </div>
                                        <div class='col-lg-6 col-sm-12'>
                                            ".$scholarship."
                                            
                                            <label for='description-internship-data' class='lead fw-normal'>Descrição: </label>
                                            <textarea id='description-internship-data' class='form-control' name='description-internship-data'  style='height:130px;' disabled>".$return['description_internship_data']."</textarea><br>
                                            <label for='total-hours-internship-data' class='lead fw-normal'>Total de horas:</label>
                                            <input type='number' id='total-hours-internship-data' class='form-control'  name='total-hours-internship-data' value='".$return['total_hours_internship_data']."' disabled><br>
                                        </div> 
                                    </div> 
                                    ";
                            
                                    echo $form;
                                }                               

                            } else if ($type == 'company') {
                                // Edição de Empresas
    
                                $query = 'SELECT * FROM company WHERE id_company = :id';
    
                                $stmt = $conn->prepare($query);
    
                                $stmt->bindValue(':id', $idDec);
    
                                $stmt->execute();
    
                                $return = $stmt->fetch(PDO::FETCH_ASSOC);
                                
                                if($return['valid']) {
                                    echo '<h2 class="py-2">Informações - '.$return['name_company'].'
                                            <a href="companyEdit.php?type=company&id='.$idHex.'">
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
                                            <label for='name_company' class='lead fw-normal'>Nome:</label>
                                            <input type='text' id='name_company' class='form-control' name='name_company' value='".$return['name_company']."' disabled><br>
                                            
                                            <label for='telephone_company' class='lead fw-normal'>Telefone:</label>
                                            <input type='tel' id='telephone_company' class='form-control' name='telephone_company' value='".$return['telephone_company']."' disabled><br>
                                            
                                            <label for='email_company' class='lead fw-normal'>E-mail:</label>
                                            <input type='text' id='email_company' class='form-control' name='email_company' value='".$return['email_company']."' disabled><br> 
    
                                            <label for='contact_company' class='lead fw-normal'>Contato da empresa:</label>
                                            <input type='text' id='contact_company' class='form-control' name='contact_company' value='".$return['contact_company']."' disabled><br>
                                            
                                            <label for='cnpj_company' class='lead fw-normal'>CNPJ:</label>
                                            <input type='text' id='cnpj_company' class='form-control' name='cnpj_company' value='".$return['cnpj_company']."' disabled><br>
                                            
                                            <label for='branch_line_company' class='lead fw-normal'>Ramal:</label>
                                            <input type='text' id='branch_line_company' class='form-control' name='branch_line_company' value='".$return['branch_line_company']."' disabled><br>
                                        
                                        </div>
                                        <div class='col-lg-6 col-sm-12'>
                                            <label for='cep_company' class='lead fw-normal'>CEP:</label>
                                            <input type='text' id='cep_company' class='form-control' name='cep_company' value='".$return['cep_company']."' disabled><br>
                                            
                                            <label for='address_company' class='lead fw-normal'>Endereço:</label>
                                            <input type='text' id='address_company' class='form-control' name='address_company' value='".$return['address_company']."' disabled><br>
                                            
                                            <label for='number_company' class='lead fw-normal'>Número:</label> 
                                            <input type='text' id='number_company' class='form-control' name='number_company' value='".$return['number_company']."' disabled><br>
                                        
                                            <label for='district_company' class='lead fw-normal'>Bairro:</label>
                                            <input type='text' id='district_company' class='form-control' name='district_company' value='".$return['district_company']."' disabled ><br>
                                            
                                            <label for='city_company' class='lead fw-normal'>Cidade:</label>
                                            <input type='text' id='city_company' class='form-control' name='city_company' value='".$return['city_company']."' disabled ><br>
                                            
                                            <label for='state_company' class='lead fw-normal'>Estado:</label>
                                            <select  class='form-control' name='state_company' id='state_company' disabled>
                                                <option selected value='".$return['state_company']."'>".$return['state_company']."</option>
                                            </select> <br>
                                            
                                        </div>
                                        </div>
                                    ";   
    
                                    echo $form;     
                                  }
                            }
                            else {

                                header('Location: companyPage.php');
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

    <script src="../../js/company.js"></script>
</body>
</html>