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
    <title>Cadastro - Instituição de Ensino</title>

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
            $('[name=treatment-principal]').removeAttr('required');
            $('[name=rg-principal]').removeAttr('required');
            $('[name=cpf-principal]').removeAttr('required');
            $('[name=telephone-principal]').removeAttr('required');
            $('#f2').append('<input type="hidden" name="sub0" />');
            $('#f2').submit();            
        };
        function sub02() {
            $('[name=cnpj-university]').removeAttr('required');
            $('[name=name-university]').removeAttr('required');
            $('[name=state-registration-university]').removeAttr('required');
            $('#f3').append('<input type="hidden" name="sub1" />');
            $('#f3').submit(); 
        };
        function sub03() {
            $('[name=corporate-name-university]').removeAttr('required');
            $('[name=legal-representative-university]').removeAttr('required');
            $('[name=activity-branch-university]').removeAttr('required');
            $('[name=address-university]').removeAttr('required');
            $('[name=home-page-university]').removeAttr('required');
            $('[name=district-university]').removeAttr('required');
            $('#f4').append('<input type="hidden" name="sub2" />');
            $('#f4').submit();
        };
        function sub04(){
            $('[name=email-university]').removeAttr('required');
            $('[name=telephone-university]').removeAttr('required');
            $('[name=city-university]').removeAttr('required');
            $('[name=mail-box-university]').removeAttr('required');
            $('[name=cep-university]').removeAttr('required');
            $('[name=state-university]').removeAttr('required');
            $('[name=number-address-university]').removeAttr('required');
            $('#f5').append('<input type="hidden" name="sub3" />');
            $('#f5').submit();
        };
        
        function edit1(){
            $('[name=treatment-principal]').removeAttr('disabled');
            $('[name=rg-principal]').removeAttr('disabled');
            $('[name=cpf-principal]').removeAttr('disabled');
            $('[name=telephone-principal]').removeAttr('disabled');
        };

        function edit2(){
            
            $('[name=cnpj-university]').removeAttr('disabled');
            $('[name=name-university]').removeAttr('disabled');
            $('[name=state-registration-university]').removeAttr('disabled');
            $('[name=corporate-name-university]').removeAttr('disabled');
            $('[name=legal-representative-university]').removeAttr('disabled');
            $('[name=activity-branch-university]').removeAttr('disabled');
            $('[name=home-page-university]').removeAttr('disabled');
            $('[name=email-university]').removeAttr('disabled');
            $('[name=telephone-university]').removeAttr('disabled');
            $('[name=mailbox-university]').removeAttr('disabled');
            $('[name=cep-university]').removeAttr('disabled');
            $('[name=number-address-university]').removeAttr('disabled');

        };
        function submitForm(){
            $('[name=name-principal]').removeAttr('disabled');
            $('[name=treatment-principal]').removeAttr('disabled');
            $('[name=rg-principal]').removeAttr('disabled');
            $('[name=cpf-principal]').removeAttr('disabled');
            $('[name=telephone-principal]').removeAttr('disabled');

            $('[name=cnpj-university]').removeAttr('disabled');
            $('[name=name-university]').removeAttr('disabled');
            $('[name=state-registration-university]').removeAttr('disabled');
            $('[name=corporate-name-university]').removeAttr('disabled');
            $('[name=legal-representative-university]').removeAttr('disabled');
            $('[name=activity-branch-university]').removeAttr('disabled');
            $('[name=address-university]').removeAttr('disabled');
            $('[name=number-address-university]').removeAttr('disabled');
            $('[name=home-page-university]').removeAttr('disabled');
            $('[name=district-university]').removeAttr('disabled');
            $('[name=email-university]').removeAttr('disabled');
            $('[name=telephone-university]').removeAttr('disabled');
            $('[name=city-university]').removeAttr('disabled');
            $('[name=mailbox-university]').removeAttr('disabled');
            $('[name=cep-university]').removeAttr('disabled');
            $('[name=state-university]').removeAttr('disabled');



            $('[name=name-principal]').attr('required');
            $('[name=treatment-principal]').attr('required');
            $('[name=rg-principal]').attr('required');
            $('[name=cpf-principal]').attr('required');
            $('[name=telephone-principal]').attr('required');
            
            $('[name=cnpj-university]').attr('required');
            $('[name=name-university]').attr('required');
            $('[name=state-registration-university]').attr('required');
            $('[name=corporate-name-university]').attr('required');
            $('[name=legal-representative-university]').attr('required');
            $('[name=activity-branch-university]').attr('required');
            $('[name=address-university]').attr('required');
            $('[name=number-address-university]').attr('required');
            $('[name=home-page-university]').attr('required');
            $('[name=district-university]').attr('required');
            $('[name=email-university]').attr('required');
            $('[name=telephone-university]').attr('required');
            $('[name=city-university]').attr('required');
            $('[name=cep-university]').attr('required');
            $('[name=state-university]').attr('required');

            $('form').append('<input type="hidden" name="sub6" />');
            $('form').submit();
        };

        function submitForm4()
        {
            $('[name=city-university]').removeAttr('disabled');
            $('[name=state-university]').removeAttr('disabled');
            $('[name=address-university]').removeAttr('disabled');
            $('[name=district-university]').removeAttr('disabled');

            $('[name=city-university]').attr('required');
            $('[name=state-university]').attr('required');
            $('[name=address-university]').attr('required');
            $('[name=district-university]').attr('required');
        };
        
