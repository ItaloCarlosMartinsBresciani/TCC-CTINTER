<?php
    session_start();

    if(!isset($_SESSION['isAuth'])){
      echo "<script>alert('Usuário não reconhecido.');</script>";
        header("Location: ../../index.php ");
        exit();
    }
    
    $id = $_SESSION["idUser"];

    require_once('../../app/db/connect.php');
    require_once('../../app/php/functions.php');

    $query = "SELECT * FROM change_data_companies WHERE fk_id = ".$id." AND allowed = FALSE";
   
    $stmt = $conn->prepare($query);

    $return = $stmt->execute();

    $return = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($return) //se o pedido ainda não foi permitido
    {
        $query = "SELECT * FROM change_data_companies WHERE fk_id = ".$id." AND allowed = FALSE AND pending_allowance = TRUE";
   
        $stmt = $conn->prepare($query);

        $return = $stmt->execute();

        $return = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $query = "SELECT * FROM change_data_companies WHERE fk_id = ".$id." AND edited = TRUE";
   
        $stmt = $conn->prepare($query);

        $return_2 = $stmt->execute();

        $return_2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $query = "SELECT * FROM change_data_companies WHERE fk_id = ".$id." AND blocked_edition = TRUE";
   
        $stmt = $conn->prepare($query);

        $return_3 = $stmt->execute();

        $return_3 = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($return_3)
        {
            echo "<script>alert('Seu poder de requisição de edição foi bloqueado. Caso achar que isso é um erro, entre em contato com seus superiores.');</script>";
            echo "<script>window.location.replace('companyPage.php')</script>";
        }
        else if ($return)
        {
            echo "<script>alert('Seu pedido de edição ainda não foi respondido.');</script>";           
            echo "<script>window.location.replace('companyPage.php')</script>";
        }
        else if ($return_2)
        {
            echo "<script>alert('Você já editou seus dados, peça permissão novamente caso queira editar outras informações.');</script>";
            echo "<script>window.location.replace('companyPage.php')</script>";
        }
        else
        {
            echo "<script>alert('Você não possui acesso à edição destes dados.');</script>";
            echo "<script>window.location.replace('companyPage.php')</script>";
        }
    }  
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edição - Company</title>

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
        $("#cnpj_company").mask("00.000.000/0000-00");
        $("#state_registration_company").mask("00.000.0000-0");
        $("#telephone_company").mask("(00) 00000-0000");
        $("#mailbox_company").mask("00000-000");
        $("#cep_company").mask("00000-000");
        $("#cnpj_company").mask("00.000.000/0000-00");
        $("#telephone_company").mask("(00) 00000-0000");
        $("#cep_company").mask("00000-000");
        $("#branch_line_company").mask("0000"); 
    });

    function cleanFormCep(){
      document.getElementById('address-company').value=("");
      document.getElementById('district-company').value=("");
      document.getElementById('city-company').value=("");
      document.getElementById('state-company').value=("");
    };

    function callbackCep(conteudo) {
        if (!("erro" in conteudo)) {
            document.getElementById('address-company').value=(conteudo.logradouro);
            document.getElementById('district-company').value=(conteudo.bairro);
            document.getElementById('city-company').value=(conteudo.localidade);
            document.getElementById('state-company').value=(conteudo.uf);
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
                
                document.getElementById('address-company').value="...";
                document.getElementById('district-company').value="...";
                document.getElementById('city-company').value="...";
                document.getElementById('state-company').value="...";
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

    function cleanFormCepCompany(){
      document.getElementById('address-company').value=("");
      document.getElementById('district-company').value=("");
      document.getElementById('city-company').value=("");
      document.getElementById('state-company').value=("");
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
                      <a href="companyPage.php" class="navbar-brand" style="font-size: 24px;"><i class="fas fa-arrow-circle-left"></i></a>
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
                    <form action="../../app/php/company/logic/companyEditLogic.php?<?php echo "type=$type&id=$id"?>" method="POST">
                        
                        <?php
                            if ($type == 'company') {
                              // Edição de Empresas

                              $query = 'SELECT * FROM company WHERE id_company = :id';

                              $stmt = $conn->prepare($query);

                              $stmt->bindValue(':id', $_SESSION["idUser"]);

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
                                          <label for='name_company' class='lead fw-normal'>Nome:</label>
                                          <input type='text' id='name_company' class='form-control' name='name_company' value='".$return['name_company']."' required><br>
                                          
                                          <label for='telephone_company' class='lead fw-normal'>Telefone:</label>
                                          <input type='tel' id='telephone_company' class='form-control' name='telephone_company' value='".$return['telephone_company']."' required><br>
                                          
                                          <label for='email_company' class='lead fw-normal'>E-mail:</label>
                                          <input type='text' id='email_company' class='form-control' name='email_company' value='".$return['email_company']."' required><br> 
                                          
                                          <label for='cnpj_company' class='lead fw-normal'>CNPJ:</label>
                                          <input type='text' id='cnpj_company' class='form-control' name='cnpj_company' value='".$return['cnpj_company']."' required><br>
                                          
                                          <label for='branch_line_company' class='lead fw-normal'>Ramal:</label>
                                          <input type='text' id='branch_line_company' class='form-control' name='branch_line_company' value='".$return['branch_line_company']."' required><br>
                                      
                                          <label for='state_company' class='lead fw-normal'>Estado:</label>
                                          <select  class='form-control' name='state_company' id='state_company' required>
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
                                          
                                          </div>
                                      <div class='col-lg-6 col-sm-12'>
                                          <label for='cep_company' class='lead fw-normal'>CEP:</label>
                                          <input type='text' id='cep_company' class='form-control' name='cep_company' value='".$return['cep_company']."' onblur='searchCepCompany(this.value);' required><br>
                                          
                                          <label for='address_company' class='lead fw-normal'>Endereço:</label>
                                          <input type='text' id='address_company' class='form-control' name='address_company' value='".$return['address_company']."' required><br>
                                          
                                          <label for='number_company' class='lead fw-normal'>Número:</label> 
                                          <input type='text' id='number_company' class='form-control' name='number_company' value='".$return['number_company']."' required><br>
                                      
                                          <label for='district_company' class='lead fw-normal'>Bairro:</label>
                                          <input type='text' id='district_company' class='form-control' name='district_company' value='".$return['district_company']."' required ><br>
                                          
                                          <label for='city_company' class='lead fw-normal'>Cidade:</label>
                                          <input type='text' id='city_company' class='form-control' name='city_company' value='".$return['city_company']."' required ><br>
                                          
                                          
                                          
                                      </div>
                                      </div>
                                      
                                    <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' value='Salvar'>
                                  ";   

                                  echo $form;     
                              }
                              else {
                                header('Location: companyPage.php');
                              }
                            }
                            else {
                              header('Location: companyPage.php');
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

    <script src="../../js/company.js"></script>
</body>
</html>