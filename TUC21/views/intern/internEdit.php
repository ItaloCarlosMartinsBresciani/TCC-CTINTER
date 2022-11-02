<?php
    session_start();

    if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 1){
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
    <title>Edição - Estagiário</title>

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
        $("#cep_student").mask("00000-000");
        
    });

    function cleanFormCep(){
      document.getElementById('cep_student').value=("");
      document.getElementById('address_student').value=("");
      document.getElementById('district_student').value=("");
      document.getElementById('city_student').value=("");
    };

    function callbackCep(conteudo) {
        if (!("erro" in conteudo)) {
            document.getElementById('address_student').value=(conteudo.logradouro);
            document.getElementById('district_student').value=(conteudo.bairro);
            document.getElementById('city_student').value=(conteudo.localidade);
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
                
                document.getElementById('address_student').value="...";
                document.getElementById('district_student').value="...";
                document.getElementById('city_student').value="...";
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

    </script>
</head>
<body>
  <div id="wrapper" class="p-0">
      <div id="navbar-wrapper">
          <nav class="navbar navbar-inverse">
              <div class="container-fluid">
                  <div class="navbar-header">
                      <a href="internPage.php" class="navbar-brand" style="font-size: 24px;"><i class="fas fa-arrow-circle-left"></i></a>
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
          header('Location: internPage.php');
        }   
      } else {
      header('Location: internPage.php');
      }
    ?>

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 py-2" id="content">
                    <form action="../../app/php/intern/logic/internEditLogic.php?<?php echo "type=$type&id=$id"?>" method="POST">
                        
                        <?php
                            if ($type == 'person') 
                            {
                                $query = "SELECT * FROM change_data WHERE fk_id = ".$id_person." AND allowed = FALSE";
   
                                $stmt = $conn->prepare($query);

                                $return = $stmt->execute();

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
                                        echo "<script>alert('Seu poder de requisição de edição foi bloqueado. Caso achar que isso é um erro, entre em contato com seu professor.');</script>";
                                        echo "<script>window.location.replace('internPage.php')</script>";
                                    }
                                    else if ($return)
                                    {
                                        echo "<script>alert('Seu pedido de edição ainda não foi respondido.');</script>";           
                                        echo "<script>window.location.replace('internPage.php')</script>";
                                    }
                                    else if ($return_2)
                                    {
                                        echo "<script>alert('Você já editou seus dados, peça permissão novamente caso queira editar outras informações.');</script>";
                                        echo "<script>window.location.replace('internPage.php')</script>";
                                    }
                                    else
                                    {
                                        echo "<script>alert('Você não possui acesso à edição destes dados.');</script>";
                                        echo "<script>window.location.replace('internPage.php')</script>";
                                    }
                                } 
                                //fazer select se já está permitido, se não
                                //deixar recado que já foi pedida a edição

                                // Edição de Pessoas
                                $query = 'SELECT * FROM person WHERE id_person = :id';

                                $stmt = $conn->prepare($query);

                                $stmt->bindValue(':id', $idDec);

                                $stmt->execute();

                                $return = $stmt->fetch(PDO::FETCH_ASSOC);

                                 //Dados do estudante

                                    $query2 = "SELECT * FROM student WHERE fk_id = :id";

                                    $stmt2 = $conn->prepare($query2);

                                    $stmt2->bindValue(':id', $idDec);

                                    $stmt2->execute();

                                    $return2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                                
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
                                            <label for='cep_student' class='lead fw-normal'>CEP:</label>
                                            <input type='text' id='cep_student' class='form-control' name='cep_student' value='".$return2['cep_student']."' onblur='searchCep(this.value)' required><br>
                                            <label for='city_student' class='lead fw-normal'>Cidade:</label>
                                            <input type='text' id='city_student' class='form-control' name='city_student' value='".$return2['city_student']."' required><br>
                                        </div>
                                        <div class='col-lg-6 col-sm-12'>
                                            <label for='cpf-person' class='lead fw-normal'>CPF:</label>
                                            <input type='text' id='cpf-person' class='form-control' name='cpf-person' value='".$return['cpf_person']."' required><br>
                                            <label for='rg-person' class='lead fw-normal'>RG:</label>
                                            <input type='text' id='rg-person' class='form-control' name='rg-person' value='".$return['rg_person']."' required><br>
                                            <label for='treatment-person' class='lead fw-normal'>Tratamento:</label>
                                            <input type='text' id='treatment-person' class='form-control' placeholder='Ex: Doutor' name='treatment-person' value='".$return['treatment_person']."' required><br>
                                            <label for='address_student' class='lead fw-normal'>Endereço:</label>
                                            <input type='text' id='address_student' class='form-control' name='address_student' value='".$return2['address_student']."' required><br>
                                            <label for='district_student' class='lead fw-normal'>Distrito:</label>
                                            <input type='text' id='district_student' class='form-control' name='district_student' value='".$return2['district_student']."' required><br>
                                        </div> 
                                    </div> 
                                    ";

                                    echo $form;

                                    $query = 'SELECT * FROM internship_data WHERE fk_student = :id';
                            
                                    $stmt = $conn->prepare($query);

                                    $stmt->bindValue(':id', $idDec);

                                    $stmt->execute();

                                    $return = $stmt->fetch(PDO::FETCH_ASSOC);  
                                    
                                    if($return)
                                    {
                                        echo '<h3 class="py-2">Informações - Estágio '.$return["name_internship_data"].'
                                                <a href="internEdit.php?type=internship&id='.codeId($return["id_internship_data"]).'"> <!--botão de edição -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                                        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                                                    </svg>
                                                </a>
                                            </h3>';
                                        
                                        if($return['scholarship_internship_data'])
                                        {
                                            $scholarship = "<label for='scholarship-internship-data' class='lead fw-normal'>Possui bolsa?</label>
                                                            <input type='text' id='scholarship-internship-data' class='form-control' name='scholarship-internship-data' value='Sim' disabled><br>
                                                            <label for='scholarship-value-internship-data' class='lead fw-normal'>Valor da bolsa:</label>
                                                            <input type='number' id='scholarship-value-internship-data' class='form-control' name='scholarship-value-internship-data' value='".$return['scholarship_value_internship_data']."' disabled><br>
                                                            ";
                                        }
                                        else{
                                            $scholarship = "<label for='scholarship-internship-data' class='lead fw-normal'>Possui bolsa?</label>
                                                            <input type='text' id='scholarship-internship-data' class='form-control' name='scholarship-internship-data' value='Não' disabled><br>
                                                            ";
                                            $height = 225;
                                        }

                                        $form = "
                                        <div class='row'>
                                            <div class='col-lg-6 col-sm-12'>
                                                <label for='role-internship-data' class='lead fw-normal'>Função:</label>
                                                <input type='text' id='role-internship-data' class='form-control' name='role-internship-data' value='".$return['role_internship_data']."' disabled><br>
                                                <label for='course-internship-data' class='lead fw-normal'>Curso:</label>
                                                <input type='text' id='course-internship-data' class='form-control' name='course-internship-data' value='".$return['course_internship_data']."' disabled><br>
                                                <label for='area-internship-data' class='lead fw-normal'>Área:</label>                                            
                                                <input type='text' id='area-internship-data' class='form-control' name='area-internship-data' value='".$return['area_internship_data']."' disabled><br>
                                                <label for='week-hours-internship-data' class='lead fw-normal'>Horas semanais:</label>
                                                <input type='number' id='week-hours-internship-data' class='form-control' name='week-hours-internship-data' value='".$return['week_hours_internship_data']."' disabled><br>
                                                <label for='start-date-internship-data' class='lead fw-normal'>Data de Início:</label>
                                                <input type='date' id='start-date-internship-data' class='form-control' name='start-date-internship-data' value='".$return['start_date_internship_data']."' disabled><br>
                                                <label for='end-date-internship-data' class='lead fw-normal'>Data de término:</label>
                                                <input type='date' id='end-date-internship-data' class='form-control'  name='end-date-internship-data' value='".$return['end_date_internship_data']."' disabled><br>
                                                <label for='total-hours-internship-data' class='lead fw-normal'>Total de horas:</label>
                                                <input type='number' id='total-hours-internship-data' class='form-control'  name='total-hours-internship-data' value='".$return['total_hours_internship_data']."' disabled><br>
                                            </div>
                                            <div class='col-lg-6 col-sm-12'>
                                                ".$scholarship."
                                                
                                                <label for='description-internship-data' class='lead fw-normal'>Descrição: </label>
                                                <textarea id='description-internship-data' class='form-control' name='description-internship-data'  style='height:130px;' disabled>".$return['description_internship_data']."</textarea><br>
                                                <label for='total-hours-internship-data' class='lead fw-normal'>Total de horas:</label>
                                                <input type='number' id='total-hours-internship-data' class='form-control'  name='total-hours-internship-data' value='".$return['total_hours_internship_data']."' disabled><br>
                                            </div> 
                                        </div> 
                                        

                                        <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' value='Salvar'>
                                        ";
                                
                                        echo $form;
                                    }
                                }                              
                            }else if($type == "internship"){

                                $query = "SELECT * FROM change_data_internship WHERE fk_id = ".$idDec." AND allowed = FALSE";
   
                                $stmt = $conn->prepare($query);

                                $return = $stmt->execute();

                                $return = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if($return) //se o pedido ainda não foi permitido
                                {
                                    $query = "SELECT * FROM change_data_internship WHERE fk_id = ".$idDec." AND allowed = FALSE AND pending_allowance = TRUE";
                            
                                    $stmt = $conn->prepare($query);

                                    $return = $stmt->execute();

                                    $return = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    $query = "SELECT * FROM change_data_internship WHERE fk_id = ".$idDec." AND edited = TRUE";
                            
                                    $stmt = $conn->prepare($query);

                                    $return_2 = $stmt->execute();

                                    $return_2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    $query = "SELECT * FROM change_data_internship WHERE fk_id = ".$idDec." AND blocked_edition = TRUE";
                            
                                    $stmt = $conn->prepare($query);

                                    $return_3 = $stmt->execute();

                                    $return_3 = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    if($return_3)
                                    {
                                        echo "<script>alert('Seu poder de requisição de edição foi bloqueado. Caso achar que isso é um erro, entre em contato com seu professor.');</script>";
                                        echo "<script>window.location.replace('internPage.php')</script>";
                                    }
                                    else if ($return)
                                    {
                                        echo "<script>alert('Seu pedido de edição ainda não foi respondido.');</script>";           
                                        echo "<script>window.location.replace('internPage.php')</script>";
                                    }
                                    else if ($return_2)
                                    {
                                        echo "<script>alert('Você já editou os dados, peça permissão novamente caso queira editar outras informações.');</script>";
                                        echo "<script>window.location.replace('internPage.php')</script>";
                                    }
                                    else
                                    {
                                        echo "<script>alert('Você não possui acesso à edição destes dados.');</script>";
                                        echo "<script>window.location.replace('internPage.php')</script>";
                                    }
                                    //fazer select se já está permitido, se não
                                    //deixar recado que já foi pedida a edição                                    
                                }  

                                $query = 'SELECT name_person FROM person WHERE id_person = :id';

                                $stmt = $conn->prepare($query);

                                $stmt->bindValue(':id', $id_person);

                                $stmt->execute();

                                $return = $stmt->fetch(PDO::FETCH_ASSOC);

                                $name_person = $return['name_person'];

                                $query = 'SELECT * FROM internship_data WHERE id_internship_data = :id AND finished = FALSE';
                            
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
                                            
                                            <label for='lunch_time' class='lead fw-normal'>Horas de almoço:</label>  
                                            <input type='time' id='lunch_time' class='form-control' name='lunch_time' value='".$return['lunch_time']."'required><br>
            
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
                            else {
                            header('Location: internPage.php');
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

    <script src="../../js/intern.js"></script>
</body>
</html>