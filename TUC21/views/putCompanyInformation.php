<?php

use Google\Service\Script;

session_start();

    require_once('../app/php/functions.php');
    require_once('../app/db/connect.php');

    if(isset($_GET['key'])){          //ema    <-- o ema sagrado
        $key = cleanString($_GET['key']);

        $query = 'SELECT token, valid_date FROM tokens WHERE token = :acessKey';
    
        $stmt = $conn->prepare($query);

        $stmt->bindValue(':acessKey', $key);

        $stmt->execute();
        $return = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $validDate = date("U", $return[0]['valid_date']);

        if(count($return) < 1) {
            header("Location: ../index.php ");
            exit();
        } else if ($validDate < date('U', time())) {       
            // Exclusão do Token

            $query = 'DELETE FROM tokens WHERE token = :token';

            $stmt = $conn->prepare($query);

            $stmt->bindValue(':token', $key);

            $stmt->execute();

            header('Location: ../index.php');
            exit();
        }
    }
    else {
        header('Location: ../index.php');
        exit();
    }  
    
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Empresa</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../public/bootstrap/bootstrap.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../public/css/style.css">

    <!-- JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js%22%3E"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js" 
        integrity="sha256-yE5LLp5HSQ/z+hJeCqkz9hdjNkk1jaiGG0tDCraumnA=" 
        crossorigin="anonymous"
    ></script>
    <script type="text/javascript">
        function sub01(){
            $('[name=cnpj-company]').removeAttr('required');
            $('#f2').append('<input type="hidden" name="sub0" />');
            $('#f2').submit();            
        };
        
        function sub02(){
            $('[name=branch-line-company]').removeAttr('required');
            $('[name=telephone-company]').removeAttr('required');
            $('[name=telephone2-company]').removeAttr('required');
            $('#f3').append('<input type="hidden" name="sub1" />');
            $('#f3').submit();            
        };

        function sub03(){
            $('[name=legal-representative-company]').removeAttr('required');
            $('[name=section-company]').removeAttr('required');
            $('[name=function-company]').removeAttr('required');
            $('[name=activity-branch-company]').removeAttr('required');
            $('[name=corporate-name-company]').removeAttr('required');
            $('[name=home-page-company]').removeAttr('required');
            $('#f4').append('<input type="hidden" name="sub2" />');
            $('#f4').submit();            
        };
        function sub04(){
            $('[name=city-company]').removeAttr('required');
            $('[name=cep-company]').removeAttr('required');
            $('[name=state-company]').removeAttr('required');
            $('[name=district-company]').removeAttr('required');
            $('[name=number-company]').removeAttr('required');
            $('[name=address-company]').removeAttr('required');
            $('[name=state-registration-company]').removeAttr('required');
            $('#f5').append('<input type="hidden" name="sub3" />');
            $('#f5').submit();            
        };
            
        function edit1(){
            //$('[name=name-company]').removeAttr('disabled');  
            //$('[name=email-company]').removeAttr('disabled');  
            $('[name=telephone-company]').removeAttr('disabled');
            $('[name=telephone2-company]').removeAttr('disabled');        
            $('[name=cnpj-company]').removeAttr('disabled');
            $('[name=branch-line-company]').removeAttr('disabled');
            $('[name=cep-company]').removeAttr('disabled');
            $('[name=legal-representative-company]').removeAttr('disabled');
            $('[name=section-company]').removeAttr('disabled');
            $('[name=function-company]').removeAttr('disabled');
            $('[name=activity-branch-company]').removeAttr('disabled');
            $('[name=corporate-name-company]').removeAttr('disabled');
            $('[name=home-page-company]').removeAttr('disabled');
            $('[name=number-company]').removeAttr('disabled');
            $('[name=state-registration-company]').removeAttr('disabled');
        };

        function submitForm(){
            $('[name=name-company]').removeAttr('disabled');  
            $('[name=email-company]').removeAttr('disabled');  
            $('[name=telephone-company]').removeAttr('disabled');
            $('[name=telephone2-company]').removeAttr('disabled');        
            $('[name=cnpj-company]').removeAttr('disabled');
            $('[name=branch-line-company]').removeAttr('disabled');
            $('[name=city-company]').removeAttr('disabled');
            $('[name=cep-company]').removeAttr('disabled');
            $('[name=address-company]').removeAttr('disabled');
            $('[name=state-company]').removeAttr('disabled');
            $('[name=district-company]').removeAttr('disabled');
            $('[name=number-company]').removeAttr('disabled');    
            $('[state-registration-company]').removeAttr('disabled');  
            $('[name=legal-representative-company]').removeAttr('disabled');
            $('[name=section-company]').removeAttr('disabled');
            $('[name=function-company]').removeAttr('disabled');
            $('[name=activity-branch-company]').removeAttr('disabled');
            $('[name=corporate-name-company]').removeAttr('disabled');
            $('[name=home-page-company]').removeAttr('disabled');
            $('[name=number-company]').removeAttr('disabled');
            $('[name=state-registration-company]').removeAttr('disabled');

            $('[name=name-company]').attr('required');  
            $('[name=email-company]').attr('required');  
            $('[name=telephone-company]').attr('required');
            $('[name=telephone2-company]').attr('required');        
            $('[name=cnpj-company]').attr('required');
            $('[name=branch-line-company]').attr('required');
            $('[name=city-company]').attr('required');
            $('[name=cep-company]').attr('required');
            $('[name=address-company]').attr('required');
            $('[name=state-company]').attr('required');
            $('[name=district-company]').attr('required');
            $('[name=number-company]').attr('required'); 
            $('[state-registration-company]').attr('required'); 
            $('[name=legal-representative-company]').attr('required');
            $('[name=section-company]').attr('required');
            $('[name=function-company]').attr('required');
            $('[name=activity-branch-company]').attr('required');
            $('[name=corporate-name-company]').attr('required');
            $('[name=home-page-company]').attr('required');
            $('[name=number-company]').attr('required');
            $('[name=state-registration-company]').attr('required');
            
            $('form').append('<input type="hidden" name="sub6" />');
            $('form').submit();
        };

        function cleanFormCep(){
                document.getElementById('cep-company').value=("");
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
        
        /*Máscaras - inputs */
        jQuery(function($){
            $("#cnpj-company").mask("00.000.000/0000-00");
            $("#telephone-company").mask("(00) 00000-0000");
            $("#telephone2-company").mask("(00) 00000-0000");
            $("#cep-company").mask("00000-000");
            $("#mailbox-company").mask("0000 0000");
            $("#branch-line-company").mask("0000");
            $("#state-registration-company").mask("000.000.000.000");             
        });
    </script>
</head>
<body>

    <div class="container min-vh-100">
        <header class="row bg-white">
            <div class="col-8 d-flex align-items-center">
                <img class="w-100 unesp" src="../public/images/logo_cti.png" alt="Logo UNESP">
            </div>
            
            <div class="col-4 pt-3 pb-3 d-flex justify-content-end">
                <img class="w-100 feb" src="../public/images/logo_ctinter.jpg" alt="Logo FEB">
            </div>
        </header>

        <?php

            if(isset($_SESSION['done'])){
                if($_SESSION['done']){ //se a sessão estiver setada com 1
                    $_POST['sub0'] = 1;
                    $_SESSION['done'] = 0;
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



            $inf[] = "name-company";
            $inf[] = "email-company";
            $inf[] = "telephone-company";
            $inf[] = "telephone2-company";
            $inf[] = "mailbox-company";
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
            $inf[] = "section-company";       
            $inf[] = "function-company";      
            $inf[] = "activity-branch-company";
            $inf[] = "corporate-name-company";
            $inf[] = "home-page-company";

            for ($i=0; $i<=19; $i++)
            {
                if(!isset($_POST[$inf[$i]])){
                    if(!isset($_SESSION[$inf[$i]])){
                        $_SESSION[$inf[$i]] = '';
                    }
                }else{
                    $_SESSION[$inf[$i]] = $_POST[$inf[$i]];  
                }
            }
            
            if(isset($_POST['sub0'])){
                $_SESSION['section'] = 1;
            }
            else if(isset($_POST['sub1'])){
                $_SESSION['section'] = 2;
            }
            else if(isset($_POST['sub2'])){

                $query = 'SELECT name_company FROM company WHERE cnpj_company = :cnpj_company';
    
                $stmt = $conn->prepare($query);

                $stmt->bindValue(':cnpj_company', $_SESSION['cnpj-company']);

                $stmt->execute();

                $return = $stmt->fetchAll(PDO::FETCH_ASSOC);                
                
                if($return)
                {
                    $_SESSION['section'] = 2;
                    echo "<script>alert('CNPJ já está em uso! Redigite.');</script>";
                }
                else if(verifyCNPJ($_SESSION['cnpj-company']))
                {
                    $_SESSION['section'] = 3;
                }
                else
                {
                    $_SESSION['section'] = 2;
                    echo "<script>alert('CNPJ inválido! Redigite.');</script>";
                }
            }
            else if(isset($_POST['sub3']))
            {
                $_SESSION['section'] = 4;
            }
            else if(isset($_POST['sub4']))
            {
                $_SESSION['section'] = 5;
            }
            else if(isset($_POST['sub5']))
            {
                $_SESSION['section'] = 6;
            }
            else if(isset($_POST['sub6'])){

                $query = 'SELECT name_company FROM company WHERE cnpj_company = :cnpj_company';
    
                $stmt = $conn->prepare($query);

                $stmt->bindValue(':cnpj_company', $_SESSION['cnpj-company']);

                $stmt->execute();

                $return = $stmt->fetchAll(PDO::FETCH_ASSOC);        

                if(verifyCNPJ($_SESSION['cnpj-company']) && !$return)
                {
                    echo "<script>location= 'http://localhost/UNESP-Internship/TUC21/app/php/registerCompanyLogic.php?key=".$key."';</script>"; 
                    exit();
                }

                if(verifyCNPJ($_SESSION['cnpj-company']))
                {
                    echo "<script>location= 'http://localhost/UNESP-Internship/TUC21/app/php/registerCompanyLogic.php?key=".$key."';</script>"; 
                    exit();
                }
            }
            else {
                $_SESSION['section'] = 0;
            }       

            
            if($_SESSION['section'] == 6){
                $form6="
                    <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                        <form action='?key=".$_GET['key']."' method='POST' id='f6'>
                            <h2 class='text-white'>Dados da Empresa
                                <button type='button' class='btn btn-light' data-toggle='tooltip' data-placement='right' title='Editar' onclick='edit1()'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-pencil-fill' viewBox='0 0 16 16'>
                                        <path d='M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z'/>
                                    </svg>
                                </button>
                            </h2>
                            <div class='row'>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='name-company' class='lead fw-normal' style='color: white;'>Nome:</label>  
                                    <input pattern='[A-Za-z]{\b}{1,40}'  maxlenght='40' type='text' id='name-company' class='form-control' name='name-company' value='".$_SESSION['name-company']."' disabled><br>
                                    
                                    <label for='corporate-name-company' class='lead fw-normal' style='color: white;'>Nome corporativo:</label>
                                    <input type='text' id='corporate-name-company' class='form-control'  name='corporate-name-company' value='".$_SESSION['corporate-name-company']."' disabled><br>
                                    
                                    <label for='cnpj-company' class='lead fw-normal' style='color: white;'>CNPJ:</label>
                                    <input type='text' id='cnpj-company' class='form-control'  name='cnpj-company' value='".$_SESSION['cnpj-company']."' disabled><br>
                                    
                                    <label for='legal-representative-company' class='lead fw-normal' style='color: white;'>Representante legal:</label>  
                                    <input type='text' id='legal-representative-company' class='form-control' name='legal-representative-company' value='".$_SESSION['legal-representative-company']."' disabled><br>
                                    
                                    
                                    <label for='section-company' class='lead fw-normal' style='color: white;'>Seção do Representante:</label>  
                                    <input type='text' id='section-company' class='form-control' name='section-company' value='".$_SESSION['section-company']."' disabled><br>

                                    <label for='function-company' class='lead fw-normal' style='color: white;'>Função do Representante:</label>  
                                    <input type='text' id='function-company' class='form-control' name='function-company' value='".$_SESSION['function-company']."' disabled><br>

                                    <label for='email-company' class='lead fw-normal' style='color: white;'>E-mail:</label>
                                    <input type='email' id='email-company' maxlenght='50' class='form-control' name='email-company' value='".$_SESSION['email-company']."' disabled><br>

                                    <label for='telephone-company' class='lead fw-normal' style='color: white;'>Telefone:</label>  
                                    <input type='text' id='telephone-company' class='form-control' name='telephone-company' value='".$_SESSION['telephone-company']."' disabled><br>

                                    <label for='telephone2-company' class='lead fw-normal' style='color: white;'>Telefone 2:</label>  
                                    <input type='text' id='telephone2-company' class='form-control' name='telephone2-company' value='".$_SESSION['telephone2-company']."' disabled><br>
                                    
                                    <label for='branch-line-company' class='lead fw-normal' style='color: white;'>Ramal:</label>
                                    <input type='text' id='branch-line-company' class='form-control'  name='branch-line-company' value='".$_SESSION['branch-line-company']."' disabled><br>
                                   
                                </div>
                                <div class='col-lg-6 col-sm-12'>
                                    
                                    <label for='activity-branch-company' class='lead fw-normal' style='color: white;'>Ramo de atuação:</label>  
                                    <input type='text' id='activity-branch-company' class='form-control' name='activity-branch-company' value='".$_SESSION['activity-branch-company']."' disabled><br>
                                
                                    <label for='cep-company' class='lead fw-normal' style='color: white;'>CEP: </label>  
                                    <input type='text' id='cep-company' class='form-control' name='cep-company' value='".$_SESSION['cep-company']."'  onblur='searchCep(this.value);' disabled><br>
                                    
                                    <label for='address-company' class='lead fw-normal' style='color: white;'>Endereço:</label>  
                                    <input type='text' id='address-company' class='form-control' name='address-company' value='".$_SESSION['address-company']."' disabled><br>

                                    <label for='number-company' class='lead fw-normal' style='color: white;'>Número:</label>  
                                    <input type='text' id='number-company' class='form-control' name='number-company' value='".$_SESSION['number-company']."' disabled><br>
                                
                                    
                                    <label for='district-company' class='lead fw-normal' style='color: white;'>Bairro:</label>
                                    <input type='text' id='district-company' class='form-control'  name='district-company' value='".$_SESSION['district-company']."' disabled><br>
                        
                                    <label for='city-company' class='lead fw-normal' style='color: white;'>Cidade:</label>
                                    <input type='text' id='city-company' class='form-control'  name='city-company' value='".$_SESSION['city-company']."' disabled><br>

                                    
                                    <label for='state-company' class='lead fw-normal' style='color: white;'>Estado:</label>
                                    <select class='form-control' name='state-company' id='state-company' disabled>
                                        <option selected value='".$_SESSION['state-company']."'>".$_SESSION['state-company']."</option>
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

                                    <label for='home-page-company' class='lead fw-normal' style='color: white;'>Home page:</label>
                                    <input type='text' id='home-page-company' class='form-control'  name='home-page-company' value='".$_SESSION['home-page-company']."' disabled><br>
                            
                                    <label for='state-registration-company' class='lead fw-normal' style='color: white;'>Inscrição Estadual:</label>
                                    <input type='text' id='state-registration-company' class='form-control'  name='state-registration-company' value='".$_SESSION['state-registration-company']."' disabled><br>
                                
                                </div>
                                
                                 
                                
                            </div>
                            <input type='button' class='btn btn-success col-lg-3 col-12 fw-bold' style='border:solid 1px black;' name='sub6' value='Confirmar' onclick='submitForm()'>
                            
                            
                        </form>
                    </div>
                ";
                echo $form6;

            }

            if($_SESSION['section'] == 5){
                $form4="
                    <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                        <form action='?key=".$_GET['key']."' method='POST' id='f5'>
                            <h2 class='text-white'>Cadastro - Empresa</h2>
                            <div class='row'>
                             
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='cep-company' class='lead fw-normal' style='color: white;'>CEP *</label>  
                                    <input type='text' id='cep-company' class='form-control' name='cep-company' value='".$_SESSION['cep-company']."'  onblur='searchCep(this.value);' required><br>
                                    
                                    <label for='address-company' class='lead fw-normal' style='color: white;'>Endereço *</label>  
                                    <input type='text' id='address-company' class='form-control' name='address-company' value='".$_SESSION['address-company']."' required><br>
                                
                                    <label for='number-company' class='lead fw-normal' style='color: white;'>Número *</label>  
                                    <input type='text' id='number-company' class='form-control' name='number-company' value='".$_SESSION['number-company']."' required><br>
                                
                                    <label for='district-company' class='lead fw-normal' style='color: white;'>Bairro *</label>
                                    <input type='text' id='district-company' class='form-control'  name='district-company' value='".$_SESSION['district-company']."' required><br>
                            
                                </div>
                                <div class='col-lg-6 col-sm-12'>
                                    
                                    <label for='city-company' class='lead fw-normal' style='color: white;'>Cidade *</label>
                                    <input type='text' id='city-company' class='form-control'  name='city-company' value='".$_SESSION['city-company']."' required><br>
                                
                                    <label for='state-company' class='lead fw-normal' style='color: white;'>Estado *</label>
                                    <select class='form-control' name='state-company' id='state-company' required>
                                        <option selected value='".$_SESSION['state-company']."'>".$_SESSION['state-company']."</option>
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

                                    <label for='state-registration-company' class='lead fw-normal' style='color: white;'>Inscrição Estadual *</label>
                                    <input type='text' id='state-registration-company' class='form-control'  name='state-registration-company' value='".$_SESSION['state-registration-company']."' required><br>
                                
                                </div>
                            </div>
                            <input type='button' name='sub3' onclick='sub04()' class='btn btn-dark fw-bold' value='Voltar'>
                            <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' name='sub5' value='Enviar'>
                        </form> 
                    </div>
                ";
                echo $form4;               
            } 

            //legal_representative_company VARCHAR(100) NOT NULL,
            //activity_branch_company VARCHAR(50) NOT NULL,
            //corporate_name_company VARCHAR(100) NOT NULL, /*razão social*/
            //home_page_company VARCHAR(50) NOT NULL,

            if($_SESSION['section'] == 4){
                $form3="
                    <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                        <form action='?key=".$_GET['key']."' method='POST' id='f4'>
                            <h2 class='text-white'>Cadastro - Empresa</h2>
                            <div class='row'>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='legal-representative-company' class='lead fw-normal' style='color: white;'>Representante legal *</label>  
                                    <input type='text' id='legal-representative-company' class='form-control' name='legal-representative-company' value='".$_SESSION['legal-representative-company']."' required><br>
                                    
                                    <label for='section-company' class='lead fw-normal' style='color: white;'>Seção do Representante *</label>  
                                    <input type='text' id='section-company' class='form-control' name='section-company' value='".$_SESSION['section-company']."' required><br>

                                    <label for='function-company' class='lead fw-normal' style='color: white;'>Função do Representante *</label>  
                                    <input type='text' id='function-company' class='form-control' name='function-company' value='".$_SESSION['function-company']."' required><br>
                                
                                </div>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='corporate-name-company' class='lead fw-normal' style='color: white;'>Nome corporativo *</label>
                                    <input type='text' id='corporate-name-company' class='form-control'  name='corporate-name-company' value='".$_SESSION['corporate-name-company']."' required><br>
                                    
                                    <label for='activity-branch-company' class='lead fw-normal' style='color: white;'>Ramo de atuação *</label>  
                                    <input type='text' id='activity-branch-company' class='form-control' name='activity-branch-company' value='".$_SESSION['activity-branch-company']."' required><br>

                                    <label for='home-page-company' class='lead fw-normal' style='color: white;'>Home page *</label>
                                    <input type='text' id='home-page-company' class='form-control'  name='home-page-company' value='".$_SESSION['home-page-company']."' required><br>
                                </div>
                            </div>
                            <input type='button' name='sub2' onclick='sub03()' class='btn btn-dark fw-bold' value='Voltar'>
                            <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' name='sub4' value='Enviar'>
                        </form> 
                    </div>
                ";
                echo $form3;               
            } 

            if($_SESSION['section'] == 3){
                $form3="
                    <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                        <form action='?key=".$_GET['key']."' method='POST' id='f3'>
                            <h2 class='text-white'>Cadastro - Empresa</h2>
                            <div class='row'>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='telephone-company' class='lead fw-normal' style='color: white;'>Telefone *</label>  
                                    <input type='text' id='telephone-company' class='form-control' name='telephone-company' value='".$_SESSION['telephone-company']."' required><br>
                                    
                                    <label for='telephone2-company' class='lead fw-normal' style='color: white;'>Telefone 2 *</label>  
                                    <input type='text' id='telephone2-company' class='form-control' name='telephone2-company' value='".$_SESSION['telephone2-company']."' required><br>
                                </div>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='branch-line-company' class='lead fw-normal' style='color: white;'>Ramal *</label>
                                    <input type='text' id='branch-line-company' class='form-control'  name='branch-line-company' value='".$_SESSION['branch-line-company']."' required><br>
                                    
                                    <label for='mailbox-company' class='lead fw-normal' style='color: white;'>Fax *</label>
                                    <input type='text' id='mailbox-company' class='form-control'  name='mailbox-company' value='".$_SESSION['mailbox-company']."' required><br>
                                </div>
                            </div>
                            <input type='button' name='sub1' onclick='sub02()' class='btn btn-dark fw-bold' value='Voltar'>
                            <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' name='sub3' value='Enviar'>
                        </form> 
                    </div>
                ";
                echo $form3;               
            } 
            

            if($_SESSION['section'] == 2){
                $form2="
                    <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                        <form action='?key=".$_GET['key']."' method='POST' id='f2'>
                            <h2 class='text-white'>Cadastro - Empresa</h2>
                            <label for='cnpj-company' class='lead fw-normal' style='color: white;'>CNPJ *</label>
                            <input type='text' id='cnpj-company' class='form-control'  name='cnpj-company' value='".$_SESSION['cnpj-company']."' required><br>
                            <input type='button' name='sub0' onclick='sub01()' class='btn btn-dark fw-bold' value='Voltar'>
                            <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' name='sub2' value='Enviar'>
                        </form> 
                    </div>
                ";
                echo $form2;               
            } 

            if($_SESSION['section'] == 1){
                $form1 = "
                    <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                        <form action='?key=".$_GET['key']."' method='POST'>
                            <h2 class='text-white'>Cadastro - Empresa</h2>
                            <label for='name-company' class='lead fw-normal' style='color: white;'>Nome:</label>
                            <input pattern='[A-Za-z]{\b}{1,40}'  maxlenght='40' type='text' id='name-company' class='form-control' name='name-company' value='".$_SESSION['name-company']."' disabled><br>
                            <label for='email-company' class='lead fw-normal' style='color: white;'>E-mail:</label>
                            <input type='email' id='email-company' maxlenght='50' class='form-control' name='email-company' value='".$_SESSION['email-company']."' disabled><br>
                            <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' name='sub1' value='Enviar'>
                        </form>
                    </div>
                ";
                echo $form1;
            }

            if($_SESSION['section'] == 0){
                //Google Button
                $form0 = "
                <div class='w-100 rounded bg-primary' style='padding: 8em 1em;'>
                    <div class='text-center'>
                        <h2 class='text-light mb-5'>Login Automático com Google</h2>
                        <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                            <div class='google-login d-flex justify-content-center'>
                                <div id='g_id_onload'
                                    data-client_id='491184431140-06ob6ri8njp6gu0d3o2a6fknqo94nj4e.apps.googleusercontent.com'
                                    data-context='signin'
                                    data-ux_mode='popup'
                                    data-login_uri='http://localhost/UNESP-Internship/TUC21/app/php/google/verifyCompanyEmail.php?key=".$_GET['key']."'
                                    data-auto_prompt='false'>
                                </div>
                
                                <div class='g_id_signin'
                                    data-type='standard'
                                    data-shape='pill'
                                    data-theme='outline'
                                    data-text='signin_with'
                                    data-size='large'
                                    data-logo_alignment='left'>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>";
                echo $form0;
            }

        ?>

        <footer class="text-center pt-5 pb-3">
             &copy; 2022 CTI - Colégio Técnico Industrial "Prof. Isaac Portal Roldán"
        </footer>

        <!-- Google API -->
        <script src="https://accounts.google.com/gsi/client" async defer></script>
    
    </div>
        
</body>
</html>
