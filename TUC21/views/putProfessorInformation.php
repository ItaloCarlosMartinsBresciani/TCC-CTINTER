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
     
    if(isset($_GET['id_principal'])){  //ema    <-- o ema sagrado parte 2: o inimigo agora é outro
        $idPrincipal = $_GET['id_principal'];
    }else{
        exit();
    }
    
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Professor</title>

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
            $('[name=treatment-professor]').removeAttr('required');
            $('[name=rg-professor]').removeAttr('required');
            $('[name=cpf-professor]').removeAttr('required');
            $('[name=business-sector-professor]').attr('required');
            $('[name=telephone-professor]').removeAttr('required');
            $('#f2').append('<input type="hidden" name="sub0" />');
            $('#f2').submit();            
        };
        
        
        function edit1(){
            $('[name=treatment-professor]').removeAttr('disabled');
            $('[name=rg-professor]').removeAttr('disabled');
            $('[name=cpf-professor]').removeAttr('disabled');
            $('[name=business-sector-professor]').attr('disabled');
            $('[name=telephone-professor]').removeAttr('disabled');
        };

        function submitForm(){
            $('[name=name-professor]').removeAttr('disabled');
            $('[name=treatment-professor]').removeAttr('disabled');
            $('[name=rg-professor]').removeAttr('disabled');
            $('[name=cpf-professor]').removeAttr('disabled');
            $('[name=business-sector-professor]').attr('required');
            $('[name=telephone-professor]').removeAttr('disabled');

            $('[name=name-professor]').attr('required');
            $('[name=treatment-professor]').attr('required');
            $('[name=rg-professor]').attr('required');
            $('[name=cpf-professor]').attr('required');
            $('[name=business-sector-professor]').attr('required');
            $('[name=telephone-professor]').attr('required');
            
            $('form').append('<input type="hidden" name="sub6" />');
            $('form').submit();
        };

        
