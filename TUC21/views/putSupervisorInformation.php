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

    if(isset($_GET['id_company'])){  //ema    <-- o ema sagrado parte 2: o inimigo agora é outro
        $idCompany = $_GET['id_company'];
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
    <title>Cadastro - Supervisor</title>

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
            $('[name=telephone-professor]').removeAttr('required');
            $('#f2').append('<input type="hidden" name="sub0" />');
            $('#f2').submit();            
        };

        function sub02(){
            $('[name=role-professor]').removeAttr('required');
            $('[name=cic-professor]').removeAttr('required');
            $('#f3').append('<input type="hidden" name="sub1" />');
            $('#f3').submit();            
        };

        function edit1(){
            $('[name=name-supervisor]').removeAttr('disabled');
            $('[name=email-supervisor]').removeAttr('disabled');  
            $('[name=telephone-supervisor]').removeAttr('disabled'); 
            $('[name=cpf-supervisor]').removeAttr('disabled');
            $('[name=rg-supervisor]').removeAttr('disabled'); 
            $('[name=treatment-supervisor]').removeAttr('disabled');
            $('[name=role-supervisor]').removeAttr('disabled'); 
            $('[name=cic-supervisor]').removeAttr('disabled');          
        };

        function submitForm(){
            $('[name=name-supervisor]').removeAttr('disabled');
            $('[name=email-supervisor]').removeAttr('disabled');  
            $('[name=telephone-supervisor]').removeAttr('disabled'); 
            $('[name=cpf-supervisor]').removeAttr('disabled');
            $('[name=rg-supervisor]').removeAttr('disabled'); 
            $('[name=treatment-supervisor]').removeAttr('disabled');
            $('[name=role-supervisor]').removeAttr('disabled'); 
            $('[name=cic-supervisor]').removeAttr('disabled');  

            $('[name=name-supervisor]').attr('required');
            $('[name=email-supervisor]').attr('required');  
            $('[name=telephone-supervisor]').attr('required'); 
            $('[name=cpf-supervisor]').attr('required');
            $('[name=rg-supervisor]').attr('required'); 
            $('[name=treatment-supervisor]').attr('required');
            $('[name=role-supervisor]').attr('required'); 
            $('[name=cic-supervisor]').attr('required'); 
            
            $('form').append('<input type="hidden" name="sub6" />');
            $('form').submit();
        };
        
        /*Máscaras - inputs */
        jQuery(function($){
            $("#telephone-supervisor").mask("(00)00000-0000"); 
            $("#rg-supervisor").mask("00.000.000-0");          
            $("#cic-supervisor").mask("000.000.000-00");     
            $("#cpf-supervisor").mask("000.000.000-00");     
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
function verifyCIC( $cicsupervisor )
            {
                /*$cicsupervisor = "$cicsupervisor";*/
                if (strpos($cicsupervisor, "-") !== false)
                {
                    $cicsupervisor = str_replace("-", "", $cicsupervisor);
                }
                if (strpos($cicsupervisor, ".") !== false)
                {
                    $cicsupervisor = str_replace(".", "", $cicsupervisor);
                }
                $sum = 0;
                $cicsupervisor = str_split( $cicsupervisor );
                $cictrueverifier = array();
                $cicnumbers = array_splice( $cicsupervisor , 0, 9 );
                $cicdefault = array(10, 9, 8, 7, 6, 5, 4, 3, 2);
                for ( $i = 0; $i <= 8; $i++ )
                {
                    $sum += $cicnumbers[$i]*$cicdefault[$i];
                }
                $sumresult = $sum % 11;  
                if ( $sumresult < 2 )
                {
                    $cictrueverifier[0] = 0;
                }
                else
                {
                    $cictrueverifier[0] = 11-$sumresult;
                }
                $sum = 0;
                $cicdefault = array(11, 10, 9, 8, 7, 6, 5, 4, 3, 2);
                $cicnumbers[9] = $cictrueverifier[0];
                for ( $i = 0; $i <= 9; $i++ )
                {
                    $sum += $cicnumbers[$i]*$cicdefault[$i];
                }
                $sumresult = $sum % 11;
                if ( $sumresult < 2 )
                {
                    $cictrueverifier[1] = 0;
                }
                else
                {
                    $cictrueverifier[1] = 11 - $sumresult;
                }
                $returner = false;
                if ( $cicsupervisor == $cictrueverifier )
                {
                    $returner = true;
                }


                $cicver = array_merge($cicnumbers, $cicsupervisor);

                if ( count(array_unique($cicver)) == 1 || $cicver == array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0) )

                {

                    $returner = false;

                }
                return $returner;
            }
            

            


            if(isset($_SESSION['done'])){
                if($_SESSION['done']){ //se a sessão estiver setada com 1
                    $_POST['sub0'] = 1;
                    $_SESSION['done'] = 0;
                }
            }

            
            $inf[] = "name-supervisor";
            $inf[] = "email-supervisor";
            $inf[] = "telephone-supervisor";
            $inf[] = "cpf-supervisor";
            $inf[] = "rg-supervisor";
            $inf[] = "treatment-supervisor";
            $inf[] = "cic-supervisor";
            $inf[] = "role-supervisor";

            for ($i=0; $i<=7; $i++)
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
                 $query = "SELECT * from person WHERE cpf_person = :cpf_supervisor";
    
                 $stmt = $conn->prepare($query);
 
                 $stmt->bindValue(':cpf_supervisor', $_SESSION['cpf-supervisor']);
 
                 $stmt->execute();
 
                 $return_cpf = $stmt->fetchAll(PDO::FETCH_ASSOC);    
 
                 //Consistência rg
                 $query = "SELECT * from person WHERE rg_person = :rg_supervisor";
     
                 $stmt = $conn->prepare($query);
 
                 $stmt->bindValue(':rg_supervisor', $_SESSION['rg-supervisor']);
 
                 $stmt->execute();
 
                 $return_rg = $stmt->fetchAll(PDO::FETCH_ASSOC); 

                 if($return_rg == null)
                {
                    if ($return_cpf == null)
                    {
                        if(verifyCIC($_SESSION['cpf-supervisor']))
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
            else if(isset($_POST['sub3'])){   

                if(!verifyCIC($_SESSION['cic-supervisor']))
                {
                    $_SESSION['section'] = 3;
                    echo "<script>alert('CIC inválido! Redigite.');</script>";
                }
                else if($_SESSION["cpf-supervisor"] != $_SESSION['cic-supervisor'])
                {
                    $_SESSION['section'] = 3;
                    echo "<script>alert('O CIC não corresponde ao CPF digitado anteriormente! Redigite.');</script>";
                }
                else
                {
                    $_SESSION['section'] = 6;
                }
            }
            else if(isset($_POST['sub6'])){
                //Consistência cpf
                $query = "SELECT * from person WHERE cpf_person = :cpf_supervisor";
    
                $stmt = $conn->prepare($query);

                $stmt->bindValue(':cpf_supervisor', $_SESSION['cpf-supervisor']);

                $stmt->execute();

                $return_cpf = $stmt->fetchAll(PDO::FETCH_ASSOC);    

                //Consistência rg
                $query = "SELECT * from person WHERE rg_person = :rg_supervisor";
    
                $stmt = $conn->prepare($query);

                $stmt->bindValue(':rg_supervisor', $_SESSION['rg-supervisor']);

                $stmt->execute();

                $return_rg = $stmt->fetchAll(PDO::FETCH_ASSOC); 

                if (!$return_rg)
                {
                    if(verifyCIC($_SESSION['cic-supervisor']) && verifyCIC($_SESSION["cpf-supervisor"]))
                    {
                        if($_SESSION["cpf-supervisor"] == $_SESSION['cic-supervisor'])
                        {
                            if(!$return_cpf)
                            {
                                echo "<script>location= 'http://localhost/UNESP-Internship/TUC21/app/php/registerSupervisorLogic.php?key=".$_GET['key']."&id_company=".$idCompany."';</script>"; 
                                exit(); 
                            }
                            else
                            {                    
                                echo "<script>alert('O CPF digitado já foi cadastrado! Redigite.');</script>";
                            }  
                        }
                        else
                        {                    
                            echo "<script>alert('O CIC não corresponde ao CPF digitado! Redigite.');</script>";
                        }  
                    }         
                    else
                    {                    
                        echo "<script>alert('CIC e CPF inválidos! Redigite.');</script>";
                    }  
                } 
                else
                {                    
                    echo "<script>alert('O RG digitado já foi cadastrado! Redigite.');</script>";
                }      
            }
            else {
                $_SESSION['section'] = 0;
            }       

            if($_SESSION['section'] == 6){
                $form6="
                    <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                        <form action='?key=".$_GET['key']."&id_company=".$idCompany."'' method='POST' id='f6'>
                            <h2 class='text-white'>Dados do Supervisor
                                <button type='button' class='btn btn-light' data-toggle='tooltip' data-placement='right' title='Editar' onclick='edit1()'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-pencil-fill' viewBox='0 0 16 16'>
                                        <path d='M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z'/>
                                    </svg>
                                </button>
                            </h2>
                            <div class='row'>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='name-supervisor' class='lead fw-normal' style='color: white;'>Nome:</label>
                                    <input pattern='[A-Za-z]{\b}{1,40}'  maxlenght='40' type='text' id='name-supervisor' class='form-control' name='name-supervisor' value='".$_SESSION['name-supervisor']."' disabled><br>

                                    <label for='email-supervisor' class='lead fw-normal' style='color: white;'>E-mail:</label>
                                    <input type='email' id='email-supervisor' maxlenght='50' class='form-control' name='email-supervisor' value='".$_SESSION['email-supervisor']."' disabled><br>

                                    <label for='telephone-supervisor' class='lead fw-normal' style='color: white;'>Telefone:</label>  
                                    <input type='text' id='telephone-supervisor' class='form-control' name='telephone-supervisor' value='".$_SESSION['telephone-supervisor']."' disabled><br>

                                    <label for='cpf-supervisor' class='lead fw-normal' style='color: white;'>CPF:</label>
                                    <input type='text' id='cpf-supervisor' class='form-control'  name='cpf-supervisor' value='".$_SESSION['cpf-supervisor']."' disabled><br>
                                    
                                </div> 
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='rg-supervisor' class='lead fw-normal' style='color: white;'>RG:</label>
                                    <input type='text' id='rg-supervisor' class='form-control' name='rg-supervisor' value='".$_SESSION['rg-supervisor']."' disabled><br>

                                    <label for='treatment-supervisor' class='lead fw-normal' style='color: white;'>Tratamento:</label>
                                        <select class='form-control' name='treatment-supervisor' id='treatment-supervisor' disabled>
                                            <option selected value='".$_SESSION['treatment-supervisor']."'>".$_SESSION['treatment-supervisor']."</option>
                                            <option value='Sr'>Senhor</option>
                                            <option value='Sra'>Senhora</option>
                                            <option value='Dr'>Doutor</option>
                                            <option value='Dra'>Doutora</option>
                                        </select> <br>
                                    
                                    <label for='role-supervisor' class='lead fw-normal' style='color: white;'>Cargo:</label>  
                                    <input type='text' id='role-supervisor' class='form-control' name='role-supervisor' value='".$_SESSION['role-supervisor']."' disabled><br>

                                    <label for='cic-supervisor' class='lead fw-normal' style='color: white;'>CIC:</label>  
                                    <input type='text' id='cic-supervisor' class='form-control' name='cic-supervisor' value='".$_SESSION['cic-supervisor']."' disabled><br>
                                </div> 
                            </div>       
                            <input type='button' class='btn btn-success col-lg-3 col-12 fw-bold' style='border:solid 1px black;' name='sub6' value='Confirmar' onclick='submitForm()'>
                        </form>
                    </div> 
                ";
                echo $form6;

            }

            if($_SESSION['section'] == 3){
                $form3 = "
                            <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                                <form action='?key=".$_GET['key']."&id_company=".$idCompany."' method='POST' id='f3'>
                                    <h2 class='text-white'>Cadastro - Supervisor</h2>
                                    <label for='role-supervisor' class='lead fw-normal' style='color: white;'>Cargo *</label>  
                                    <input type='text' id='role-supervisor' class='form-control' name='role-supervisor' value='".$_SESSION['role-supervisor']."' required><br>
                                    <label for='cic-supervisor' class='lead fw-normal' style='color: white;'>CIC *</label>  
                                    <input type='text' id='cic-supervisor' class='form-control' name='cic-supervisor' value='".$_SESSION['cic-supervisor']."' required><br>
                                    
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
                        <form action='?key=".$_GET['key']."&id_company=".$idCompany."' method='POST' id='f2'>
                            <h2 class='text-white'>Cadastro - Supervisor</h2>
                            <div class='row'>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='telephone-supervisor' class='lead fw-normal' style='color: white;'>Telefone *</label>  
                                    <input type='text' id='telephone-supervisor' class='form-control' name='telephone-supervisor' value='".$_SESSION['telephone-supervisor']."' required><br>
                                    <label for='cpf-supervisor' class='lead fw-normal' style='color: white;'>CPF *</label>
                                    <input type='text' id='cpf-supervisor' class='form-control'  name='cpf-supervisor' value='".$_SESSION['cpf-supervisor']."' required><br>
                                </div>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='rg-supervisor' class='lead fw-normal' style='color: white;'>RG *</label>
                                    <input type='text' id='rg-supervisor' class='form-control' name='rg-supervisor' value='".$_SESSION['rg-supervisor']."' required><br>
                                    <label for='treatment-supervisor' class='lead fw-normal' style='color: white;'>Tratamento *</label>
                                        <select class='form-control' name='treatment-supervisor' id='treatment-supervisor' required>
                                            <option selected value='".$_SESSION['treatment-supervisor']."'>".$_SESSION['treatment-supervisor']."</option>
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
                        <form action='?key=".$_GET['key']."&id_company=".$idCompany."' method='POST'>
                            <h2 class='text-white'>Cadastro - Supervisor</h2>
                            <label for='name-supervisor' class='lead fw-normal' style='color: white;'>Nome:</label>
                            <input pattern='[A-Za-z]{\b}{1,40}'  maxlenght='40' type='text' id='name-supervisor' class='form-control' name='name-supervisor' value='".$_SESSION['name-supervisor']."' disabled><br>
                            <label for='email-supervisor' class='lead fw-normal' style='color: white;'>E-mail:</label>
                            <input type='email' id='email-supervisor' maxlenght='50' class='form-control' name='email-supervisor' value='".$_SESSION['email-supervisor']."' disabled><br>
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
                                    data-login_uri='http://localhost/UNESP-Internship/TUC21/app/php/google/verifySupervisorEmail.php?key=".$_GET['key']."&id_company=".$idCompany."'
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
        

    <!--CREATE TABLE supervisor (
        fk_id BIGINT NOT NULL UNIQUE, 

        role_supervisor VARCHAR(30) NOT NULL, 
        cic_supervisor CHAR(25) NOT NULL UNIQUE,
        deleted BOOLEAN DEFAULT FALSE,
        valid BOOLEAN DEFAULT FALSE,
        active BOOLEAN DEFAULT FALSE,

        FOREIGN KEY (fk_id) REFERENCES person(id_person),

        fk_company BIGINT NOT NULL,
        FOREIGN KEY (fk_company) REFERENCES company (id_company)
    ); 

/* Funcionário setor de estágio (Empresa e universidade) */

/* Funcionário Empresa*/

CREATE TABLE company_employee (
    fk_id BIGINT NOT NULL UNIQUE,


    role_company_employee VARCHAR(30) NOT NULL, 
    cic_company_employee CHAR(25) NOT NULL UNIQUE,
    deleted BOOLEAN DEFAULT FALSE, 
    valid BOOLEAN DEFAULT FALSE,
    active BOOLEAN DEFAULT FALSE,
    valid_company_employee BOOLEAN DEFAULT FALSE,
    
    FOREIGN KEY (fk_id) REFERENCES person(id_person),

    fk_company BIGINT NOT NULL,
    FOREIGN KEY (fk_company) REFERENCES company (id_company)
);-->


    
</body>
</html>