<?php
    session_start();

    if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 2){
        echo "<script>alert('Usuário não reconhecido.');</script>";
        header("Location: ../../index.php ");
        exit();
    }
    
  

    require_once('../../app/db/connect.php');
    require_once('../../app/php/functions.php');

    $id_person = $_SESSION['idUser'];
    
    
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edição - Professor</title>

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
        $("#telephone-person").mask("(00) 00000-0000");
        $("#rg-person").mask("00.000.000-0");
        $("#cpf-person").mask("000.000.000-00");
        
    });
    </script>
</head>
<body>
  <div id="wrapper" class="p-0">
      <div id="navbar-wrapper">
          <nav class="navbar navbar-inverse">
              <div class="container-fluid">
                  <div class="navbar-header">
                      <a href="professorPage.php" class="navbar-brand" style="font-size: 24px;"><i class="fas fa-arrow-circle-left"></i></a>
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
          header('Location: professorPage.php');
        }   
      } else {
      header('Location: professorPage.php');
      }
    ?>

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 py-2" id="content">
                    <form action="../../app/php/professor/logic/professorEditLogic.php?<?php echo "type=$type&id=$id"?>" method="POST">
                        
                        <?php
                            if ($type == 'person') 
                            {
                              // Edição de Pessoas
                              
                              $query = 'SELECT * FROM person WHERE id_person = :id';

                              $stmt = $conn->prepare($query);

                              $stmt->bindValue(':id', $idDec);

                              $stmt->execute();

                              $return = $stmt->fetch(PDO::FETCH_ASSOC);
                              
                              if($return['valid']) 
                              {
                                    echo '<h2 class="py-2">Edição - '.$return['name_person'].'</h2>';
                                
                                    $form =
                                    "
                                    <div class='row'>
                                        <div class='col-lg-6 col-sm-12'>
                                            <label for='name-person' class='lead fw-normal'>Nome:</label>
                                            <input type='text' id='name-person' class='form-control' name='name-person' value='".$return['name_person']."' required><br>
                                            <label for='email-person' class='lead fw-normal'>E-mail:</label>
                                            <input type='email' id='email-person' class='form-control' name='email-person' value='".$return['email_person']."' required><br>
                                            <label for='telephone-person' class='lead fw-normal'>Telefone:</label>
                                            <input type='tel' id='telephone-person' class='form-control' name='telephone-person' value='".$return['telephone_person']."' required><br>
                                        </div>
                                        <div class='col-lg-6 col-sm-12'>
                                            <label for='cpf-person' class='lead fw-normal'>CPF:</label>
                                            <input type='text' id='cpf-person' class='form-control' name='cpf-person' value='".$return['cpf_person']."' required><br>
                                            <label for='rg-person' class='lead fw-normal'>RG:</label>
                                            <input type='text' id='rg-person' class='form-control' name='rg-person' value='".$return['rg_person']."' required><br>
                                            <label for='treatment-person' class='lead fw-normal'>Tratamento:</label>
                                            <select class='form-control' name='treatment-person' id='treatment-person' required>
                                                <option selected value='".$return['treatment_person']."'>".$return['treatment_person']."</option>
                                                <option value='Sr'>Senhor</option>
                                                <option value='Sra'>Senhora</option>
                                                <option value='Dr'>Doutor</option>
                                                <option value='Dra'>Doutora</option>
                                            </select> <br>
                                        </div> 
                                    </div> 

                                    <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' value='Salvar'>
                                    ";

                                    echo $form;

                              }
                              else {
                                header('Location: professorPage.php');
                              }
                            }
                            else if($type == "intern"){
                                $query = 'SELECT * FROM person WHERE id_person = :id';

                                $stmt = $conn->prepare($query);

                                $stmt->bindValue(':id', $idDec);

                                $stmt->execute();

                                $return = $stmt->fetch(PDO::FETCH_ASSOC);

                                $name_person = $return['name_person'];
                                if($return['valid']) {
                                    
                                    echo '<h2 class="py-2">Informações - '.$return['name_person'].'</h2>';

                                    $form = "
                                    <div class='row'>
                                        <div class='col-lg-6 col-sm-12'>
                                            <label for='name-person' class='lead fw-normal'>Nome:</label>
                                            <input type='text' id='name-person' class='form-control' name='name-person' value='".$return['name_person']."' ><br>
                                            <label for='email-person' class='lead fw-normal'>E-mail:</label>
                                            <input type='email' id='email-person' class='form-control' name='email-person' value='".$return['email_person']."' ><br>
                                            <label for='telephone-person' class='lead fw-normal'>Telefone:</label>
                                            <input type='tel' id='telephone-person' class='form-control' name='telephone-person' value='".$return['telephone_person']."' ><br>
                                        </div>
                                        <div class='col-lg-6 col-sm-12'>
                                            <label for='cpf-person' class='lead fw-normal'>CPF:</label>
                                            <input type='text' id=''cpf-person' class='form-control' name='cpf-person' value='".$return['cpf_person']."' ><br>
                                            <label for='rg-person' class='lead fw-normal'>RG:</label>
                                            <input type='text' id='rg-person' class='form-control' name='rg-person' value='".$return['rg_person']."' ><br>
                                            <label for='treatment-person' class='lead fw-normal'>Tratamento:</label>
                                            <select class='form-control' name='treatment-person' id='treatment-person' required>
                                                <option selected value='".$return['treatment_person']."'>".$return['treatment_person']."</option>
                                                <option value='Sr'>Senhor</option>
                                                <option value='Sra'>Senhora</option>
                                                <option value='Dr'>Doutor</option>
                                                <option value='Dra'>Doutora</option>
                                            </select> <br>
                                        </div> 
                                        </div> 
                                    ";
                            
                                    echo $form;

                                }

                                $query = 'SELECT * FROM internship_data WHERE fk_student = :id AND finished = FALSE AND valid = TRUE';
                            
                                $stmt = $conn->prepare($query);

                                $stmt->bindValue(':id', $idDec);

                                $stmt->execute();

                                $return = $stmt->fetch(PDO::FETCH_ASSOC);  
                                
                                if($return)
                                {
                                    // $h1 = new DateTime($return['start_time_internship_data']);
                                    // $h2 = new DateTime($return['end_time_internship_data']);
                                    // $diff = $h2->diff($h1, true);   
                                    // $diff = $diff->format("%h:%i");
                                    // if($diff > 6)
                                    // {
                                    //     $h3 = new DateTime($diff);   
                                    //     $s = '06:00:00';
                                    //     $date = date_create_from_format('H:i:s', $s);
                                    //     $date->getTimestamp();
                                    //     // $h4 = new DateTime($date);
                                    //     $diff2 = $h3->diff($date, true); 
                                    //     $diff2 = $diff2->format("%h:%i");
                                    //     $lunch_time = new DateTime($diff2); 
                                    //     $lunch_time = $lunch_time->format('H:i'); 
                                    // }
                                    // else
                                    // { 
                                    //     $s = '00:00:00';
                                    //     $date = date_create_from_format('H:i:s', $s);
                                    //     $date->getTimestamp();
                                    //     $lunch_time = $date->format('H:i');                                   
                                    // }

                                    if($return['nature_internship_data'] == true)
                                    {
                                        
                                        echo '<h3 class="py-2">Informações - Estágio obrigatório de '.$name_person.'</h3>';
                                    }
                                    else
                                    {
                                        
                                    echo '<h3 class="py-2">Informações - Estágio não obrigatório de '.$name_person.'</h3>';
                                        
                                    }

                                    if ($return['scholarship_internship_data'] == TRUE)
                                    {
                                        $scholarship = "<input type='radio' name='scholarship-internship-data' id='scholarship-internship-data' value='True' checked required> Sim
                                                        <input type='radio' name='scholarship-internship-data' id='scholarship-internship-data' value='False' style='margin-left:15px;' required> Não<br>
                                                        <br>
                                                        <label for='scholarship-value-internship-data' class='lead fw-normal'>Valor da bolsa:</label>
                                                        <input type='number' id='scholarship-value-internship-data' class='form-control' name='scholarship-value-internship-data' value='".$return['scholarship_value_internship_data']."' min='0' required><br>";
                                    }
                                    else if($return['scholarship_internship_data'] == FALSE)
                                    {
                                        $scholarship = "<input type='radio' name='scholarship-internship-data' id='scholarship-internship-data' value='True' required> Sim
                                                        <input type='radio' name='scholarship-internship-data' id='scholarship-internship-data' value='False' style='margin-left:15px;' checked required> Não<br>
                                                        <br>
                                                        <label for='scholarship-value-internship-data' class='lead fw-normal'>Valor da bolsa:</label>
                                                        <input type='number' id='scholarship-value-internship-data' class='form-control' name='scholarship-value-internship-data' value='0' min='0' required><br>";
                                    }          
                
                                    $form = "
                                    <div class='row'>
                                        <div class='col-lg-6 col-sm-12'>
                                            <label for='role-internship-data' class='lead fw-normal'>Função:</label>
                                            <input type='text' id='role-internship-data' class='form-control' name='role-internship-data' value='".$return['role_internship_data']."'><br>
                                            <label for='course-internship-data' class='lead fw-normal'>Curso:</label>
                                            <input type='text' id='course-internship-data' class='form-control' name='course-internship-data' value='".$return['course_internship_data']."' required><br>
                                            <label for='area-internship-data' class='lead fw-normal'>Área:</label>                                            
                                            <input type='text' id='area-internship-data' class='form-control' name='area-internship-data' value='".$return['area_internship_data']."' required><br>
                                            <label for='week-hours-internship-data' class='lead fw-normal'>Horas semanais:</label>
                                            <input type='number' id='week-hours-internship-data' class='form-control' name='week-hours-internship-data' value='".$return['week_hours_internship_data']."' min='0' required><br>
                                            <label for='start-date-internship-data' class='lead fw-normal'>Data de Início:</label>
                                            <input type='date' id='start-date-internship-data' class='form-control' name='start-date-internship-data' value='".$return['start_date_internship_data']."' required><br>
                                            <label for='end-date-internship-data' class='lead fw-normal'>Data de término:</label>
                                            <input type='date' id='end-date-internship-data' class='form-control'  name='end-date-internship-data' value='".$return['end_date_internship_data']."' required><br>
                                            <label for='total-hours-internship-data' class='lead fw-normal'>Total de horas:</label>
                                            <input type='number' id='total-hours-internship-data' class='form-control'  name='total-hours-internship-data' value='".$return['total_hours_internship_data']."' required><br>
                                        </div>
                                        <div class='col-lg-6 col-sm-12'>
                                            <label for='scholarship-internship-data' class='lead fw-normal' style='margin-top:15px;'>Possui bolsa?</label><br>
                                            ".$scholarship."
                                            
                                            <label for='lunch-time' class='lead fw-normal'>Horas de almoço:</label>  
                                            <input type='time' id='lunch-time' class='form-control' name='lunch-time' value='".$lunch_time."' required><br>
                                            
                                            <label for='description-internship-data' class='lead fw-normal'>Descrição: </label>
                                            <textarea id='description-internship-data' class='form-control' name='description-internship-data'  style='height:130px;' required>".$return['description_internship_data']."</textarea><br>
                                        </div> 
                                    </div> 
                                    
                                    <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' value='Salvar'>
                                    ";
                            
                                    echo $form;
                                }              
                                else
                                {
                                    echo '<div class="col-12 h5 mt-5 text-secondary text-center">O estágio ainda não está validado</div>';
                                }                 

                            }
                            else if($type == "internship_data"){

                                $query = 'SELECT name_person FROM person WHERE id_person = :id';

                                $stmt = $conn->prepare($query);

                                $stmt->bindValue(':id', $idDec);

                                $stmt->execute();

                                $return = $stmt->fetch(PDO::FETCH_ASSOC);

                                $name_person = $return['name_person'];

                                $query = 'SELECT * FROM internship_data WHERE fk_student = :id AND finished = FALSE AND valid = TRUE';
                            
                                $stmt = $conn->prepare($query);

                                $stmt->bindValue(':id', $idDec);

                                $stmt->execute();

                                $return = $stmt->fetch(PDO::FETCH_ASSOC);  
                                
                                if($return)
                                {
                                    $h1 = new DateTime($return['start_time_internship_data']);
                                    $h2 = new DateTime($return['end_time_internship_data']);
                                    $diff = $h2->diff($h1, true);   
                                    $diff = $diff->format("%h:%i");
                                    if($diff > 6)
                                    {
                                        $h3 = new DateTime($diff);   
                                        $s = '06:00:00';
                                        $date = date_create_from_format('H:i:s', $s);
                                        $date->getTimestamp();
                                        // $h4 = new DateTime($date);
                                        $diff2 = $h3->diff($date, true); 
                                        $diff2 = $diff2->format("%h:%i");
                                        $lunch_time = new DateTime($diff2); 
                                        $lunch_time = $lunch_time->format('H:i'); 
                                    }
                                    else
                                    { 
                                        $s = '00:00:00';
                                        $date = date_create_from_format('H:i:s', $s);
                                        $date->getTimestamp();
                                        $lunch_time = $date->format('H:i');                                   
                                    }
                                    
                                    if($return['nature_internship_data'] == true)
                                    {
                                        
                                        echo '<h3 class="py-2">Informações - Estágio obrigatório de '.$name_person.'</h3>';
                                    }
                                    else
                                    {
                                        
                                    echo '<h3 class="py-2">Informações - Estágio não obrigatório de '.$name_person.'</h3>';
                                        
                                    }

                                    if ($return['scholarship_internship_data'] == TRUE)
                                    {
                                        $scholarship = "<input type='radio' name='scholarship-internship-data' id='scholarship-internship-data' value='True' checked required> Sim
                                                        <input type='radio' name='scholarship-internship-data' id='scholarship-internship-data' value='False' style='margin-left:15px;' required> Não<br>
                                                        <br>
                                                        <label for='scholarship-value-internship-data' class='lead fw-normal'>Valor da bolsa:</label>
                                                        <input type='number' id='scholarship-value-internship-data' class='form-control' name='scholarship-value-internship-data' value='".$return['scholarship_value_internship_data']."' min='0' required><br>";
                                    }
                                    else if($return['scholarship_internship_data'] == FALSE)
                                    {
                                        $scholarship = "<input type='radio' name='scholarship-internship-data' id='scholarship-internship-data' value='True' required> Sim
                                                        <input type='radio' name='scholarship-internship-data' id='scholarship-internship-data' value='False' style='margin-left:15px;' checked required> Não<br>
                                                        <br>
                                                        <label for='scholarship-value-internship-data' class='lead fw-normal'>Valor da bolsa:</label>
                                                        <input type='number' id='scholarship-value-internship-data' class='form-control' name='scholarship-value-internship-data' value='0' min='0' required><br>";
                                    }          
                
                                    $form = "
                                    <div class='row'>
                                        <div class='col-lg-6 col-sm-12'>
                                            <label for='role-internship-data' class='lead fw-normal'>Função:</label>
                                            <input type='text' id='role-internship-data' class='form-control' name='role-internship-data' value='".$return['role_internship_data']."'><br>
                                            <label for='course-internship-data' class='lead fw-normal'>Curso:</label>
                                            <input type='text' id='course-internship-data' class='form-control' name='course-internship-data' value='".$return['course_internship_data']."' required><br>
                                            <label for='area-internship-data' class='lead fw-normal'>Área:</label>                                            
                                            <input type='text' id='area-internship-data' class='form-control' name='area-internship-data' value='".$return['area_internship_data']."' required><br>
                                            <label for='week-hours-internship-data' class='lead fw-normal'>Horas semanais:</label>
                                            <input type='number' id='week-hours-internship-data' class='form-control' name='week-hours-internship-data' value='".$return['week_hours_internship_data']."' min='0' required><br>
                                            <label for='start-date-internship-data' class='lead fw-normal'>Data de Início:</label>
                                            <input type='date' id='start-date-internship-data' class='form-control' name='start-date-internship-data' value='".$return['start_date_internship_data']."' required><br>
                                            <label for='end-date-internship-data' class='lead fw-normal'>Data de término:</label>
                                            <input type='date' id='end-date-internship-data' class='form-control'  name='end-date-internship-data' value='".$return['end_date_internship_data']."' required><br>
                                            <label for='total-hours-internship-data' class='lead fw-normal'>Total de horas:</label>
                                            <input type='number' id='total-hours-internship-data' class='form-control'  name='total-hours-internship-data' value='".$return['total_hours_internship_data']."' required><br>
                                        </div>
                                        <div class='col-lg-6 col-sm-12'>
                                            <label for='scholarship-internship-data' class='lead fw-normal' style='margin-top:15px;'>Possui bolsa?</label><br>
                                            ".$scholarship."
                                          
                                            <label for='lunch-time' class='lead fw-normal'>Horas de almoço:</label>  
                                            <input type='time' id='lunch-time' class='form-control' name='lunch-time' value='".$lunch_time."' required><br>
                                            
                                            <label for='description-internship-data' class='lead fw-normal'>Descrição: </label>
                                            <textarea id='description-internship-data' class='form-control' name='description-internship-data'  style='height:130px;' required>".$return['description_internship_data']."</textarea><br>
                                        </div> 
                                    </div> 
                                    
                                    <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' value='Salvar'>
                                    ";
                            
                                    echo $form;
                                }              
                                else
                                {
                                    echo '<div class="col-12 h5 mt-5 text-secondary text-center">O estágio ainda não está validado</div>';
                                }                      
                            }
                            else if ($type == 'profile') 
                            {
                                // Edição de Dados Pessoais
                                $query = "SELECT * FROM change_data WHERE fk_id = ".$id_person." AND allowed = FALSE";
   
                                $stmt = $conn->prepare($query);

                                $stmt->execute();

                                $return = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if($return) //se o pedido ainda não foi permitido
                                {
                                    $query = "SELECT * FROM change_data WHERE fk_id = ".$id_person." AND allowed = FALSE AND pending_allowance = TRUE";
                            
                                    $stmt = $conn->prepare($query);

                                    $return = $stmt->execute();

                                    $return = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    $query = "SELECT * FROM change_data WHERE fk_id = ".$id_person." AND edited = TRUE";
                            
                                    $stmt = $conn->prepare($query);

                                    $return_2 = $stmt->execute();

                                    $return_2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    $query = "SELECT * FROM change_data WHERE fk_id = ".$id_person." AND blocked_edition = TRUE";
                            
                                    $stmt = $conn->prepare($query);

                                    $return_3 = $stmt->execute();

                                    $return_3 = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    if($return_3)
                                    {
                                        echo "<script>alert('Seu poder de requisição de edição foi bloqueado. Caso achar que isso é um erro, entre em contato com seus superiores.');</script>";
                                        echo "<script>window.location.replace('professorPage.php')</script>";
                                    }
                                    else if ($return)
                                    {
                                        echo "<script>alert('Seu pedido de edição ainda não foi respondido.');</script>";           
                                        echo "<script>window.location.replace('professorPage.php')</script>";
                                    }
                                    else if ($return_2)
                                    {
                                        echo "<script>alert('Você já editou seus dados, peça permissão novamente caso queira editar outras informações.');</script>";
                                        echo "<script>window.location.replace('professorPage.php')</script>";
                                    }
                                    else
                                    {
                                        echo "<script>alert('Você não possui acesso à edição destes dados.');</script>";
                                        echo "<script>window.location.replace('professorPage.php')</script>";
                                    }
                                }  

                                $query = 'SELECT * FROM person WHERE id_person = :id';

                                $stmt = $conn->prepare($query);

                                $stmt->bindValue(':id', $idDec);

                                $stmt->execute();

                                $return = $stmt->fetch(PDO::FETCH_ASSOC);
                                
                                if($return['valid']) 
                                {
                                        echo '<h2 class="py-2">Edição - '.$return['name_person'].'</h2>';
                                    
                                        $form =
                                        "
                                        <div class='row'>
                                            <div class='col-lg-6 col-sm-12'>
                                                <label for='name-person' class='lead fw-normal'>Nome:</label>
                                                <input type='text' id='name-person' class='form-control' name='name-person' value='".$return['name_person']."' required><br>
                                                <label for='email-person' class='lead fw-normal'>E-mail:</label>
                                                <input type='email' id='email-person' class='form-control' name='email-person' value='".$return['email_person']."' required><br>
                                                <label for='telephone-person' class='lead fw-normal'>Telefone:</label>
                                                <input type='tel' id='telephone-person' class='form-control' name='telephone-person' value='".$return['telephone_person']."' required><br>
                                            </div>
                                            <div class='col-lg-6 col-sm-12'>
                                                <label for='cpf-person' class='lead fw-normal'>CPF:</label>
                                                <input type='text' id='cpf-person' class='form-control' name='cpf-person' value='".$return['cpf_person']."' required><br>
                                                <label for='rg-person' class='lead fw-normal'>RG:</label>
                                                <input type='text' id='rg-person' class='form-control' name='rg-person' value='".$return['rg_person']."' required><br>
                                                <label for='treatment-person' class='lead fw-normal'>Tratamento:</label>
                                                <select class='form-control' name='treatment-person' id='treatment-person' required>
                                                    <option selected value='".$return['treatment_person']."'>".$return['treatment_person']."</option>
                                                    <option value='Sr'>Senhor</option>
                                                    <option value='Sra'>Senhora</option>
                                                    <option value='Dr'>Doutor</option>
                                                    <option value='Dra'>Doutora</option>
                                                </select> <br>
                                            </div> 
                                        </div> 

                                        <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' value='Salvar'>
                                        ";

                                        echo $form;

                                }
                                else {
                                    header('Location: professorPage.php');
                                }
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

    <script src="../../js/professor.js"></script>
</body>
</html>