//aaaaaaaaaaa
            function cleanFormCep(){
                document.getElementById('cep-university').value=("");
                document.getElementById('address-university').value=("");
                document.getElementById('district-university').value=("");
                document.getElementById('city-university').value=("");
                document.getElementById('state-university').value=("");
            };

            function callbackCep(conteudo) {
                if (!("erro" in conteudo)) {
                    document.getElementById('address-university').value=(conteudo.logradouro);
                    document.getElementById('district-university').value=(conteudo.bairro);
                    document.getElementById('city-university').value=(conteudo.localidade);
                    document.getElementById('state-university').value=(conteudo.uf);
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
                        
                        document.getElementById('address-university').value="...";
                        document.getElementById('district-university').value="...";
                        document.getElementById('city-university').value="...";
                        document.getElementById('state-university').value="...";
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
            $("#telephone-principal").mask("(00) 00000-0000");
            $("#rg-principal").mask("00.000.000-0");
            $("#cpf-principal").mask("000.000.000-00");
            $("#cnpj-university").mask("00.000.000/0000-00");
            $("#state-registration-university").mask("00.000.0000-0");
            $("#telephone-university").mask("(00) 00000-0000");
            $("#mailbox-university").mask("00000-000");
            $("#cep-university").mask("00000-000");
            //$("#number_address_university").mask("00-000");
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

            function verifyCPF( $cpfPrincipal )
            {
                /*$cpfPrincipal = "$cpfPrincipal";*/
                if (strpos($cpfPrincipal, "-") !== false)
                {
                    $cpfPrincipal = str_replace("-", "", $cpfPrincipal);
                }
                if (strpos($cpfPrincipal, ".") !== false)
                {
                    $cpfPrincipal = str_replace(".", "", $cpfPrincipal);
                }
                $sum = 0;
                $cpfPrincipal = str_split( $cpfPrincipal );
                $cpftrueverifier = array();
                $cpfnumbers = array_splice( $cpfPrincipal , 0, 9 );
                $cpfdefault = array(10, 9, 8, 7, 6, 5, 4, 3, 2);
                for ( $i = 0; $i <= 8; $i++ )
                {
                    $sum += $cpfnumbers[$i]*$cpfdefault[$i];
                }
                $sumresult = $sum % 11;  
                if ( $sumresult < 2 )
                {
                    $cpftrueverifier[0] = 0;
                }
                else
                {
                    $cpftrueverifier[0] = 11-$sumresult;
                }
                $sum = 0;
                $cpfdefault = array(11, 10, 9, 8, 7, 6, 5, 4, 3, 2);
                $cpfnumbers[9] = $cpftrueverifier[0];
                for ( $i = 0; $i <= 9; $i++ )
                {
                    $sum += $cpfnumbers[$i]*$cpfdefault[$i];
                }
                $sumresult = $sum % 11;
                if ( $sumresult < 2 )
                {
                    $cpftrueverifier[1] = 0;
                }
                else
                {
                    $cpftrueverifier[1] = 11 - $sumresult;
                }
                $returner = false;
                if ( $cpfPrincipal == $cpftrueverifier )
                {
                    $returner = true;
                }


                $cpfver = array_merge($cpfnumbers, $cpfPrincipal);

                if ( count(array_unique($cpfver)) == 1 || $cpfver == array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0) )

                {

                    $returner = false;

                }
                return $returner;
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
            $inf[] = "home-page-university";
            $inf[] = "district-university";
            $inf[] = "cep-university";
            $inf[] = "mailbox-university";
            $inf[] = "city-university";
            $inf[] = "state-university";
            $inf[] = "telephone-university";
            $inf[] = "email-university";
            $inf[] = "number-address-university";

            for ($i=0; $i<=21; $i++)
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
                if(verifyCPF($_SESSION['cpf-principal']))
                {
                    $_SESSION['section'] = 3;
                }
                else{
                    $_SESSION['section'] = 2;
                    echo "<script>alert('CPF inválido! Redigite.');</script>";
                }
            }
            else if(isset($_POST['sub3'])){
                if(verifyCNPJ($_SESSION['cnpj-university']))
                {
                    $_SESSION['section'] = 4;
                }
                else{
                    $_SESSION['section'] = 3;
                    echo "<script>alert('CNPJ inválido! Redigite.');</script>";
                }
            }
            else if(isset($_POST['sub4'])){
                $_SESSION['section'] = 5;
            }
            else if(isset($_POST['sub5'])){
                $_SESSION['section'] = 6;
            }
            else if(isset($_POST['sub6'])){
                //echo "alert('aaaaa')";
                if(verifyCPF($_SESSION['cpf-principal']) && verifyCNPJ($_SESSION['cnpj-university']))
                {
                    echo "<script>location= 'http://localhost/UNESP-Internship/TUC21/app/php/registerUniversityLogic.php?key=".$_GET['key']."';</script>"; 
                    // echo "<script>location = '';</script>"; //?key=".$_GET['key']."  ../app/php/registerUniversityLogic.php
                    exit();
                }
                else{
                    if(!verifyCPF($_SESSION['cpf-principal']) && !verifyCNPJ($_SESSION['cnpj-university'])){                      
                        echo "<script>alert('CPF do diretor e CNPJ da instituição inválidos! Redigite.');</script>";
                    }else
                    if (!verifyCPF($_SESSION['cpf-principal'])){
                        echo "<script>alert('CPF inválido! Redigite.');</script>";
                    }else
                    if (!verifyCNPJ($_SESSION['cnpj-university'])){
                        echo "<script>alert('CNPJ inválido! Redigite.');</script>";
                    }
                    $_SESSION['section'] = 6;
                }
            }
            else {
                $_SESSION['section'] = 0;
            }       

            if($_SESSION['section'] == 6){
                $form6="
                    <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                        <form action='?key=".$_GET['key']."' method='POST' id='f6'>
                            <h2 class='text-white'>Dados do Diretor
                                <button type='button' class='btn btn-light' data-toggle='tooltip' data-placement='right' title='Editar' onclick='edit1()'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-pencil-fill' viewBox='0 0 16 16'>
                                        <path d='M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z'/>
                                    </svg>
                                </button>
                            </h2>
                            <div class='row'>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='name-principal' class='lead fw-normal' style='color: white;'>Nome:</label>
                                    <input type='text' pattern='[A-Za-z]{\b}{1,40}' maxlenght='40' id='name-principal' class='form-control' name='name-principal' value='".$_SESSION['name-principal']."' disabled><br>
                                    <label for='email-principal' class='lead fw-normal' style='color: white;'>E-mail:</label>
                                    <input type='email' maxlenght='50' id='email-principal' class='form-control' name='email-principal' value='".$_SESSION['email-principal']."' disabled><br>
                                    <label for='telephone-principal' class='lead fw-normal' style='color: white;'>Telefone:</label>
                                    <input type='text' id='telephone-principal' class='form-control' name='telephone-principal' value='".$_SESSION['telephone-principal']."' disabled><br>
                                </div>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='cpf-principal' class='lead fw-normal' style='color: white;'>CPF:</label>
                                    <input type='text' id='cpf-principal' class='form-control' name='cpf-principal' value='".$_SESSION['cpf-principal']."' disabled><br>
                                    <label for='rg-principal' class='lead fw-normal' style='color: white;'>RG:</label>
                                    <input type='text' id='rg-principal' class='form-control' name='rg-principal' value='".$_SESSION['rg-principal']."' disabled><br>
                                    <label for='treatment-principal' class='lead fw-normal' style='color: white;'>Tratamento:</label>
                                    <input type='text' id='treatment-principal' class='form-control' placeholder='Ex: Doutor' name='treatment-principal' value='".$_SESSION['treatment-principal']."' disabled><br>
                                </div> 
                            </div> 
                            <h2 class='text-white'>Dados da Instituição de Ensino
                            <button type='button' class='btn btn-light'  data-toggle='tooltip' data-placement='right' title='Editar' onclick='edit2()'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-pencil-fill' viewBox='0 0 16 16'>
                                    <path d='M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z'/>
                                </svg>
                            </button>
                            </h2>
                            <div class='row'>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='cnpj-university' class='lead fw-normal' style='color: white;'>CNPJ:</label>
                                    <input type='text' id='cnpj-university' class='form-control' name='cnpj-university' value='".$_SESSION['cnpj-university']."' disabled><br>
                                    <label for='name-university' class='lead fw-normal' style='color: white;'>Nome:</label>
                                    <input type='text' pattern='[A-Za-z]{\b}{1,50} maxlenght='50' id='name-university' class='form-control' name='name-university' value='".$_SESSION['name-university']."' disabled><br>
                                    <label for='state-registration-university' class='lead fw-normal' style='color: white;'>Inscrição estadual:</label>
                                    <input type='text' id='state-registration-university' maxlenght='13' class='form-control' name='state-registration-university' value='".$_SESSION['state-registration-university']."' disabled><br>
                                    <label for='corporate-name-university' class='lead fw-normal' style='color: white;'>Razão social:</label>
                                    <input pattern='[A-Za-z]{\b}{1,100}' maxlenght='100' type='text' id='corporate-name-university' class='form-control' name='corporate-name-university' value='".$_SESSION['corporate-name-university']."' disabled><br>
                                    <label for='legal-representative-university' class='lead fw-normal' style='color: white;'>Representante legal:</label>
                                    <input pattern='[A-Za-z]{\b}{1,100}' maxlenght='100' type='text' id='legal-representative-university' class='form-control' name='legal-representative-university' value='".$_SESSION['legal-representative-university']."'disabled><br>
                                    <label for='activity-branch-university' class='lead fw-normal' style='color: white;'>Ramo de atividade:</label>
                                    <input pattern='[A-Za-z]{\b}{1,50}' maxlenght='50'  type='text' id='activity-branch-university' class='form-control' name='activity-branch-university' value='".$_SESSION['activity-branch-university']."' disabled><br>
                                    <label for='home-page-university' class='lead fw-normal' style='color: white;'>Homepage:</label>
                                    <input pattern='{1,50}' type='text' maxlenght='50' id='home-page-university' class='form-control' name='home-page-university' value='".$_SESSION['home-page-university']."' disabled><br>
                                    <label for='email-university' class='lead fw-normal' style='color: white;'>E-mail:</label>
                                    <input type='email' id='email-university' maxlenght='50' class='form-control' name='email-university'  value='".$_SESSION['email-university']."' disabled><br>
                                </div>  
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='cep-university' class='lead fw-normal' style='color: white;'>CEP:</label>
                                    <input type='text' id='cep-university' class='form-control' name='cep-university' value='".$_SESSION['cep-university']."' onblur='searchCep(this.value);' disabled><br>
                                    <label for='telephone-university' class='lead fw-normal' style='color: white;'>Telefone:</label>
                                    <input type='text' id='telephone-university' class='form-control' name='telephone-university' value='".$_SESSION['telephone-university']."' disabled><br>
                                    <label for='district-university' class='lead fw-normal' style='color: white;'>Bairro:</label>
                                    <input type='text' id='district-university' class='form-control' name='district-university' value='".$_SESSION['district-university']."' disabled><br>
                                    <label for='address-university' class='lead fw-normal' style='color: white;'>Endereço:</label>
                                    <input type='text' id='address-university' class='form-control' name='address-university' value='".$_SESSION['address-university']."' disabled><br>
                                    <label for='number-address-university' class='lead fw-normal' style='color: white;'>Número:</label>
                                    <input type='text' pattern='[0-9]{1,6}' id='number-address-university' maxlenght='6' class='form-control' name='number-address-university' value='".$_SESSION['number-address-university']."' disabled><br>
                                    <label for='mailbox-university' class='lead fw-normal' style='color: white;'>Caixa postal:</label>
                                    <input type='text' id='mailbox-university' minlength='10' maxlenght='10' class='form-control' name='mailbox-university' placeholder='Opcional' value='".$_SESSION['mailbox-university']."' disabled><br>
                                    <label for='city-university' class='lead fw-normal' style='color: white;'>Cidade:</label>
                                    <input pattern='[A-Za-z]{1,30}' type='text' id='city-university' class='form-control' name='city-university' value='".$_SESSION['city-university']."' disabled><br>
                                    <label for='state-university' class='lead fw-normal' style='color: white;'>Estado:</label>
                                        <select class='form-control' name='state-university' id='state-university' disabled>
                                            <option selected value='".$_SESSION['state-university']."'>".$_SESSION['state-university']."</option>
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
                                    </label>
                                </div> 
                            </div> 
                            
                            <input type='button' class='btn btn-success col-lg-3 col-12 fw-bold' style='border:solid 1px black;' name='sub6' value='Confirmar' onclick='submitForm()'>
                            
                        </form>
                    </div>
                ";
                echo $form6;

            }

            if($_SESSION['section'] == 5){
                $form5="
                    <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                        <form action='?key=".$_GET['key']."' method='POST' id='f5'>
                        <h2 class='text-white'>Cadastro - Instituição de Ensino</h2>
                            <div class='row'>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='cep-university' class='lead fw-normal' style='color: white;'>CEP *</label>
                                    <input type='text' id='cep-university' class='form-control' name='cep-university' value='".$_SESSION['cep-university']."' onblur='searchCep(this.value);' required><br>
                                    <label for='city-university' class='lead fw-normal' style='color: white;'>Cidade *</label>
                                    <input type='text' id='city-university' class='form-control' name='city-university' value='".$_SESSION['city-university']."' disabled required><br>
                                    <label for='state-university' class='lead fw-normal' style='color: white;'>Estado *</label>
                                        <select class='form-control' name='state-university' id='state-university' disabled required>
                                            <option selected value='".$_SESSION['state-university']."'>".$_SESSION['state-university']."</option>
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
                                        <label for='address-university' class='lead fw-normal' style='color: white;'>Endereço *</label>
                                        <input type='text' id='address-university' class='form-control' name='address-university' value='".$_SESSION['address-university']."' disabled><br>
                                    
                                </div>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='district-university' class='lead fw-normal' style='color: white;'>Bairro *</label>
                                    <input type='text' id='district-university' class='form-control' name='district-university' value='".$_SESSION['district-university']."' disabled><br>  
                                    <label for='mailbox-university' class='lead fw-normal' style='color: white;'>Caixa postal</label>
                                    <input type='text' id='mailbox-university' minlength='9' maxlenght='9' placeholder='Opcional' class='form-control' name='mailbox-university' value='".$_SESSION['mailbox-university']."' ><br>
                                    <label for='number-address-university' class='lead fw-normal' style='color: white;'>Número *</label>
                                    <input type='text' pattern='[0-9-]{1,6}' id='number-address-university' maxlenght='6' class='form-control' name='number-address-university' value='".$_SESSION['number-address-university']."' required><br>
                                </div>
                            </div>
                                <input type='button' name='sub3' onclick='sub04()' class='btn btn-dark fw-bold' value='Voltar'>
                                <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' name='sub5' onclick='submitForm4()' value='Enviar'>
                        </form>
                    </div>
                ";
                echo $form5;
            }

            if($_SESSION['section'] == 4){
                $form4="
                    <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                        <form action='?key=".$_GET['key']."' method='POST' id='f4'>
                            <h2 class='text-white'>Cadastro - Instituição de Ensino</h2>
                            <div class='row'>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='corporate-name-university' class='lead fw-normal' style='color: white;'>Razão social *</label>
                                    <input pattern='[A-Za-z]{\b}{1,100}' maxlenght='100' type='text' id='corporate-name-university' class='form-control' name='corporate-name-university' placeholder='Nome completo da sua Instituição' value='".$_SESSION['corporate-name-university']."' required><br>
                                    <label for='legal-representative-university' class='lead fw-normal' style='color: white;'>Representante legal *</label>
                                    <input pattern='[A-Za-z]{\b}{1,100}' maxlenght='100' type='text' id='legal-representative-university' class='form-control' name='legal-representative-university' value='".$_SESSION['legal-representative-university']."'required><br>
                                    <label for='activity-branch-university' class='lead fw-normal' style='color: white;'>Ramo de atividade *</label>
                                    <input pattern='[A-Za-z]{\b}{1,50}' maxlenght='50' type='text' id='activity-branch-university' class='form-control' name='activity-branch-university' placeholder='Comércio, Serviços, Educação, Indústria' value='".$_SESSION['activity-branch-university']."' required><br>
                                </div>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='telephone-university' class='lead fw-normal' style='color: white;'>Telefone *</label>
                                    <input type='text' id='telephone-university' class='form-control' name='telephone-university' value='".$_SESSION['telephone-university']."' required><br>
                                    <label for='email-university' class='lead fw-normal' style='color: white;'>E-mail *</label>
                                    <input type='email' maxlenght='50' id='email-university' class='form-control' name='email-university'  value='".$_SESSION['email-university']."' required><br>
                                    <label for='home-page-university' class='lead fw-normal' style='color: white;'>Homepage *</label>
                                    <input pattern='{1,50}'  maxlenght='50' type='text' id='home-page-university' class='form-control' name='home-page-university' value='".$_SESSION['home-page-university']."' required><br>
                                </div>
                            </div>
                            <input type='button' name='sub2' onclick='sub03()' class='btn btn-dark fw-bold' value='Voltar'>
                            <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' name='sub4' value='Enviar'> 
                        </form>
                    </div>
                ";
                echo $form4;
            }

            if($_SESSION['section'] == 3){
                $form3="
                    <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                        <form action='?key=".$_GET['key']."' method='POST' id='f3'>
                            <h2 class='text-white'>Cadastro - Instituição de Ensino</h2>
                            <label for='cnpj-university' class='lead fw-normal' style='color: white;'>CNPJ *</label>
                            <input type='text' id='cnpj-university' class='form-control' name='cnpj-university' value='".$_SESSION['cnpj-university']."' required><br>
                            <label for='name-university' class='lead fw-normal' style='color: white;'>Nome *</label>
                            <input type='text'  maxlenght='50' id='name-university' class='form-control' name='name-university' value='".$_SESSION['name-university']."' pattern='[A-Za-z]{\b}{1,50}' required><br>
                            <label for='state-registration-university' class='lead fw-normal' style='color: white;'>Inscrição estadual *</label>
                            <input type='text' id='state-registration-university' class='form-control' name='state-registration-university' value='".$_SESSION['state-registration-university']."' required><br>
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
                        <h2 class='text-white'>Cadastro - Diretor</h2>
                        
                            <div class='row'>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='telephone-principal' class='lead fw-normal' style='color: white;'>Telefone *</label>  
                                    <input type='text' id='telephone-principal' class='form-control' name='telephone-principal' value='".$_SESSION['telephone-principal']."' required><br>
                                    <label for='cpf-principal' class='lead fw-normal' style='color: white;'>CPF *</label>
                                    <input type='text' id='cpf-principal' class='form-control'  name='cpf-principal' value='".$_SESSION['cpf-principal']."' required><br>
                                </div>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='rg-principal' class='lead fw-normal' style='color: white;'>RG *</label>
                                    <input type='text' id='rg-principal' class='form-control' name='rg-principal' value='".$_SESSION['rg-principal']."' required><br>
                                    <label for='treatment-principal' class='lead fw-normal' style='color: white;' >Tratamento *</label>
                                        <select class='form-control' name='treatment-principal' id='treatment-principal' required>
                                            <option selected value='".$_SESSION['treatment-principal']."'>".$_SESSION['treatment-principal']."</option>
                                            <option value='Sr'>Senhor</option>
                                            <option value='Sra'>Senhora</option>
                                            <option value='Srta'>Senhorita</option>
                                            <option value='Dr'>Doutor</option>
                                            <option value='Dra'>Doutora</option>
                                        </select> <br>
                                </div>
                            </div>
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
                            <h2 class='text-white'>Cadastro - Diretor</h2>
                            <label for='name-principal' class='lead fw-normal' style='color: white;'>Nome:</label>
                            <input pattern='[A-Za-z]{\b}{1,40}'  maxlenght='40' type='text' id='name-principal' class='form-control' name='name-principal' value='".$_SESSION['name-principal']."' disabled><br>
                            <label for='email-principal' class='lead fw-normal' style='color: white;'>E-mail:</label>
                            <input type='email' id='email-principal' maxlenght='50' class='form-control' name='email-principal' value='".$_SESSION['email-principal']."' disabled><br>
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
                                    data-login_uri='http://localhost/UNESP-Internship/TUC21/app/php/google/verifyPrincipalEmail.php?key=".$_GET['key']."'
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
