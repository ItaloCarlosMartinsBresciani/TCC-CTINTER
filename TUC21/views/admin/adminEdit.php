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
    <title>Edição - Admin</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../public/bootstrap/bootstrap.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../../public/css/sidebar.css">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a39639353a.js" crossorigin="anonymous"></script>

    <!-- JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js%22%3E"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js" 
        integrity="sha256-yE5LLp5HSQ/z+hJeCqkz9hdjNkk1jaiGG0tDCraumnA=" 
        crossorigin="anonymous">
    </script>

    <script>
    /*Máscaras - inputs */
    jQuery(function($){
        $("#telephone_person").mask("(00) 00000-0000");
        $("#rg_person").mask("00.000.000-0");
        $("#cpf_person").mask("000.000.000-00");
        $("#cnpj_university").mask("00.000.000/0000-00");
        $("#state_registration_university").mask("00.000.0000-0");
        $("#telephone_university").mask("(00) 00000-0000");
        $("#mailbox_university").mask("00000-000");
        $("#cep_university").mask("00000-000");
        $("#cnpj-company").mask("00.000.000/0000-00");
        $("#telephone-company").mask("(00) 00000-0000");
        $("#cep-company").mask("00000-000");
        $("#branch-line-company").mask("0000"); 
    });

    function cleanFormCep(){
      document.getElementById('cep_university').value=("");
      document.getElementById('address_university').value=("");
      document.getElementById('district_university').value=("");
      document.getElementById('city_university').value=("");
      document.getElementById('state_university').value=("");
    };

    function cleanFormCepCompany(){
      document.getElementById('cep-company').value=("");
      document.getElementById('address-company').value=("");
      document.getElementById('district-company').value=("");
      document.getElementById('city-company').value=("");
      document.getElementById('state-company').value=("");
    };

    function callbackCep(conteudo) {
        if (!("erro" in conteudo)) {
            document.getElementById('address_university').value=(conteudo.logradouro);
            document.getElementById('district_university').value=(conteudo.bairro);
            document.getElementById('city_university').value=(conteudo.localidade);
            document.getElementById('state_university').value=(conteudo.uf);
        }
        else {
            cleanFormCep();
            alert("CEP não encontrado.");
        }
    };

    function searchCep(valor) {
        
        var cep = valor.replace(/\D/g, '');
        if (cep != "") {
            var validatecep = /^[0-9]{8}$/;
            if(validatecep.test(cep)) {
                
                document.getElementById('address_university').value="...";
                document.getElementById('district_university').value="...";
                document.getElementById('city_university').value="...";
                document.getElementById('state_university').value="...";
                var script = document.createElement('script');
                
                script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=callbackCep';
                document.body.appendChild(script);
            }
            else {
                cleanFormCep();
                alert("CEP inválido");
            }
        }
        else {
            cleanFormCep();
        }
    };

    function callbackCepCompany(conteudo) {
        if (!("erro" in conteudo)) {
            document.getElementById('address-company').value=(conteudo.logradouro);
            document.getElementById('district-company').value=(conteudo.bairro);
            document.getElementById('city-company').value=(conteudo.localidade);
            document.getElementById('state-company').value=(conteudo.uf);
        }
        else {
            cleanFormCepCompany();
            alert("CEP não encontrado.");
        }
    };

    function searchCepCompany(valor) {
        
        var cep = valor.replace(/\D/g, '');
        if (cep != "") {
            var validatecep = /^[0-9]{8}$/;
            if(validatecep.test(cep)) {
                document.getElementById('address-company').value="...";
                document.getElementById('district-company').value="...";
                document.getElementById('city-company').value="...";
                document.getElementById('state-company').value="...";
                var script = document.createElement('script');
                
                script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=callbackCepCompany';
                document.body.appendChild(script);
            }
            else {
                cleanFormCepCompany();
                alert("CEP inválido");
            }
        }
        else {
            cleanFormCepCompany();
        }
    };

    </script>

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
          $id = cleanString($_GET['id']);

          $idDec = decodeId($id);
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
                    <form action="../../app/php/admin/logic/adminEditLogic.php?<?php echo "type=$type&id=$id"?>" method="POST">
                        
                        <?php
                            if ($type == 'person') 
                            {
                              // Edição de Pessoas
                              
                              $query = 'SELECT * FROM person WHERE id_person = :id';

                              $stmt = $conn->prepare($query);

                              $stmt->bindValue(':id', $idDec);

                              $stmt->execute();

                              $return = $stmt->fetch(PDO::FETCH_ASSOC);
                              
                              if($return['valid']) {
                                
                                echo '<h2 class="py-2">Edição - '.$return['name_person'].'</h2>';
                                
                                $form =
                                "
                                  <div class='row'>
                                      <div class='col-lg-6 col-sm-12'>
                                          <label for='name_person' class='lead fw-normal'>Nome:</label>
                                          <input type='text' id='name_person' class='form-control' name='name_person' value='".$return['name_person']."' required><br>
                                          <label for='email_person' class='lead fw-normal'>E-mail:</label>
                                          <input type='email' id='email-person' class='form-control' name='email_person' value='".$return['email_person']."' required><br>
                                          <label for='telephone_person' class='lead fw-normal'>Telefone:</label> 
                                          <input type='tel' id='telephone_person' class='form-control' name='telephone_person' value='".$return['telephone_person']."' required><br>
                                      </div>
                                      <div class='col-lg-6 col-sm-12'>
                                          <label for='cpf_person' class='lead fw-normal'>CPF:</label>
                                          <input type='text' id='cpf_person' class='form-control' name='cpf_person' value='".$return['cpf_person']."' required><br>
                                          <label for='rg_person' class='lead fw-normal'>RG:</label>
                                          <input type='text' id='rg_person' class='form-control' name='rg_person' value='".$return['rg_person']."' required><br>
                                          <label for='treatment_person' class='lead fw-normal'>Tratamento:</label>
                                          <select class='form-control' name='treatment_person' id='treatment_person' required>
                                            <option selected value='".$return['treatment_person']."'>".$return['treatment_person']."</option>
                                            <option value='Sr'>Senhor</option>
                                            <option value='Sra'>Senhora</option>
                                            <option value='Srta'>Senhorita</option>
                                            <option value='Dr'>Doutor</option>
                                            <option value='Dra'>Doutora</option>
                                          </select>
                                      </div> 
                                  </div> 

                                  <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' value='Salvar'>
                                ";

                                echo $form;

                              }

                            } else if ($type == 'university') {
                              // Edição de Instituições de ensino
                              
                              $query = 'SELECT * FROM university WHERE id_university = :id';

                              $stmt = $conn->prepare($query);

                              $stmt->bindValue(':id', $idDec);

                              $stmt->execute();

                              $return = $stmt->fetch(PDO::FETCH_ASSOC);
                              
                              if($return['valid']) {
                                echo '<h2 class="py-2">Edição - '.$return['name_university'].'</h2>';

                                $form = 
                                "
                                  <div class='row'>
                                    <div class='col-lg-6 col-sm-12'>
                                        <label for='cnpj_university' class='lead fw-normal'>CNPJ:</label>
                                        <input type='text' id='cnpj_university' class='form-control' name='cnpj_university' value='".$return['cnpj_university']."' required><br>
                                        <label for='name_university' class='lead fw-normal'>Nome:</label>
                                        
                                        <input type='text' id='name_university' class='form-control' name='name_university' value='".$return['name_university']."' required><br>
                                        
                                        
                                        <label for='state_registration_university' class='lead fw-normal'>Inscrição estadual:</label>
                                        <input type='text' id='state_registration_university' class='form-control' name='state_registration_university' value='".$return['state_registration_university']."' required><br>
                                        <label for='corporate_name_university' class='lead fw-normal'>Razão social:</label>
                                        <input type='text' id='corporate_name_university' class='form-control' name='corporate_name_university' value='".$return['corporate_name_university']."' required><br>
                                        <label for='legal_representative_university' class='lead fw-normal'>Representante legal:</label>
                                        <input type='text' id='legal_representative_university' class='form-control' name='legal_representative_university' value='".$return['legal_representative_university']."' required><br>
                                        <label for='activity_branch_university' class='lead fw-normal'>Ramo de atividade:</label>
                                        <input type='text' id='activity_branch_university' class='form-control' name='activity_branch_university' value='".$return['activity_branch_university']."' required><br>
                                        <label for='address_university' class='lead fw-normal'>Endereço:</label>
                                        <input type='text' id='address_university' class='form-control' name='address_university' value='".$return['address_university']."' required><br>
                                        <label for='home_page_university' class='lead fw-normal'>Homepage:</label>
                                        <input type='text' id='home_page_university' class='form-control' name='home_page_university' value='".$return['home_page_university']."' required><br>
                                    </div>
                                    <div class='col-lg-6 col-sm-12'>
                                        <label for='district_university' class='lead fw-normal'>Bairro:</label>
                                        <input type='text' id='district_university' class='form-control' name='district_university' value='".$return['district_university']."' required><br>
                                        <label for='cep_university' class='lead fw-normal'>CEP:</label>
                                        <input type='text' id='cep_university' class='form-control' name='cep_university' value='".$return['cep_university']."' onblur='searchCep(this.value);' required><br>
                                        <label for='mailbox_university' class='lead fw-normal'>Caixa postal:</label>
                                        <input type='text' id='mailbox_university' class='form-control' name='mailbox_university' value='".$return['mailbox_university']."'><br>
                                        <label for='city_university' class='lead fw-normal'>Cidade:</label>
                                        <input type='text' id='city_university' class='form-control' name='city_university' value='".$return['city_university']."' required><br>
                                        <label for='state_university' class='lead fw-normal'>Estado:</label>
                                        <select  class='form-control' name='state_university' id='state_university' required>
                                          <option selected value='".$return['state_university']."'>".$return['state_university']."</option>
                                          <option value='AC'>AC</option>    
                                          <option value='AL'>AL</option>   
                                          <option value='AP'>AP</option>  
                                          <option value='AM'>AM</option>
                                          <option value='BA'>BA</option>
                                          <option value='CE'>CE</option>
                                          <option value='DF'>DF</option>
                                          <option value='ES'>ES</option>
                                          <option value='GO'>GO</option>
                                          <option value='AM'>AM</option>
                                          <option value='MT'>MT</option>
                                          <option value='MS'>MS</option>
                                          <option value='MG'>MG</option>
                                          <option value='PA'>PA</option>
                                          <option value='PB'>PB</option>
                                          <option value='PR'>PR</option>
                                          <option value='PE'>PE</option>
                                          <option value='PI'>PI</option>
                                          <option value='RJ'>RJ</option>
                                          <option value='RN'>RN</option>
                                          <option value='RS'>RS</option>
                                          <option value='RO'>RO</option>
                                          <option value='RR'>RR</option>
                                          <option value='SC'>SC</option>
                                          <option value='SP'>SP</option>
                                          <option value='SE'>SE</option>
                                          <option value='TO'>TO</option>
                                        </select> <br>
                                        <label for='telephone_university' class='lead fw-normal'>Telefone:</label>
                                        <input type='tel' id='telephone_university' class='form-control' name='telephone_university' value='".$return['telephone_university']."' required><br>
                                        <label for='email_university' class='lead fw-normal'>E-mail:</label>
                                        <input type='text' id='email_university' class='form-control' name='email_university' value='".$return['email_university']."' required><br>

                                    </div>
                                  </div>
                                  <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' value='Salvar'>
                                ";   

                                echo $form; 
                              }
                              else {
                                header('Location: adminPage.php');
                              }
                                                  
                            } else if ($type == 'company') {
                              // Edição de Empresas

                              $query = 'SELECT * FROM company WHERE id_company = :id';

                              $stmt = $conn->prepare($query);

                              $stmt->bindValue(':id', $idDec);

                              $stmt->execute();

                              $return = $stmt->fetch(PDO::FETCH_ASSOC);
                              
                              if($return['valid']) {
                                  echo '<h2 class="py-2">Edição - '.$return['name_company'].'
                                          
                                      </h2>
                                  ';
                                      
                                  $form = 
                                  "
                                  <div class='row'>
                                  <div class='col-lg-6 col-sm-12'>
                                      <label for='name-company' class='lead fw-normal'>Nome:</label>  
                                      <input pattern='[A-Za-z]{\b}{1,40}'  maxlenght='40' type='text' id='name-company' class='form-control' name='name-company' value='".$return['name_company']."' required><br>
                                      
                                      <label for='corporate-name-company' class='lead fw-normal'>Nome corporativo:</label>
                                      <input type='text' id='corporate-name-company' class='form-control'  name='corporate-name-company' value='".$return['corporate_name_company']."' required><br>
                                      
                                      <label for='cnpj-company' class='lead fw-normal'>CNPJ:</label>
                                      <input type='text' id='cnpj-company' class='form-control'  name='cnpj-company' value='".$return['cnpj_company']."' required><br>
                                      
                                      <label for='legal-representative-company' class='lead fw-normal'>Representante legal:</label>  
                                      <input type='text' id='legal-representative-company' class='form-control' name='legal-representative-company' value='".$return['legal_representative_company']."' required><br>
                                      
                                      <label for='email-company' class='lead fw-normal'>E-mail:</label>
                                      <input type='email' id='email-company' maxlenght='50' class='form-control' name='email-company' value='".$return['email_company']."' required><br>
  
                                      <label for='telephone-company' class='lead fw-normal'>Telefone:</label>  
                                      <input type='text' id='telephone-company' class='form-control' name='telephone-company' value='".$return['telephone_company']."' required><br>
                                      
                                      <label for='branch-line-company' class='lead fw-normal'>Ramal:</label>
                                      <input type='text' id='branch-line-company' class='form-control'  name='branch-line-company' value='".$return['branch_line_company']."' required><br>
                                      
                                      <label for='activity-branch-company' class='lead fw-normal'>Ramo de atuação:</label>  
                                      <input type='text' id='activity-branch-company' class='form-control' name='activity-branch-company' value='".$return['activity_branch_company']."' required><br>
                                     
                                     
                                  </div>
                                  <div class='col-lg-6 col-sm-12'>
  
                                      <label for='cep-company' class='lead fw-normal'>CEP:</label>  
                                      <input type='text' id='cep-company' class='form-control' name='cep-company' value='".$return['cep_company']."'  onblur='searchCepCompany(this.value);' required><br>
                                      
                                      <label for='address-company' class='lead fw-normal'>Endereço:</label>  
                                      <input type='text' id='address-company' class='form-control' name='address-company' value='".$return['address_company']."' required><br>
  
                                      <label for='number-company' class='lead fw-normal'>Número:</label>  
                                      <input type='text' id='number-company' class='form-control' name='number-company' value='".$return['number_company']."' required><br>
                                  
                                      
                                      <label for='district-company' class='lead fw-normal'>Bairro:</label>
                                      <input type='text' id='district-company' class='form-control'  name='district-company' value='".$return['district_company']."' required><br>
                          
                                      <label for='city-company' class='lead fw-normal'>Cidade:</label>
                                      <input type='text' id='city-company' class='form-control'  name='city-company' value='".$return['city_company']."' required><br>
  
                                      
                                      <label for='state-company' class='lead fw-normal'>Estado</label>
                                      <select class='form-control' name='state-company' id='state-company' required>
                                          <option selected value='".$return['state_company']."'>".$return['state_company']."</option>
                                          <option value='AC'>AC</option>
                                          <option value='AL'>AL</option>
                                          <option value='AP'>AP</option>
                                          <option value='AM'>AM</option>
                                          <option value='BA'>BA</option>
                                          <option value='CE'>CE</option>
                                          <option value='DF'>DF</option>
                                          <option value='ES'>ES</option>
                                          <option value='GO'>GO</option>
                                          <option value='AM'>AM</option>
                                          <option value='MT'>MT</option>
                                          <option value='MS'>MS</option>
                                          <option value='MG'>MG</option>
                                          <option value='PA'>PA</option>
                                          <option value='PB'>PB</option>
                                          <option value='PR'>PR</option>
                                          <option value='PE'>PE</option>
                                          <option value='PI'>PI</option>
                                          <option value='RJ'>RJ</option>
                                          <option value='RN'>RN</option>
                                          <option value='RS'>RS</option>
                                          <option value='RO'>RO</option>
                                          <option value='RR'>RR</option>
                                          <option value='SC'>SC</option>
                                          <option value='SP'>SP</option>
                                          <option value='SE'>SE</option>
                                          <option value='TO'>TO</option>
                                      </select> <br>
  
                                      <label for='home-page-company' class='lead fw-normal'>Home page:</label>
                                      <input type='text' id='home-page-company' class='form-control'  name='home-page-company' value='".$return['home_page_company']."' required><br>
                              
                                      <label for='state-registration-company' class='lead fw-normal'>Inscrição Estadual:</label>
                                      <input type='text' id='state-registration-company' class='form-control'  name='state-registration-company' value='".$return['state_registration_company']."' required><br>
                                  
                                  </div>
                                  
                                   
                                  
                              </div>
                                      
                                    <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' value='Salvar'>
                                  ";   

                                  echo $form;     
                              }
                              else {
                                header('Location: adminPage.php');
                              }
                            }
                            else {
                              header('Location: adminPage.php');
                            } 
                        ?>

                    </form>
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