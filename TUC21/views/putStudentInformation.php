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
     
    if(isset($_GET['id_coordinator'])){  
        $idCoordinator = $_GET['id_coordinator'];
       
    }else{
        echo "<script>alert('Não foi possível entrar no cadastro, o link atual foi inválido.');</script>";
        exit();
    }
    
    
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Alunos</title>

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
            $('[name=treatment-student]').removeAttr('required');
            $('[name=rg-student]').removeAttr('required');
            $('[name=cpf-student]').removeAttr('required');
            $('[name=birthday-person]').removeAttr('required');
            $('[name=telephone-student]').removeAttr('required');
            $('#f2').append('<input type="hidden" name="sub0" />');
            $('#f2').submit();            
        };
        
        function sub02(){
            $('[name=ra-student]').removeAttr('required');
            $('[name=course-code-student]').removeAttr('required');
            $('[name=year-entry-student]').removeAttr('required');
            $('[name=business-sector-student]').removeAttr('required');
            $('[name=period-student]').removeAttr('required');
            $('#f3').append('<input type="hidden" name="sub1" />');
            $('#f3').submit();            
        };

        function sub03(){
            $('[name=city-student]').removeAttr('required');
            $('[name=cep-student]').removeAttr('required');
            $('[name=district-student]').removeAttr('required');
            $('[name=number-student]').removeAttr('required');
            $('[name=address-student]').removeAttr('required');
            $('[name=complement-student]').removeAttr('required');
            $('#f4').append('<input type="hidden" name="sub2" />');
            $('#f4').submit();            
        };
            
        
        function edit1(){
            $('[name=treatment-student]').removeAttr('disabled');
            $('[name=rg-student]').removeAttr('disabled');
            $('[name=birthday-person]').removeAttr('disabled');
            $('[name=cpf-student]').removeAttr('disabled');
            $('[name=telephone-student]').removeAttr('disabled');
            $('[name=course-code-student]').removeAttr('disabled');
            $('[name=year-entry-student]').removeAttr('disabled');
            $('[name=ra-student]').removeAttr('disabled');
            $('[name=cep-student]').removeAttr('disabled');
            $('[name=city-student]').removeAttr('disabled');
            $('[name=address-student]').removeAttr('disabled');
            $('[name=district-student]').removeAttr('disabled');
            $('[name=number-student]').removeAttr('disabled');
            $('[name=business-sector-student]').removeAttr('disabled');
            $('[name=period-student]').removeAttr('disabled');
            $('[name=complement-student]').removeAttr('disabled');

        };

        function submitForm(){
            $('[name=name-student]').removeAttr('disabled');
            $('[name=birthday-person]').removeAttr('disabled');
            $('[name=treatment-student]').removeAttr('disabled');
            $('[name=rg-student]').removeAttr('disabled');
            $('[name=cpf-student]').removeAttr('disabled');
            $('[name=telephone-student]').removeAttr('disabled');
            $('[name=cep-student]').removeAttr('disabled');
            $('[name=city-student]').removeAttr('disabled');
            $('[name=address-student]').removeAttr('disabled');
            $('[name=district-student]').removeAttr('disabled');
            $('[name=number-student]').removeAttr('disabled');
            $('[name=business-sector-student]').removeAttr('disabled');
            $('[name=period-student]').removeAttr('disabled');
            $('[name=complement-student]').removeAttr('disabled');

            $('[name=birthday-person]').removeAttr('required');
            $('[name=name-student]').attr('required');
            $('[name=treatment-student]').attr('required');
            $('[name=rg-student]').attr('required');
            $('[name=cpf-student]').attr('required');
            $('[name=telephone-student]').attr('required');
            $('[name=cep-student]').attr('required');
            $('[name=city-student]').attr('required');
            $('[name=address-student]').attr('required');
            $('[name=number-student]').attr('required');
            $('[name=district-student]').attr('required');
            $('[name=business-sector-student]').removeAttr('required');
            $('[name=period-student]').removeAttr('required');
            $('[name=complement-student]').attr('required');
            
            $('form').append('<input type="hidden" name="sub6" />');
            $('form').submit();
        };

        function submitForm4()
        {
            $('[name=city-student]').removeAttr('disabled');
            $('[name=address-student]').removeAttr('disabled');
            $('[name=complement-student]').removeAttr('disabled');
            $('[name=district-student]').removeAttr('disabled');

            $('[name=city-student]').attr('required');
            $('[name=address-student]').attr('required');
            $('[name=complement-student]').attr('required');
            $('[name=number-student]').attr('required');
            $('[name=district-student]').attr('required');
        };

        function cleanFormCep(){
                document.getElementById('cep-student').value=("");
                document.getElementById('address-student').value=("");
                document.getElementById('district-student').value=("");
                document.getElementById('city-student').value=("");
                document.getElementById('complement-student').value=("");
            };

            function callbackCep(conteudo) {
                if (!("erro" in conteudo)) {
                    document.getElementById('address-student').value=(conteudo.logradouro);
                    document.getElementById('district-student').value=(conteudo.bairro);
                    document.getElementById('city-student').value=(conteudo.localidade);
                    // document.getElementById('number-student').value=(conteudo.uf);
                    document.getElementById('complement-student').value=(conteudo.complemento);
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
                        
                        document.getElementById('address-student').value="...";
                        document.getElementById('district-student').value="...";
                        document.getElementById('city-student').value="...";
                       // document.getElementById('state-student').value="...";
                        document.getElementById('complement-student').value="...";
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
        
        
//aaaaaaaaaaa
        
        /*Máscaras - inputs */
        jQuery(function($){
            $("#telephone-student").mask("(00) 00000-0000");
            $("#rg-student").mask("00.000.000-0");
            $("#cpf-student").mask("000.000.000-00");
            $("#ra-student").mask("0000000");
            $("#course-code-student").mask("0000");
            $("#year-entry-student").mask("0000");
            $("#cep-student").mask("00000-000");
            
            
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

            

            function verifyCPF( $cpfstudent )
            {
                /*$cpfstudent = "$cpfstudent";*/
                if (strpos($cpfstudent, "-") !== false)
                {
                    $cpfstudent = str_replace("-", "", $cpfstudent);
                }
                if (strpos($cpfstudent, ".") !== false)
                {
                    $cpfstudent = str_replace(".", "", $cpfstudent);
                }
                $sum = 0;
                $cpfstudent = str_split( $cpfstudent );
                $cpftrueverifier = array();
                $cpfnumbers = array_splice( $cpfstudent , 0, 9 );
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
                if ( $cpfstudent == $cpftrueverifier )
                {
                    $returner = true;
                }


                $cpfver = array_merge($cpfnumbers, $cpfstudent);

                if ( count(array_unique($cpfver)) == 1 || $cpfver == array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0) )

                {

                    $returner = false;

                }
                return $returner;
            }


      
            $inf[] = "name-student";
            $inf[] = "email-student";
            $inf[] = "telephone-student";
            $inf[] = "cpf-student";
            $inf[] = "rg-student";
            $inf[] = "treatment-student";
            $inf[] = "ra-student";
            $inf[] = "course-code-student";
            $inf[] = "year-entry-student";
            $inf[] = "business-sector-student";
            $inf[] = "period-student";
            $inf[] = "address-student";
            $inf[] = "number-student";
            $inf[] = "district-student";
            $inf[] = "city-student";
            $inf[] = "cep-student";
            $inf[] = "complement-student";
            $inf[] = "birthday-person";       

            for ($i=0; $i<=17; $i++)
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

              
                //Consistência cpf
                $query = "SELECT * from person WHERE cpf_person = :cpf_student";
    
                $stmt = $conn->prepare($query);

                $stmt->bindValue(':cpf_student', $_SESSION['cpf-student']);

                $stmt->execute();

                $return_cpf = $stmt->fetchAll(PDO::FETCH_ASSOC);    

                //Consistência rg
                $query = "SELECT * from person WHERE rg_person = :rg_student";
    
                $stmt = $conn->prepare($query);

                $stmt->bindValue(':rg_student', $_SESSION['rg-student']);

                $stmt->execute();

                $return_rg = $stmt->fetchAll(PDO::FETCH_ASSOC); 

                if($return_rg == null)
               {
                   if ($return_cpf == null)
                   {
                       if(verifyCPF($_SESSION['cpf-student']))
                       {
                           $_SESSION['section'] = 3;
                       }
                       else{
                           $_SESSION['section'] = 2;
                           echo "<script>alert('CPF inválido! Redigite.');</script>";
                       }
                   }         
                   else
                   {
                       $_SESSION['section'] = 2;    
                       echo "<script>alert('O CPF digitado já foi cadastrado! Redigite.');</script>";                        
                   }
               }
               else
               {            
                   $_SESSION['section'] = 2;    
                   echo "<script>alert('O RG digitado já foi cadastrado! Redigite.');</script>";
               }
            }
            else if (isset($_POST['sub3']))
            {
                $query = "SELECT * from student WHERE ra_student = :ra_student";
    
                $stmt = $conn->prepare($query);

                $stmt->bindValue(':ra_student', $_SESSION['ra-student']);

                $stmt->execute();

                $return_ra = $stmt->fetchAll(PDO::FETCH_ASSOC); 

                if ($return_ra)
                {
                    $_SESSION['section'] = 3;
                    echo "<script>alert('RA já existente! Redigite.');</script>";
                }
                else 
                {
                    $year = date('Y');
                    if ($_SESSION['year-entry-student'] >= ($year - 6) && $_SESSION['year-entry-student'] <= $year)
                        $_SESSION['section'] = 4;                
                    else
                    {                    
                        $_SESSION['section'] = 3;
                        echo "<script>alert('Ano inválido! Redigite.');</script>";
                    }
                }
            }
            else if(isset($_POST['sub4']))
            {
                $_SESSION['section'] = 6;
            }
            else if(isset($_POST['sub6'])){
                //Consistência cpf
                $query = "SELECT * from person WHERE cpf_person = :cpf_student";
    
                $stmt = $conn->prepare($query);

                $stmt->bindValue(':cpf_student', $_SESSION['cpf-student']);

                $stmt->execute();

                $return_cpf = $stmt->fetchAll(PDO::FETCH_ASSOC);    

                //Consistência rg
                $query = "SELECT * from person WHERE rg_person = :rg_student";
    
                $stmt = $conn->prepare($query);

                $stmt->bindValue(':rg_student', $_SESSION['rg-student']);

                $stmt->execute();

                $return_rg = $stmt->fetchAll(PDO::FETCH_ASSOC); 

                $query = "SELECT * from student WHERE ra_student = :ra_student";
    
                $stmt = $conn->prepare($query);

                $stmt->bindValue(':ra_student', $_SESSION['ra-student']);

                $stmt->execute();

                $return_ra = $stmt->fetchAll(PDO::FETCH_ASSOC); 

                if($return_rg == null)
                {
                   if ($return_cpf == null)
                   {
                       if(verifyCPF($_SESSION['cpf-student']))
                       {
                            $year = date('Y');
                            if ($_SESSION['year-entry-student'] >= ($year - 6) && $_SESSION['year-entry-student'] <= $year)
                            {
                                if ($return_ra)
                                {
                                    $_SESSION['section'] = 6;
                                    echo "<script>alert('RA já existente! Redigite.');</script>";
                                }
                                else {
                                    echo "<script>location= 'http://localhost/UNESP-Internship/TUC21/app/php/registerStudentLogic.php?key=".$key."&id_coordinator=".$idCoordinator."';</script>"; 
                                    exit();
                                }
                            }              
                            else
                            {                    
                                $_SESSION['section'] = 6;
                                echo "<script>alert('Ano inválido! Redigite.');</script>";
                            }
                       }
                       else{
                           $_SESSION['section'] = 6;
                           echo "<script>alert('CPF inválido! Redigite.');</script>";
                       }
                   }         
                   else
                   {
                       $_SESSION['section'] = 6;    
                       echo "<script>alert('O CPF digitado já foi cadastrado! Redigite.');</script>";                        
                   }
                }
                else
                {            
                   $_SESSION['section'] = 6;    
                   echo "<script>alert('O RG digitado já foi cadastrado! Redigite.');</script>";
                }
            }
            else {
                $_SESSION['section'] = 0;
            }       

            if($_SESSION['section'] == 6){
                $form6="
                    <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                        <form action='?key=".$_GET['key']."&id_coordinator=".$idCoordinator."' method='POST' id='f6'>
                            <h2 class='text-white'>Dados do Aluno
                                <button type='button' class='btn btn-light' data-toggle='tooltip' data-placement='right' title='Editar' onclick='edit1()'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-pencil-fill' viewBox='0 0 16 16'>
                                        <path d='M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z'/>
                                    </svg>
                                </button>
                            </h2>
                            <div class='row'>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='name-student' class='lead fw-normal' style='color: white;'>Nome:</label>
                                    <input type='text' pattern='[A-Za-z]{\b}{1,40}' maxlenght='40' id='name-student' class='form-control' name='name-student' value='".$_SESSION['name-student']."' disabled><br>
                                    <label for='email-student' class='lead fw-normal' style='color: white;'>E-mail:</label>
                                    <input type='email' maxlenght='50' id='email-student' class='form-control' name='email-student' value='".$_SESSION['email-student']."' disabled><br>
                                    <label for='telephone-student' class='lead fw-normal' style='color: white;'>Telefone:</label>
                                    <input type='text' id='telephone-student' class='form-control' name='telephone-student' value='".$_SESSION['telephone-student']."' disabled><br>
                                    <label for='cpf-student' class='lead fw-normal' style='color: white;'>CPF:</label>
                                    <input type='text' id='cpf-student' class='form-control' name='cpf-student' value='".$_SESSION['cpf-student']."' disabled><br>
                                    <label for='rg-student' class='lead fw-normal' style='color: white;'>RG:</label>
                                    <input type='text' id='rg-student' class='form-control' name='rg-student' value='".$_SESSION['rg-student']."' disabled><br>
                                    <label for='birthday-person' class='lead fw-normal' style='color: white;'>Data de nascimento:</label>
                                    <input type='date' id='birthday-person' class='form-control'  name='birthday-person' value='".$_SESSION['birthday-person']."' disabled><br>

                                    <label for='cep-student' class='lead fw-normal' style='color: white;'>CEP:</label>  
                                    <input type='text' id='cep-student' class='form-control' name='cep-student' value='".$_SESSION['cep-student']."'  onblur='searchCep(this.value);' disabled><br>
                                    
                                    <label for='address-student' class='lead fw-normal' style='color: white;'>Endereço:</label>  
                                    <input type='text' id='address-student' class='form-control' name='address-student' value='".$_SESSION['address-student']."' disabled><br>
                                
                                    <label for='district-student' class='lead fw-normal' style='color: white;'>Bairro:</label>
                                    <input type='text' id='district-student' class='form-control'  name='district-student' value='".$_SESSION['district-student']."' disabled><br>

                                    <label for='complement-student' class='lead fw-normal' style='color: white;'>Complemento:</label>  
                                    <input type='text' id='complement-student' class='form-control' name='complement-student' value='".$_SESSION['complement-student']."' disabled><br>
                                    

                                    
                                   
                                </div>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='treatment-student' class='lead fw-normal' style='color: white;'>Tratamento:</label>
                                    <input type='text' id='treatment-student' class='form-control' placeholder='Ex: Doutor' name='treatment-student' value='".$_SESSION['treatment-student']."' disabled><br>
                                    <label for='ra-student' class='lead fw-normal' style='color: white;'>RA:</label>  
                                    <input type='text' pattern='[0-9]{7}' id='ra-student' class='form-control' name='ra-student' value='".$_SESSION['ra-student']."' disabled><br>
                                    <label for='course-code-student' class='lead fw-normal' style='color: white;'>Código do curso:</label>
                                    <input type='text' id='course-code-student' class='form-control'  name='course-code-student' value='".$_SESSION['course-code-student']."' disabled><br>
                                    <label for='business-sector-student' class='lead fw-normal' style='color: white;'>Curso atuante:</label>
                                    <input type='text' id='business-sector-student' class='form-control' name='business-sector-student' value='".$_SESSION['business-sector-student']."' disabled><br>
                                    <label for='period-student' class='lead fw-normal' style='color: white;'>Período:</label>
                                    <input type='text' id='period-student' class='form-control' name='period-student' value='".$_SESSION['period-student']."' disabled><br>
                                    <label for='year-entry-student' class='lead fw-normal' style='color: white;'>Ano de entrada no curso:</label>
                                    <input type='text' pattern='[0-9]{4}' id='year-entry-student' class='form-control' name='year-entry-student' value='".$_SESSION['year-entry-student']."' disabled><br>
                                    <label for='number-student' class='lead fw-normal' style='color: white;'>Número:</label>  
                                    <input type='text'  maxlength='10' id='number-student' class='form-control' name='number-student' value='".$_SESSION['number-student']."' disabled><br>

                                    <label for='city-student' class='lead fw-normal' style='color: white;'>Cidade:</label>
                                    <input type='text' id='city-student' class='form-control'  name='city-student' value='".$_SESSION['city-student']."' disabled><br>
                                


                                </div> 
                                <input type='button' class='btn btn-success col-lg-3 col-12 fw-bold' style='border:solid 1px black;' name='sub6' value='Confirmar' onclick='submitForm()'>
                            </div>
                            
                        </form>
                    </div> 
                ";
                echo $form6;

            }


            if($_SESSION['section'] == 4){
                $form4="
                    <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                        <form action='?key=".$_GET['key']."&id_coordinator=".$idCoordinator."' method='POST' id='f4'>
                        <form action='?key=".$_GET['key']."' method='POST' id='f4'>
                            <h2 class='text-white'>Cadastro - Aluno</h2>

                            <div class='row'>
                             
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='cep-student' class='lead fw-normal' style='color: white;'>CEP *</label>  
                                    <input type='text' id='cep-student' class='form-control' name='cep-student' value='".$_SESSION['cep-student']."'  onblur='searchCep(this.value);' required><br>
                                    
                                    <label for='address-student' class='lead fw-normal' style='color: white;'>Endereço *</label>  
                                    <input type='text' id='address-student' class='form-control' name='address-student' value='".$_SESSION['address-student']."' disabled required><br>
                                
                                    <label for='complement-student' class='lead fw-normal' style='color: white;'>Complemento:</label>  
                                    <input type='text' id='complement-student' class='form-control' name='complement-student' value='".$_SESSION['complement-student']."' disabled><br>
                                </div>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='number-student' class='lead fw-normal' style='color: white;'>Número *</label>  
                                    <input type='text'  maxlength='10' id='number-student' class='form-control' name='number-student' value='".$_SESSION['number-student']."' required><br>

                                    <label for='city-student' class='lead fw-normal' style='color: white;'>Cidade *</label>
                                    <input type='text' id='city-student' class='form-control'  name='city-student' value='".$_SESSION['city-student']."' disabled required><br>
                                
                                    <label for='district-student' class='lead fw-normal' style='color: white;'>Bairro *</label>
                                    <input type='text' id='district-student' class='form-control'  name='district-student' value='".$_SESSION['district-student']."' disabled required><br>
                                   
                                </div>
                            </div>
                            <input type='button' name='sub2' onclick='sub03()' class='btn btn-dark fw-bold' value='Voltar'>
                            <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' name='sub4' onclick='submitForm4()' value='Enviar'>
                        </form> 
                    </div>
                ";
                echo $form4;               
            } 




            if($_SESSION['section'] == 3){
                $form3="
                    <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                        <form action='?key=".$_GET['key']."&id_coordinator=".$idCoordinator."' method='POST' id='f3'>
                            <h2 class='text-white'>Cadastro - Aluno</h2>
                            <label for='ra-student' class='lead fw-normal' style='color: white;'>RA *</label>  
                            <input type='text' pattern='[0-9]{7}' id='ra-student' class='form-control' name='ra-student' value='".$_SESSION['ra-student']."' required><br>
                            <label for='course-code-student' class='lead fw-normal' style='color: white;'>Código do curso *</label>
                            <input type='text' id='course-code-student' class='form-control'  name='course-code-student' value='".$_SESSION['course-code-student']."' required><br>

                            <label for='business-sector-student' class='lead fw-normal' style='color: white;'>Curso atuante *</label>
                            <select class='form-control' name='business-sector-student' id='business-sector-student' required>
                                            <option selected value='".$_SESSION['business-sector-student']."'>".$_SESSION['business-sector-student']."</option>
                                            <option value='Info'>Informática</option>
                                            <option value='Eletro'>Eletrônica</option>
                                            <option value='Mec'>Mecânica</option>
                                            <option value='Adm'>Administração</option>
                                            <option value='Log'>Logística</option>
                                            <option value='RH'>Recursos Humanos</option>
                            </select> <br>

                            <label for='period-student' class='lead fw-normal' style='color: white;'>Período *</label>
                            <select class='form-control' name='period-student' id='period-student' required>
                                            <option selected value='".$_SESSION['period-student']."'>".$_SESSION['period-student']."</option>
                                            <option value='Diurno'>Diurno</option>
                                            <option value='Noturno'>Noturno</option>
                            </select> <br>

                            <label for='year-entry-student' class='lead fw-normal' style='color: white;'>Ano de entrada no curso *</label>
                            <input type='text' pattern='[0-9]{4}' id='year-entry-student' class='form-control' name='year-entry-student' value='".$_SESSION['year-entry-student']."' required><br>
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
                        <form action='?key=".$_GET['key']."&id_coordinator=".$idCoordinator."' method='POST' id='f2'>
                            <h2 class='text-white'>Cadastro - Aluno</h2>
                            <div class='row'>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='telephone-student' class='lead fw-normal' style='color: white;'>Telefone *</label>  
                                    <input type='text' id='telephone-student' class='form-control' name='telephone-student' value='".$_SESSION['telephone-student']."' required><br>
                                    <label for='cpf-student' class='lead fw-normal' style='color: white;'>CPF *</label>
                                    <input type='text' id='cpf-student' class='form-control'  name='cpf-student' value='".$_SESSION['cpf-student']."' required><br>
                                    <label for='birthday-person' class='lead fw-normal' style='color: white;'>Data de nascimento *</label>
                                    <input type='date' id='birthday-person' class='form-control'  name='birthday-person' value='".$_SESSION['birthday-person']."' required><br>
                                </div>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='rg-student' class='lead fw-normal' style='color: white;'>RG *</label>
                                    <input type='text' id='rg-student' class='form-control' name='rg-student' value='".$_SESSION['rg-student']."' required><br>
                                    <label for='treatment-student' class='lead fw-normal' style='color: white;'>Tratamento *</label>
                                        <select class='form-control' name='treatment-student' id='treatment-student' required>
                                            <option selected value='".$_SESSION['treatment-student']."'>".$_SESSION['treatment-student']."</option>
                                            <option value='Sr'>Senhor</option>
                                            <option value='Sra'>Senhora</option>
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
                        <form action='?key=".$_GET['key']."&id_coordinator=".$idCoordinator."' method='POST'>
                            <h2 class='text-white'>Cadastro - Aluno</h2>
                            <label for='name-student' class='lead fw-normal' style='color: white;'>Nome:</label>
                            <input pattern='[A-Za-z]{\b}{1,40}'  maxlenght='40' type='text' id='name-student' class='form-control' name='name-student' value='".$_SESSION['name-student']."' disabled><br>
                            <label for='email-student' class='lead fw-normal' style='color: white;'>E-mail:</label>
                            <input type='email' id='email-student' maxlenght='50' class='form-control' name='email-student' value='".$_SESSION['email-student']."' disabled><br>
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
                                    data-login_uri='http://localhost/UNESP-Internship/TUC21/app/php/google/verifyStudentEmail.php?key=".$_GET['key']."&id_coordinator=".$idCoordinator."'
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
