<?php

    use Google\Service\Script;

    session_start();
    if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 1){
        echo "<script>alert('Usuário não reconhecido.');</script>";
        header("Location: ../../../index.php ");
        exit();
    }

    require_once('../app/php/functions.php');
    require_once('../app/db/connect.php');

    $query = 'SELECT ip.* FROM internship_plan ip, internship_data i WHERE i.id_internship_data = ip.fk_internship_data AND i.fk_student = :idUser AND denied_internship_plan = FALSE';
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':idUser', $_SESSION["idUser"]);
    $stmt->execute();        
    $return_internship = $stmt->fetch(PDO::FETCH_ASSOC); 

    $query = 'SELECT * FROM student WHERE fk_id = :idUser';
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':idUser', $_SESSION["idUser"]);
    $stmt->execute();        
    $return = $stmt->fetch(PDO::FETCH_ASSOC); 

    if($return_internship){
        if($_SESSION['c_approve'] = 1)
            {
            echo "<script>location= 'intern/documents/proposta_de_plano_de_estagio.php';</script>";
            exit();
            }
        if ($return["course_code_student"] != NULL)
        {
            $_SESSION["feedback"] = 'planAlreadyExists';
            $_SESSION['btn'] = 1;
            //echo "<script>location= 'intern/documents/proposta_de_plano_de_estagio.php';</script>";
            echo "<script>location= 'intern/internPage.php';</script>";
            exit();
        }
    }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Informações Adicionais Plano de Estágio</title>

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
            $('[name=total_hours_student]').removeAttr('disabled');
            $('[name=paid_hours_student]').removeAttr('disabled');  
            $('[name=semester_observations_student]').removeAttr('disabled');
            $('[name=year_observations_student]').removeAttr('disabled');  
            $('[name=monday]').removeAttr('disabled');
            $('[name=tuesday]').removeAttr('disabled');  
            $('[name=wednesday]').removeAttr('disabled');  
            $('[name=thursday]').removeAttr('disabled');  
            $('[name=friday]').removeAttr('disabled');  
            $('[name=saturday]').removeAttr('disabled');    
            $('[name=end_monday]').removeAttr('disabled');
            $('[name=end_tuesday]').removeAttr('disabled');  
            $('[name=end_wednesday]').removeAttr('disabled');  
            $('[name=end_thursday]').removeAttr('disabled');  
            $('[name=end_friday]').removeAttr('disabled');  
            $('[name=end_saturday]').removeAttr('disabled'); 
        };

        function submitForm(){
            $('[name=total_hours_student]').removeAttr('disabled');
            $('[name=paid_hours_student]').removeAttr('disabled');  
            $('[name=semester_observations_student]').removeAttr('disabled');
            $('[name=year_observations_student]').removeAttr('disabled');  
            $('[name=monday]').removeAttr('disabled');
            $('[name=tuesday]').removeAttr('disabled');  
            $('[name=wednesday]').removeAttr('disabled');  
            $('[name=thursday]').removeAttr('disabled');  
            $('[name=friday]').removeAttr('disabled');  
            $('[name=saturday]').removeAttr('disabled');    
            $('[name=end_monday]').removeAttr('disabled');
            $('[name=end_tuesday]').removeAttr('disabled');  
            $('[name=end_wednesday]').removeAttr('disabled');  
            $('[name=end_thursday]').removeAttr('disabled');  
            $('[name=end_friday]').removeAttr('disabled');  
            $('[name=end_saturday]').removeAttr('disabled');
             
            $('[name=total_hours_student]').removeAttr('required');
            $('[name=paid_hours_student]').removeAttr('required');  
            $('[name=semester_observations_student]').removeAttr('required');
            $('[name=year_observations_student]').removeAttr('required');  
            $('[name=monday]').removeAttr('required');
            $('[name=tuesday]').removeAttr('required');  
            $('[name=wednesday]').removeAttr('required');  
            $('[name=thursday]').removeAttr('required');  
            $('[name=friday]').removeAttr('required');  
            $('[name=saturday]').removeAttr('required');    
            $('[name=end_monday]').removeAttr('required');
            $('[name=end_tuesday]').removeAttr('required');  
            $('[name=end_wednesday]').removeAttr('required');  
            $('[name=end_thursday]').removeAttr('required');  
            $('[name=end_friday]').removeAttr('required');  
            $('[name=end_saturday]').removeAttr('required');
            
            $('form').append('<input type="hidden" name="sub3" />');
            $('form').submit();
        };
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

            $inf[] = "total_hours_student";
            $inf[] = "paid_hours_student";
            $inf[] = "semester_observations_student";
            $inf[] = "year_observations_student";
            $inf[] = "monday";
            $inf[] = "tuesday";
            $inf[] = "wednesday";
            $inf[] = "thursday";
            $inf[] = "friday";
            $inf[] = "saturday";
            $inf[] = "end_monday";
            $inf[] = "end_tuesday";
            $inf[] = "end_wednesday";
            $inf[] = "end_thursday";
            $inf[] = "end_friday";
            $inf[] = "end_saturday";
            
            

            for ($i=0; $i<=15; $i++)
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
                if($_SESSION["paid_hours_student"] > $_SESSION["total_hours_student"])
                    echo "<script>alert('Quantidade de horas integralizadas inválida!');</script>";
                else
                    $_SESSION['section'] = 1;
                
            }
            else if(isset($_POST['sub3']))
            {
                 
               

                if($_SESSION["paid_hours_student"] > $_SESSION["total_hours_student"])
                    echo "<script>alert('Quantidade de horas integralizadas inválida!');</script>";
                else
                    echo "<script>location= '../app/php/registerInternshipPlanInformation.php?type=intern';</script>";
            }
            else {
                $_SESSION['section'] = 0;
            }       




            if($_SESSION['section'] == 1){
                $form1 = "
                            <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                                <form action='../app/php/registerInternshipPlanInformation.php?type=intern' method='POST' id='f1' enctype='multipart/form-data'>
                                    <h2 class='text-white'>Informações Adicionais - Plano de Estágio
                                        <button type='button' class='btn btn-light' data-toggle='tooltip' data-placement='right' title='Editar' onclick='edit1()'>
                                            <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-pencil-fill' viewBox='0 0 16 16'>
                                                <path d='M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z'/>
                                            </svg>
                                        </button>
                                    </h2>
                                    
                                    
                                    
                                    <label for='total-hours-student' class='lead  fw-normal' style='color:white;'>Total de horas:</label>
                                    <input type='number' min='0' max='2880' id='total-hours-student' class='form-control' name='total_hours_student' value='".$_SESSION['total_hours_student']."' disabled><br>
                                    
                                    <label for='paid-hours-student' class='lead  fw-normal' style='color:white;'>Total de horas integralizadas:</label>  
                                    <input type='number' min='0' id='paid-hours-student' class='form-control' name='paid_hours_student' value='".$_SESSION['paid_hours_student']."' disabled><br>
                                    
                                    <label for='semester-observations-student' class='lead  fw-normal' style='color:white;'>Semestre atual do curso:</label>  
                                    <select class='form-control' id='semester-observations-student' name='semester_observations_student' disabled>
                                        <option selected value='".$_SESSION['semester_observations_student']."'>".$_SESSION['semester_observations_student']."° Semestre</option>
                                        <option value='1'>1º Semestre</option>
                                        <option value='2'>2º Semestre</option>
                                    </select>   

                                    <br>

                                    <label for='year-observations-student' class='lead  fw-normal' style='color:white;'>Ano atual:</label>  
                                    <select class='form-control' id='year-observations-student' name='year_observations_student' disabled>
                                        <option selected value='".$_SESSION['year_observations_student']."'>".$_SESSION['year_observations_student']."° ano</option>    
                                        <option value='1'>1° ano</option>
                                        <option value='2'>2° ano</option>
                                        <option value='3'>3° ano</option>
                                        <option value='4'>4° ano</option>
                                        <option value='5'>5° ano</option>
                                        <option value='6'>6° ano</option>
                                    </select>   <br>
                                    <div class='row'>

                                    <h2 class='text-white'>Horário de entrada e saída:</h2>

                                    <div class='col-lg-6 col-sm-12'>
                                    <label for='monday' class='lead  fw-normal' style='color:white;'>Segunda-feira (entrada):</label>  
                                    <input type='time' id='monday' class='form-control' name='monday' value='".$_SESSION['monday']."'><br>

                                    <label for='tuesday' class='lead  fw-normal' style='color:white;'>Terça-feira (entrada):</label>  
                                    <input type='time' id='tuesday' class='form-control' name='tuesday' value='".$_SESSION['tuesday']."'><br>

                                    <label for='wednesday' class='lead  fw-normal' style='color:white;'>Quarta-feira (entrada):</label>  
                                    <input type='time' id='wednesday' class='form-control' name='wednesday' value='".$_SESSION['wednesday']."'><br>

                                    <label for='thursday' class='lead  fw-normal' style='color:white;'>Quinta-feira (entrada):</label>  
                                    <input type='time' id='thursday' class='form-control' name='thursday' value='".$_SESSION['thursday']."'><br>

                                    <label for='friday' class='lead  fw-normal' style='color:white;'>Sexta-feira (entrada):</label>  
                                    <input type='time' id='friday' class='form-control' name='friday' value='".$_SESSION['friday']."'><br>

                                    <label for='saturday' class='lead  fw-normal' style='color:white;'>Sábado (entrada):</label>  
                                    <input type='time' id='saturday' class='form-control' name='saturday' value='".$_SESSION['saturday']."'><br>

                                   
                                    </div>
                                    
                                    <div class='col-lg-6 col-sm-12'>
                                    <label for='end_monday' class='lead  fw-normal' style='color:white;'>Segunda-feira (saída):</label>  
                                    <input type='time' id='end_monday' class='form-control' name='end_monday' value='".$_SESSION['end_monday']."'><br>
                                    
                                    <label for='end_tuesday' class='lead  fw-normal' style='color:white;'>Terça-feira (saída):</label>  
                                    <input type='time' id='end_tuesday' class='form-control' name='end_tuesday' value='".$_SESSION['end_tuesday']."'><br>

                                    <label for='end_wednesday' class='lead  fw-normal' style='color:white;'>Quarta-feira (saída):</label>  
                                    <input type='time' id='end_wednesday' class='form-control' name='end_wednesday' value='".$_SESSION['end_wednesday']."'><br>

                                    <label for='end_thursday' class='lead  fw-normal' style='color:white;'>Quinta-feira (saída):</label>  
                                    <input type='time' id='end_thursday' class='form-control' name='end_thursday' value='".$_SESSION['end_thursday']."'><br>

                                    <label for='end_friday' class='lead  fw-normal' style='color:white;'>Sexta-feira (saída):</label>  
                                    <input type='time' id='end_friday' class='form-control' name='end_friday' value='".$_SESSION['end_friday']."'><br>

                                    <label for='end_saturday' class='lead  fw-normal' style='color:white;'>Sábado (saída):</label>  
                                    <input type='time' id='end_saturday' class='form-control' name='end_saturday' value='".$_SESSION['end_saturday']."'><br>
                                    
                                    </div>
                                    </div>
                                    
                                    <br>
                                    <input type='submit' class='btn btn-success col-lg-3 col-12 fw-bold' style='border:solid 1px black;' name='sub3' value='Confirmar'>
                                </form> 
                            </div>
                        ";
                echo $form1;
            }


            

            if($_SESSION['section'] == 0){
                $form0 = "
                            <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                                <form action='' method='POST' id='f0'>
                                    <h2 class='text-white'>Informações Adicionais - Plano de Estágio</h2>
                                 <div class='row'>
                                    <div class='col-lg-6 col-sm-12'>
                                    <label for='total-hours-student' class='lead  fw-normal' style='color:white;'>Total de horas *</label>
                                    <input type='number' min='0' max='1440' id='total-hours-student' class='form-control' name='total_hours_student' value='".$_SESSION['total_hours_student']."' required><br>
                                    
                                    <label for='paid-hours-student' class='lead  fw-normal' style='color:white;'>Total de horas integralizadas *</label>  
                                    <input type='number' min='0' id='paid-hours-student' class='form-control' name='paid_hours_student' value='".$_SESSION['paid_hours_student']."' required><br>
                                    </div>


                                    
                                    <div class='col-lg-6 col-sm-12'>
                                    <label for='semester-observations-student' class='lead  fw-normal' style='color:white;'>Semestre atual do curso *</label>  
                                    <select class='form-control' id='semester-observations-student' name='semester_observations_student' required>
                                        <option value='1'>1º Semestre</option>
                                        <option value='2'>2º Semestre</option>
                                    </select>   

                                    <br>

                                    <label for='year-observations-student' class='lead  fw-normal' style='color:white;'>Ano atual *</label>  
                                    <select class='form-control' id='year-observations-student' name='year_observations_student' required>
                                        <option value='1'>1° ano</option>
                                        <option value='2'>2° ano</option>
                                        <option value='3'>3° ano</option>
                                        <option value='4'>4° ano</option>
                                        <option value='5'>5° ano</option>
                                        <option value='6'>6° ano</option>
                                    </select>   
                                    </div>
                                    <h2 class='text-white'>Horário de entrada e saída:</h2>
                                    <h5 class='text-white'>(Caso não houver estágio no dia, preencher com ''zeros'')</h5>
                                    <div class='col-lg-6 col-sm-12'>
                                    
                                    <label for='monday' class='lead  fw-normal' style='color:white;'>Segunda-feira (entrada):</label>  
                                    <input type='time' id='monday' class='form-control' name='monday' value='".$_SESSION['monday']."' required><br>

                                    <label for='tuesday' class='lead  fw-normal' style='color:white;'>Terça-feira (entrada):</label>  
                                    <input type='time' id='tuesday' class='form-control' name='tuesday' value='".$_SESSION['tuesday']."' required><br>

                                    <label for='wednesday' class='lead  fw-normal' style='color:white;'>Quarta-feira (entrada):</label>  
                                    <input type='time' id='wednesday' class='form-control' name='wednesday' value='".$_SESSION['wednesday']."' required><br>

                                    <label for='thursday' class='lead  fw-normal' style='color:white;'>Quinta-feira (entrada):</label>  
                                    <input type='time' id='thursday' class='form-control' name='thursday' value='".$_SESSION['thursday']."' required><br>

                                    <label for='friday' class='lead  fw-normal' style='color:white;'>Sexta-feira (entrada):</label>  
                                    <input type='time' id='friday' class='form-control' name='friday' value='".$_SESSION['friday']."' required><br>

                                    <label for='saturday' class='lead  fw-normal' style='color:white;'>Sábado (entrada):</label>  
                                    <input type='time' id='saturday' class='form-control' name='saturday' value='".$_SESSION['saturday']."' required><br>
                                    
                                  

                                    </div>
                                    
                                    <div class='col-lg-6 col-sm-12'>
                                    <label for='end_monday' class='lead  fw-normal' style='color:white;'>Segunda-feira (saída):</label>  
                                    <input type='time' id='end_monday' class='form-control' name='end_monday' value='".$_SESSION['end_monday']."' required><br>
                                    
                                    <label for='end_tuesday' class='lead  fw-normal' style='color:white;'>Terça-feira (saída):</label>  
                                    <input type='time' id='end_tuesday' class='form-control' name='end_tuesday' value='".$_SESSION['end_tuesday']."' required><br>

                                    <label for='end_wednesday' class='lead  fw-normal' style='color:white;'>Quarta-feira (saída):</label>  
                                    <input type='time' id='end_wednesday' class='form-control' name='end_wednesday' value='".$_SESSION['end_wednesday']."' required><br>

                                    <label for='end_thursday' class='lead  fw-normal' style='color:white;'>Quinta-feira (saída):</label>  
                                    <input type='time' id='end_thursday' class='form-control' name='end_thursday' value='".$_SESSION['end_thursday']."' required><br>

                                    <label for='end_friday' class='lead  fw-normal' style='color:white;'>Sexta-feira (saída):</label>  
                                    <input type='time' id='end_friday' class='form-control' name='end_friday' value='".$_SESSION['end_friday']."' required><br>

                                    <label for='end_saturday' class='lead  fw-normal' style='color:white;'>Sábado (saída):</label>  
                                    <input type='time' id='end_saturday' class='form-control' name='end_saturday' value='".$_SESSION['end_saturday']."' required><br>
                                    
                                   
                                    </div>
                                    </div>
                                    <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' name='sub2' value='Enviar'>
                                 </div>
                                   <br>
                                    
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