//aaaaaaaaaaa
        
        /*Máscaras - inputs */
        jQuery(function($){
            $("#telephone-professor").mask("(00) 00000-0000");
            $("#rg-professor").mask("00.000.000-0");
            $("#cpf-professor").mask("000.000.000-00");
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

            function verifyCPF( $cpfprofessor )
            {
                /*$cpfprofessor = "$cpfprofessor";*/
                if (strpos($cpfprofessor, "-") !== false)
                {
                    $cpfprofessor = str_replace("-", "", $cpfprofessor);
                }
                if (strpos($cpfprofessor, ".") !== false)
                {
                    $cpfprofessor = str_replace(".", "", $cpfprofessor);
                }
                $sum = 0;
                $cpfprofessor = str_split( $cpfprofessor );
                $cpftrueverifier = array();
                $cpfnumbers = array_splice( $cpfprofessor , 0, 9 );
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
                if ( $cpfprofessor == $cpftrueverifier )
                {
                    $returner = true;
                }


                $cpfver = array_merge($cpfnumbers, $cpfprofessor);

                if ( count(array_unique($cpfver)) == 1 || $cpfver == array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0) )

                {

                    $returner = false;

                }
                return $returner;
            }



            $inf[] = "name-professor";
            $inf[] = "email-professor";
            $inf[] = "telephone-professor";
            $inf[] = "cpf-professor";
            $inf[] = "rg-professor";
            $inf[] = "treatment-professor";
            $inf[] = "business-sector-professor";

            for ($i=0; $i<=6; $i++)
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
                if(verifyCPF($_SESSION['cpf-professor']))
                {
                    $_SESSION['section'] = 6;
                }
                else{
                    echo "<script>alert('CPF inválido! Redigite.');</script>";
                    $_SESSION['section'] = 2;
                }
            }
            else if(isset($_POST['sub6'])){
                if(verifyCPF($_SESSION['cpf-professor']))
                {
                    echo "<script>location= 'http://localhost/UNESP-Internship/TUC21/app/php/registerProfessorLogic.php?key=".$key."&id_principal=".$idPrincipal."';</script>"; 
                    exit();
                }
                else{
                    echo "<script>alert('CPF inválido! Redigite.');</script>";
                    $_SESSION['section'] = 6;
                }
            }
            else {
                $_SESSION['section'] = 0;
            }       

            if($_SESSION['section'] == 6){
                $form6="
                    <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                        <form action='?key=".$_GET['key']."&id_principal=".$idPrincipal."' method='POST' id='f6'>
                            <h2 class='text-white'>Dados do Professor
                                <button type='button' class='btn btn-light' data-toggle='tooltip' data-placement='right' title='Editar' onclick='edit1()'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-pencil-fill' viewBox='0 0 16 16'>
                                        <path d='M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z'/>
                                    </svg>
                                </button>
                            </h2>
                            <div class='row'>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='name-professor' class='lead fw-normal' style='color: white;'>Nome:</label>
                                    <input type='text' pattern='[A-Za-z]{\b}{1,40}' maxlenght='40' id='name-professor' class='form-control' name='name-professor' value='".$_SESSION['name-professor']."' disabled><br>
                                    <label for='email-professor' class='lead fw-normal' style='color: white;'>E-mail:</label>
                                    <input type='email' maxlenght='50' id='email-professor' class='form-control' name='email-professor' value='".$_SESSION['email-professor']."' disabled><br>
                                    <label for='telephone-professor' class='lead fw-normal' style='color: white;'>Telefone:</label>
                                    <input type='text' id='telephone-professor' class='form-control' name='telephone-professor' value='".$_SESSION['telephone-professor']."' disabled><br>
                                    <label for='business-sector-professor' class='lead fw-normal' style='color: white;'>Curso atuante:</label>
                                    <input type='text' id='business-sector-professor' class='form-control' name='business-sector-professor' value='".$_SESSION['business-sector-professor']."' disabled><br>
                                </div>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='cpf-professor' class='lead fw-normal' style='color: white;'>CPF:</label>
                                    <input type='text' id='cpf-professor' class='form-control' name='cpf-professor' value='".$_SESSION['cpf-professor']."' disabled><br>
                                    <label for='rg-professor' class='lead fw-normal' style='color: white;'>RG:</label>
                                    <input type='text' id='rg-professor' class='form-control' name='rg-professor' value='".$_SESSION['rg-professor']."' disabled><br>
                                    <label for='treatment-professor' class='lead fw-normal' style='color: white;'>Tratamento:</label>
                                    <input type='text' id='treatment-professor' class='form-control' placeholder='Ex: Doutor' name='treatment-professor' value='".$_SESSION['treatment-professor']."' disabled><br>
                                </div> 
                                <input type='button' class='btn btn-success col-lg-3 col-12 fw-bold' style='border:solid 1px black;' name='sub6' value='Confirmar' onclick='submitForm()'>
                            </div>
                            
                        </form>
                    </div>
                ";
                echo $form6;

            }

            

            if($_SESSION['section'] == 2){
                $form2="
                    <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                        <form action='?key=".$_GET['key']."&id_principal=".$idPrincipal."' method='POST' id='f2'>
                            <h2 class='text-white'>Cadastro - Professor</h2>
                            <div class='row'>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='telephone-professor' class='lead fw-normal' style='color: white;'>Telefone *</label>  
                                    <input type='text' id='telephone-professor' class='form-control' name='telephone-professor' value='".$_SESSION['telephone-professor']."' required><br>
                                    <label for='cpf-professor' class='lead fw-normal' style='color: white;'>CPF *</label>
                                    <input type='text' id='cpf-professor' class='form-control'  name='cpf-professor' value='".$_SESSION['cpf-professor']."' required><br>
                                    <label for='business-sector-professor' class='lead fw-normal' style='color: white;'>Curso atuante *</label>
                                    <select class='form-control' name='business-sector-professor' id='business-sector-professor' required>
                                            <option selected value='".$_SESSION['business-sector-professor']."'>".$_SESSION['business-sector-professor']."</option>
                                            <option value='Info'>Informática</option>
                                            <option value='Eletro'>Eletrônica</option>
                                            <option value='Mec'>Mecânica</option>
                                            <option value='Adm'>Administração</option>
                                            <option value='Log'>Logística</option>
                                            <option value='RH'>Recursos Humanos</option>
                                    </select> <br>
                                   
                                </div>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='rg-professor' class='lead fw-normal' style='color: white;'>RG *</label>
                                    <input type='text' id='rg-professor' class='form-control' name='rg-professor' value='".$_SESSION['rg-professor']."' required><br>
                                    <label for='treatment-professor' class='lead fw-normal' style='color: white;'>Tratamento *</label>
                                        <select class='form-control' name='treatment-professor' id='treatment-professor' required>
                                            <option selected value='".$_SESSION['treatment-professor']."'>".$_SESSION['treatment-professor']."</option>
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
                        <form action='?key=".$_GET['key']."&id_principal=".$idPrincipal."' method='POST'>
                            <h2 class='text-white'>Cadastro - Professor</h2>
                            <label for='name-professor' class='lead fw-normal' style='color: white;'>Nome:</label>
                            <input pattern='[A-Za-z]{\b}{1,40}'  maxlenght='40' type='text' id='name-professor' class='form-control' name='name-professor' value='".$_SESSION['name-professor']."' disabled><br>
                            <label for='email-professor' class='lead fw-normal' style='color: white;'>E-mail:</label>
                            <input type='email' id='email-professor' maxlenght='50' class='form-control' name='email-professor' value='".$_SESSION['email-professor']."' disabled><br>
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
                                    data-login_uri='http://localhost/UNESP-Internship/TUC21/app/php/google/verifyProfessorEmail.php?key=".$_GET['key']."&id_principal=".$idPrincipal."'
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
