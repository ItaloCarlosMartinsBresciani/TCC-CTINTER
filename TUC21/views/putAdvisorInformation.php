<?php

use Google\Service\Script;

session_start();

    require_once('../app/php/functions.php');
    require_once('../app/db/connect.php');
     
    if(isset($_GET['id_advisor'])){  //ema    <-- o ema sagrado parte 2: o inimigo agora é outro
        $idProfessor = $_GET['id_advisor'];
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
    <title>Cadastro - Orientador</title>

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

        function edit1(){
            $('[name=cic-advisor]').removeAttr('disabled');
            $('[name=department-advisor]').removeAttr('disabled');            
        };

        function submitForm(){
            $('[name=cic-advisor]').removeAttr('disabled');
            $('[name=department-advisor]').removeAttr('disabled');   

            $('[name=cic-advisor]').attr('required');
            $('[name=department-advisor]').attr('required');
            
            $('form').append('<input type="hidden" name="sub6" />');
            $('form').submit();
        };
        
        /*Máscaras - inputs */
        jQuery(function($){
            $("#cic-advisor").mask("000.000.000-00");            
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

            function verifyCIC( $cicadvisor )
            {
                /*$cicadvisor = "$cicadvisor";*/
                if (strpos($cicadvisor, "-") !== false)
                {
                    $cicadvisor = str_replace("-", "", $cicadvisor);
                }
                if (strpos($cicadvisor, ".") !== false)
                {
                    $cicadvisor = str_replace(".", "", $cicadvisor);
                }
                $sum = 0;
                $cicadvisor = str_split( $cicadvisor );
                $cictrueverifier = array();
                $cicnumbers = array_splice( $cicadvisor , 0, 9 );
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
                if ( $cicadvisor == $cictrueverifier )
                {
                    $returner = true;
                }


                $cicver = array_merge($cicnumbers, $cicadvisor);

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

            $inf[] = "cic-advisor";
            $inf[] = "department-advisor";

            for ($i=0; $i<=1; $i++)
            {
                if(!isset($_POST[$inf[$i]])){
                    if(!isset($_SESSION[$inf[$i]])){
                        $_SESSION[$inf[$i]] = '';
                    }
                }else{
                    $_SESSION[$inf[$i]] = $_POST[$inf[$i]];  
                }
            }
            
            if(isset($_POST['sub2'])){

                $query = "SELECT * from person WHERE cpf_person = :cic_advisor and id_person = :id_advisor";
    
                $stmt = $conn->prepare($query);

                $stmt->bindValue(':id_advisor', $idProfessor);
                $stmt->bindValue(':cic_advisor', $_SESSION['cic-advisor']);

                $stmt->execute();

                $return = $stmt->fetchAll(PDO::FETCH_ASSOC);      

                if(!verifyCIC($_SESSION['cic-advisor']))
                {
                    $_SESSION['section'] = 0;
                    echo "<script>alert('CIC inválido! Redigite.');</script>";
                }
                else if(!$return)
                {
                    $_SESSION['section'] = 0;
                    echo "<script>alert('O CIC não corresponde ao CPF cadastrado! Redigite.');</script>";
                }
                else
                {
                    $_SESSION['section'] = 6;
                }
            }
            else if(isset($_POST['sub6'])){

                $query = "SELECT * from person WHERE cpf_person = :cic_advisor and id_person = :id_advisor";
    
                $stmt = $conn->prepare($query);

                $stmt->bindValue(':id_advisor', $idProfessor);
                $stmt->bindValue(':cic_advisor', $_SESSION['cic-advisor']);

                $stmt->execute();

                $return = $stmt->fetchAll(PDO::FETCH_ASSOC);    

                if(verifyCIC($_SESSION['cic-advisor']) && $return)
                {
                    echo "<script>location= 'http://localhost/UNESP-Internship/TUC21/app/php/registerAdvisorLogic.php?id_advisor=".$idProfessor."';</script>"; 
                    exit(); 
                }         
                else
                {                    
                    echo "<script>alert('CIC inválido ou não correspondente ao CPF! Redigite.');</script>";
                }       
            }
            else {
                $_SESSION['section'] = 0;
            }

            if($_SESSION['section'] == 6){
                $form6="
                    <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                        <form action='?id_advisor=".$idProfessor."' method='POST' id='f6'>
                            <h2 class='text-white'>Dados do Orientador
                                <button type='button' class='btn btn-light' data-toggle='tooltip' data-placement='right' title='Editar' onclick='edit1()'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-pencil-fill' viewBox='0 0 16 16'>
                                        <path d='M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z'/>
                                    </svg>
                                </button>
                            </h2>
                            <label for='cic-advisor' class='lead fw-normal' style='color: white;'>CIC:</label>  
                            <input type='text' id='cic-advisor' class='form-control' name='cic-advisor' value='".$_SESSION['cic-advisor']."' disabled><br>
                            
                            <label for='department-advisor' class='lead fw-normal' style='color: white;'>Departamento:</label>
                            <input type='text' id='department-advisor' class='form-control' maxlength='50'  name='department-advisor' value='".$_SESSION['department-advisor']."' disabled><br>
                            
                            <input type='button' class='btn btn-success col-lg-3 col-12 fw-bold' style='border:solid 1px black;' name='sub6' value='Confirmar' onclick='submitForm()'>
                        </form>
                    </div> 
                ";
                echo $form6;

            }

            if($_SESSION['section'] == 0){
                $form0 = "
                            <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                                <form action='?id_advisor=".$idProfessor."' method='POST' id='f2'>
                                    <h2 class='text-white'>Cadastro - Orientador</h2>
                                    <label for='cic-advisor' class='lead fw-normal' style='color: white;'>CIC *</label>  
                                    <input type='text' id='cic-advisor' class='form-control' name='cic-advisor' value='".$_SESSION['cic-advisor']."' required><br>
                                    <label for='department-advisor' class='lead fw-normal' style='color: white;'>Departamento *</label>
                                    <input type='text' id='department-advisor' class='form-control'  maxlength='50' name='department-advisor' value='".$_SESSION['department-advisor']."' required><br>
                                
                                    <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' name='sub2' value='Enviar'>
                                </form> 
                            </div>
                        ";
                echo $form0;
            }

        ?>

        <footer class="text-center pt-5 pb-3">
             &copy; 2022 CTI - Colégio Técnico Industrial "Prof. Isaac Portal Roldán"
        </footer>
    
    </div>
        
</body>
</html>
