<?php

use Google\Service\Script;

session_start();

    require_once('../app/php/functions.php');
    require_once('../app/db/connect.php');
    
    if (isset($_GET['id_intern']) && isset($_GET['id_internship_data'])) {
        try {
        $id_intern_Hex = cleanString($_GET['id_intern']);
        $id_internship_Hex = cleanString($_GET['id_internship_data']);

          $id_intern = decodeId($id_intern_Hex);
          $id_internship = decodeId($id_internship_Hex);
        }
        catch (TypeError) {
          header('Location: ../../views/intern/internPage.php');
        }   
      } else {
      header('Location: ../../views/intern/internPage.php');
      }
    
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Informações de Estágio</title>

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
            $('[name=scholarship-internship-data]').removeAttr('required');
            //$('[name=scholarship-value-internship-data]').removeAttr('required');   
            $('[name=nature-internship-data]').removeAttr('required');
            //$('[name=description-internship-data]').removeAttr('required');
            $('#f2').append('<input type="hidden" name="sub1" />');
            $('#f2').submit();            
        };

        function sub02(){
            $('[name=description-internship-data]').removeAttr('required');
            $('#f3').append('<input type="hidden" name="sub2" />');
            $('#f3').submit();            
        };

        function sub03(){
            $('[name=scholarship-value-internship-data]').removeAttr('required');   
            $('[name=description-internship-data]').removeAttr('required');
            $('#f3').append('<input type="hidden" name="sub2" />');
            $('#f3').submit();            
        };

        function edit1(){
            $('[name=role_internship_data]').removeAttr('disabled');
            $('[name=area_internship_data]').removeAttr('disabled');  
            $('[name=week_hours_internship_data]').removeAttr('disabled');
            $('[name=daily_hours]').removeAttr('disabled');
            // $('[name=start_time_internship_data]').removeAttr('disabled'); 
            $('[name=start_date_internship_data]').removeAttr('disabled');
            $('[name=lunch_time]').removeAttr('disabled'); 
            $('[name=total_hours_internship_data]').removeAttr('disabled'); 
            // $('[name=end_time_internship_data]').removeAttr('disabled');
            $('[name=end_date_internship_data]').removeAttr('disabled'); 
            $('[name=scholarship_internship_data]').removeAttr('disabled');
            $('[name=scholarship_value_internship_data]').removeAttr('disabled');   
            $('[name=nature_internship_data]').removeAttr('disabled');
            $('[name=description_internship_data]').removeAttr('disabled');   
        };

        function submitForm(){
            $('[name=role_internship_data]').removeAttr('disabled');
            $('[name=area_internship_data]').removeAttr('disabled');  
            $('[name=week_hours_internship_data]').removeAttr('disabled');
            $('[name=daily_hours]').removeAttr('disabled');
            // $('[name=start_time_internship_data]').removeAttr('disabled'); 
            $('[name=lunch_time]').removeAttr('disabled'); 
            $('[name=start_date_internship_data]').removeAttr('disabled');
            $('[name=total_hours_internship_data]').removeAttr('disabled'); 
            // $('[name=end_time_internship_data]').removeAttr('disabled');
            $('[name=end_date_internship_data]').removeAttr('disabled'); 
            $('[name=scholarship_internship_data]').removeAttr('disabled');
            $('[name=scholarship_value_internship_data]').removeAttr('disabled');   
            $('[name=nature_internship_data]').removeAttr('disabled');
            $('[name=description_internship_data]').removeAttr('disabled');  
            
            $('[name=role_internship_data]').attr('required');
            $('[name=area_internship_data]').attr('required');  
            $('[name=week_hours_internship_data]').attr('required');
            $('[name=daily_hours]').attr('required');
            // $('[name=start_time_internship_data]').attr('required'); 
            $('[name=start_date_internship_data]').attr('required');
            $('[name=total_hours_internship_data]').attr('required');
            // $('[name=end_time_internship_data]').attr('required');
            $('[name=end_date_internship_data]').attr('required'); 
            $('[name=scholarship_internship_data]').attr('required');
            $('[name=scholarship_value_internship_data]').attr('required');   
            $('[name=nature_internship_data]').attr('required');
            $('[name=description_internship_data]').attr('required');
            $('[name=lunch_time]').attr('required');
            
            $('form').append('<input type="hidden" name="sub5" />');
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

            if(isset($_SESSION['done'])){
                if($_SESSION['done']){ //se a sessão estiver setada com 1
                    $_POST['sub0'] = 1;
                    $_SESSION['done'] = 0;
                }
            }

            $inf[] = "role_internship_data";
            $inf[] = "area_internship_data";
            $inf[] = "total_hours_internship_data";
            $inf[] = "week_hours_internship_data";
            $inf[] = "daily_hours";
            $inf[] = "lunch_time";
            $inf[] = "start_date_internship_data";
            $inf[] = "end_date_internship_data";
            // $inf[] = "start_time_internship_data";
            // $inf[] = "end_time_internship_data";
            $inf[] = "scholarship_internship_data";
            $inf[] = "scholarship_value_internship_data";
            $inf[] = "nature_internship_data";
            $inf[] = "description_internship_data";
            

            for ($i=0; $i<=11; $i++)
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
                $current_date = date("Y");
                $start_date = date_create($_SESSION['start_date_internship_data']);
                $end_date = date_create($_SESSION['end_date_internship_data']);


                // $h1 = new DateTime($_SESSION['start_time_internship_data']);
                // $h2 = new DateTime($_SESSION['end_time_internship_data']);
                // $h3 = new DateTime($_SESSION['lunch_time']);
                
                // $diff   =   $h2->diff($h1, true); 
                // $diff = $diff->format("%h:%i");
                // $h4 = new DateTime($diff);                
                // $diff2 = $h4->diff($h3, true);  
                // $diff2 = $diff2->format("%h%i");

               
                // if($_SESSION['start_time_internship_data'] >= $_SESSION['end_time_internship_data'])  
                // {
                //     echo "<script>alert('Horário de estágio inválido!');</script>";
                // }
                // else if(intval($diff2) > 600) 
                // {
                //     echo "<script>alert('Horário de estágio excede o máximo diário de 6 horas, descontando o horário de almoço!');</script>";
                // }
                if($_SESSION['start_date_internship_data'] >= $_SESSION['end_date_internship_data'])
                {
                    echo "<script>alert('Período de estágio inválido!');</script>";
                }
                else if(($_SESSION['end_date_internship_data'] - $_SESSION['start_date_internship_data']) > 2)
                {
                    echo "<script>alert('Período de estágio excede o máximo exigido por lei (2 anos)!');</script>";
                }
                else if(date_format($start_date, "Y") < $current_date - 2 || date_format($start_date,"%Y") > $current_date)
                {
                    echo "<script>alert('Período de estágio inválido!');</script>";
                }
                else
                {
                    $_SESSION['section'] = 1;
                }
                
            }
            else if(isset($_POST['sub3']))
            {
                $_SESSION['section'] = 2;
            }
            else if(isset($_POST['sub4'])){

                $_SESSION['section'] = 3;   
            }
            else if(isset($_POST['sub5'])){
                $current_date = date("Y");
                $start_date = date_create($_SESSION['start_date_internship_data']);
                $end_date = date_create($_SESSION['end_date_internship_data']);
                
                
                // $h1 = new DateTime($_SESSION['start_time_internship_data']);
                // $h2 = new DateTime($_SESSION['end_time_internship_data']);
                // $h3 = new DateTime($_SESSION['lunch_time']);
                
                // $diff = $h2->diff($h1, true); 
                // $diff = $diff->format("%h:%i");
                // $h4 = new DateTime($diff);                
                // $diff2 = $h4->diff($h3, true);  
                // $diff2 = $diff2->format("%h%i");

                // if($_SESSION['start_time_internship_data'] >= $_SESSION['end_time_internship_data'])  
                // {
                //     echo "<script>alert('Horário de estágio inválido!');</script>";
                // }
                // if(intval($diff2) > 600)  
                // {
                //     echo "<script>alert('Horário de estágio excede o máximo diário de 6 horas, descontando o horário de almoço!');</script>";
                // }
                if($_SESSION['start_date_internship_data'] >= $_SESSION['end_date_internship_data'])
                {
                    echo "<script>alert('Período de estágio inválido!');</script>";
                }
                else if(($_SESSION['end_date_internship_data'] - $_SESSION['start_date_internship_data']) > 2)
                {
                    echo "<script>alert('Período de estágio excede o máximo exigido por lei (2 anos)!');</script>";
                }
                else if(date_format($start_date, "Y") < $current_date - 2 || date_format($start_date,"%Y") > $current_date)
                {
                    echo "<script>alert('Período de estágio inválido!');</script>";
                }
                else
                {
                    echo "<script>location = '../app/php/registerInternshipDataLogic.php?id_intern=".$id_intern_Hex."&id_internship_data=".$id_internship_Hex."';</script>";
                    exit(); 
                    
                } 
            }
            else {
                $_SESSION['section'] = 0;
            }       

            if($_SESSION['section'] == 3){
                if ($_SESSION['scholarship_internship_data'] == "True")
                {
                    $radio_scholarship = "<input type='radio' name='scholarship_internship_data' id='scholarship-internship-data' value='True' style='font-size:40px;' checked disabled> <label style='color: white;'>Sim</label><br> 
                                          <input type='radio' name='scholarship_internship_data' id='scholarship-internship-data' value='False' disabled> <label style='color: white;'>Não</label><br>
                                          <br>";
                }
                else
                {
                    $radio_scholarship = "<input type='radio' name='scholarship_internship_data' id='scholarship-internship-data' value='True' style='font-size:40px;' disabled> <label style='color: white;'>Sim</label><br> 
                                          <input type='radio' name='scholarship_internship_data' id='scholarship-internship-data' value='False' checked disabled> <label style='color: white;'>Não</label><br>
                                          <br>";
                }

                if($_SESSION['nature_internship_data'] == "True")
                {
                    $radio_nature = "<input type='radio' name='nature_internship_data' id='nature-internship-data' value='True' style='font-size:40px;' checked disabled> <label style='color: white;'>Obrigatório</label><br> 
                                     <input type='radio' name='nature_internship_data' id='nature-internship-data' value='False' disabled> <label style='color: white;'>Não Obrigatório</label><br>
                                     <br>";
                }
                else
                {
                    $radio_nature = "<input type='radio' name='nature_internship_data' id='nature-internship-data' value='True' style='font-size:40px;' disabled> <label style='color: white;'>Obrigatório</label><br> 
                                            <input type='radio' name='nature_internship_data' id='nature-internship-data' value='False' checked disabled> <label style='color: white;'>Não Obrigatório</label><br>
                                            <br>";
                }
                $form3="
                    <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                        <form action='?id_intern=".$id_intern_Hex."&id_internship_data=".$id_internship_Hex."' method='POST' id='f6'>
                            <h2 class='text-white'>Dados do Estagiário
                                <button type='button' class='btn btn-light' data-toggle='tooltip' data-placement='right' title='Editar' onclick='edit1()'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-pencil-fill' viewBox='0 0 16 16'>
                                        <path d='M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z'/>
                                    </svg>
                                </button>
                            </h2>
                            
                            
                            <label for='role-internship-data' class='lead fw-normal' style='color: white;' >Função:</label>
                            <input type='text' id='role-internship-data' class='form-control' placeholder = 'Ex: Programador, Analista de Sistemas...' name='role_internship_data' value='".$_SESSION['role_internship_data']."' disabled><br>
                            
                            <label for='area-internship-data' class='lead fw-normal' style='color: white;' >Área de atuação:</label>  
                            <input type='text' id='area-internship-data' class='form-control' placeholder = 'Ex: Informática, Sistemas Elétricos...' name='area_internship_data' value='".$_SESSION['area_internship_data']."' disabled><br>
                            
                            <div class='row'>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='week-hours-internship-data' class='lead fw-normal' style='color: white;' >Horas semanais:</label>
                                    <input type='number' id='week-hours-internship-data' class='form-control'  name='week_hours_internship_data' min='0' max='30' value='".$_SESSION['week_hours_internship_data']."' disabled><br>
                                
                                    
                                    
                                    <label for='lunch_time' class='lead fw-normal' style='color: white;' >Horas de almoço:</label>  
                                    <input type='time' id='lunch_time' class='form-control' name='lunch_time' value='".$_SESSION['lunch_time']."' disabled><br>

                                   
                                    <label for='start-date-internship-data' class='lead fw-normal' style='color: white;' >Data de início:</label>  
                                    <input type='date' id='start-date-internship-data' class='form-control' name='start_date_internship_data' value='".$_SESSION['start_date_internship_data']."' disabled><br>
                                </div>
                                <div class='col-lg-6 col-sm-12'>
                                    <label for='daily_hours' class='lead fw-normal' style='color: white;' >Horas diárias:</label>
                                    <input type='number' id='daily_hours' class='form-control'  name='daily_hours' min='0' max='6' value='".$_SESSION['daily_hours']."' disabled><br>
                                
                                
                                    <label for='total-hours-internship-data' class='lead fw-normal' style='color: white;' >Total aproximado de horas de estágio:</label>  
                                    <input type='number' id='total-hours-internship-data' class='form-control' name='total_hours_internship_data'  max='2880' min='0' value='".$_SESSION['total_hours_internship_data']."' disabled><br>
                                
                                
                                    <label for='end-date-internship-data' class='lead fw-normal' style='color: white;' >Data de término:</label>  
                                    <input type='date' id='end-date-internship-data' class='form-control' name='end_date_internship_data' value='".$_SESSION['end_date_internship_data']."' disabled><br>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-lg-3 col-sm-12'>
                                
                                    <label for='scholarship-internship-data' class='lead fw-normal' style='color: white;' >Recebe bolsa?</label><br>
                                    ".$radio_scholarship."
                                    
                                    <label for='start-date-internship-data' class='lead fw-normal' style='color: white;' >Natureza do estágio:</label><br>
                                    ".$radio_nature."
                                </div>
                                <div class='col-lg-9 col-sm-12'>
                                    <label for='scholarship-value-internship-data' class='lead fw-normal' style='color: white;' >Valor da bolsa:</label>
                                    <input type='number' id='scholarship-value-internship-data' class='form-control'  name='scholarship_value_internship_data' value='".$_SESSION['scholarship_value_internship_data']."' disabled><br>
                                
                                    <label for='description-internship-data' class='lead fw-normal' style='color: white;' >Descrição das atividades:</label>
                                    <textarea id='description-internship-data' class='form-control'  name='description_internship_data' required style='height:150px;' disabled>".$_SESSION['description_internship_data']."</textarea><br>
                                </div> 
                            </div>

                            <input type='button' class='btn btn-success col-lg-3 col-12 fw-bold' style='border:solid 1px black;' name='sub5' value='Confirmar' onclick='submitForm()'>
                        </form>
                    </div> 
                ";
                echo $form3;

            }
            
            if($_SESSION['section'] == 2)
            {
                if ($_SESSION['scholarship_internship_data'] == "True")
                {
                    $form2 = "
                            <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                                <form action='?id_intern=".$id_intern_Hex."&id_internship_data=".$id_internship_Hex."' method='POST' id='f3'>
                                    <h2 class='text-white'>Cadastro - Estagiário</h2>
                                    <label for='scholarship-value-internship-data' class='lead fw-normal' style='color: white;' >Valor da bolsa *</label>
                                    <input type='number' id='scholarship-value-internship-data' class='form-control' min=0 name='scholarship_value_internship_data' value='".$_SESSION['scholarship_value_internship_data']."' required><br>
                                    <label for='description-internship-data' class='lead fw-normal' style='color: white;' >Descrição das atividades: (máximo 600 caracteres) *</label>
                                    <textarea id='description-internship-data' class='form-control' name='description_internship_data' maxlength='600' required style='height:159px;'>".$_SESSION['description_internship_data']."</textarea><br>
                                <input type='button' name='sub2' onclick='sub03()' class='btn btn-dark fw-bold' value='Voltar'>
                                <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' name='sub4' value='Enviar'>
                                </form> 
                            </div>
                        ";
                }
                else
                {
                    $form2 = "
                            <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                                <form action='?id_intern=".$id_intern_Hex."&id_internship_data=".$id_internship_Hex."' method='POST' id='f3'>
                                    <h2 class='text-white'>Cadastro - Estagiário</h2>
                                    <label for='description-internship-data' class='lead fw-normal' style='color: white;'>Descrição das atividades: (máximo 600 caracteres) *</label>
                                    <textarea id='description-internship-data' class='form-control' name='description_internship_data' maxlength='600' required style='height:159px;'>".$_SESSION['description_internship_data']."</textarea><br>
                                <input type='button' name='sub2' onclick='sub02()' class='btn btn-dark fw-bold' value='Voltar'>
                                <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' name='sub4' value='Enviar'>
                                </form> 
                            </div>
                        ";
                }
                
                echo $form2;
            }

            if($_SESSION['section'] == 1){
                
                if ($_SESSION['scholarship_internship_data'] == "True")
                {
                    $radio_scholarship = "<input type='radio' name='scholarship_internship_data' id='scholarship-internship-data' value='True' style='font-size:40px;' checked required> <label style='color: white;'>Sim</label><br> 
                                          <input type='radio' name='scholarship_internship_data' id='scholarship-internship-data' value='False' required> <label style='color: white;'>Não</label><br>
                                          <br>";
                }
                else if($_SESSION['scholarship_internship_data'] == "False")
                {
                    $radio_scholarship = "<input type='radio' name='scholarship_internship_data' id='scholarship-internship-data' value='True' style='font-size:40px;' required> <label style='color: white;'>Sim</label><br> 
                                          <input type='radio' name='scholarship_internship_data' id='scholarship-internship-data' value='False' checked required> <label style='color: white;'>Não</label><br>
                                          <br>";
                }
                else
                {
                    $radio_scholarship = "<input type='radio' name='scholarship_internship_data' id='scholarship-internship-data' value='True' required> <label style='color: white;'>Sim</label><br> 
                                          <input type='radio' name='scholarship_internship_data' id='scholarship-internship-data' value='False' style='color: white;' required> <label style='color: white;'>Não</label><br>
                                          <br>";
                }

                if($_SESSION['nature_internship_data'] == "True")
                {
                    $radio_nature = "<input type='radio' name='nature_internship_data' id='nature-internship-data' value='True' style='font-size:40px;' checked required> <label style='color: white;'>Obrigatório</label><br> 
                                     <input type='radio' name='nature_internship_data' id='nature-internship-data' value='False' required> <label style='color: white;'>Não Obrigatório</label><br>
                                     <br>";
                }
                else if($_SESSION['nature_internship_data'] == "False")
                {
                    $radio_nature = "<input type='radio' name='nature_internship_data' id='nature-internship-data' value='True' style='font-size:40px;' required> <label style='color: white;'>Obrigatório</label><br> 
                                     <input type='radio' name='nature_internship_data' id='nature-internship-data' value='False' checked required> <label style='color: white;'>Não Obrigatório</label><br>
                                     <br>";
                }
                else
                {
                    $radio_nature = "<input type='radio' name='nature_internship_data' id='nature-internship-data' value='True' style='font-size:40px;' required> <label style='color: white;'>Obrigatório</label><br> 
                                     <input type='radio' name='nature_internship_data' id='nature-internship-data' value='False' required> <label style='color: white;'>Não Obrigatório</label><br>
                                     <br>";
                }

                $form1 = "
                            <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                                <form action='?id_intern=".$id_intern_Hex."&id_internship_data=".$id_internship_Hex."' method='POST' id='f2'>
                                    <h2 class='text-white'>Cadastro - Estagiário</h2>

                                    <div class='row'>
                                        <div class='col-lg-6 col-sm-12'>    
                                            <label for='scholarship-internship-data' class='lead fw-normal' style='color: white;'>Recebe bolsa? *</label><br>
                                            ".$radio_scholarship."
                                        </div>
                                        <div class='col-lg-6 col-sm-12'>  
                                            <label for='start-date-internship-data' class='lead fw-normal' style='color: white;'>Natureza do estágio *</label><br>
                                            ".$radio_nature."
                                        </div>  
                                    </div>
                                <input type='button' name='sub1' onclick='sub01()' class='btn btn-dark fw-bold' value='Voltar'>
                                <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' name='sub3' value='Enviar'>
                                </form> 
                            </div>
                        ";
                echo $form1;
            }

            if($_SESSION['section'] == 0){
                $form0 = "
                            <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                                <form action='?id_intern=".$id_intern_Hex."&id_internship_data=".$id_internship_Hex."' method='POST' id='f1'>
                                    <h2 class='text-white'>Cadastro - Estagiário</h2>
                                    
                                    <label for='role-internship-data' class='lead fw-normal' style='color: white;'>Função *</label>
                                    <input type='text' id='role-internship-data' class='form-control' placeholder = 'Ex: Programador, Analista de Sistemas...' name='role_internship_data' value='".$_SESSION['role_internship_data']."' required><br>
                                    
                                    <label for='area-internship-data' class='lead fw-normal' style='color: white;'>Área de atuação *</label>  
                                    <input type='text' id='area-internship-data' class='form-control' placeholder = 'Ex: Informática, Sistemas Elétricos...' name='area_internship_data' value='".$_SESSION['area_internship_data']."' required><br>
                                    
                                    <div class='row'>
                                        <div class='col-lg-6 col-sm-12'>
                                            <label for='week-hours-internship-data' class='lead fw-normal' style='color: white;'>Horas semanais *</label>
                                            <input type='number' id='week-hours-internship-data' class='form-control'  name='week_hours_internship_data' min='0' max='30' value='".$_SESSION['week_hours_internship_data']."' required><br>
                                        
                                            
                                            <label for='lunch_time' class='lead fw-normal' style='color: white;'>Horas de almoço *</label>  
                                            <input type='time' id='lunch_time' class='form-control' name='lunch_time' value='".$_SESSION['lunch_time']."' required><br>
                                            
                                            
                                            <label for='start-date-internship-data' class='lead fw-normal' style='color: white;'>Data de início *</label>  
                                            <input type='date' id='start-date-internship-data' class='form-control' name='start_date_internship_data' value='".$_SESSION['start_date_internship_data']."' required><br>

                                            
                                          
                                        </div>
                                        <div class='col-lg-6 col-sm-12'>

                                            <label for='daily_hours' class='lead fw-normal' style='color: white;'>Horas diárias *</label>
                                            <input type='number' id='daily_hours' class='form-control'  name='daily_hours' min='0' max='6' value='".$_SESSION['daily_hours']."' required><br>

                                            <label for='total-hours-internship-data' class='lead fw-normal' style='color: white;'>Total aproximado de horas de estágio *</label>  
                                            <input type='number' id='total-hours-internship-data' class='form-control' max='2880' min='0' name='total_hours_internship_data' value='".$_SESSION['total_hours_internship_data']."' required><br>
                                        
                                            <label for='end-date-internship-data' class='lead fw-normal' style='color: white;'>Data de término *</label>  
                                            <input type='date' id='end-date-internship-data' class='form-control' name='end_date_internship_data' value='".$_SESSION['end_date_internship_data']."' required><br>
                                            
                                        
                                            
                                        </div>
                                    </div>
                                   
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
