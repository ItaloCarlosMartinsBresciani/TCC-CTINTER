<?php
    session_start();

    if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 7){
        echo "<script>alert('Usuário não reconhecido.');</script>";
        header("Location: ../../index.php ");
        exit();
    }

    
    

    require_once('../../app/db/connect.php');
    require_once('../../app/php/functions.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informação - Supervisor</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../public/bootstrap/bootstrap.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../../public/css/sidebar.css">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a39639353a.js" crossorigin="anonymous"></script>
</head>
<body>
  <div id="wrapper" class="p-0">
      <div id="navbar-wrapper">
          <nav class="navbar navbar-inverse">
              <div class="container-fluid">
                  <div class="navbar-header">
                      <a href="supervisorPage.php" class="navbar-brand" style="font-size: 24px;"><i class="fas fa-arrow-circle-left"></i></a>
                  </div>
              </div>
          </nav>
    </div>

    <?php
      if (isset($_GET['type']) && isset($_GET['id'])) { //o tipo de usuário que quer ver informação e seu id
        $type = cleanString($_GET['type']);

    echo "<script>alert(".$type.");</script>";
        try {
          $idHex = cleanString($_GET['id']);

          $idDec = decodeId($idHex);
        }
        catch (TypeError) {
          header('Location: supervisorPage.php');
        }   
      } else {
        header('Location: supervisorPage.php');
      }
    ?>

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 py-2" id="content">                        
                        <?php
                            if ($type == 'person') {
                              // Edição de Pessoas
                              
                              $query = 'SELECT * FROM person WHERE id_person = :id';

                              $stmt = $conn->prepare($query);

                              $stmt->bindValue(':id', $idDec);

                              $stmt->execute();

                              $return = $stmt->fetch(PDO::FETCH_ASSOC);
                              
                              if($return['valid']) {
                                
                                echo '<h2 class="py-2">Informações - '.$return['name_person'].' 
                                        <a href="supervisorEdit.php?type=person&id='.$idHex.'"> <!--botão de edição -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                                            </svg>
                                        </a>
                                    </h2>     
                                ';

                                $form = "
                                <div class='row'>
                                      <div class='col-lg-6 col-sm-12'>
                                          <label for='name-person' class='lead fw-normal'>Nome:</label>
                                          <input type='text' id='name-person' class='form-control' name='name-person' value='".$return['name_person']."' disabled><br>
                                          <label for='email-person' class='lead fw-normal'>Email:</label>
                                          <input type='email' id='email-person' class='form-control' name='email-person' value='".$return['email_person']."' disabled><br>
                                          <label for='telephone-person' class='lead fw-normal'>Telefone:</label>
                                          <input type='tel' id='telephone-person' class='form-control' name='telephone-person' value='".$return['telephone_person']."' disabled><br>
                                      </div>
                                      <div class='col-lg-6 col-sm-12'>
                                          <label for='cpf-person' class='lead fw-normal'>CPF:</label>
                                          <input type='text' id=''cpf-person' class='form-control' name='cpf-person' value='".$return['cpf_person']."' disabled><br>
                                          <label for='rg-person' class='lead fw-normal'>RG:</label>
                                          <input type='text' id='rg-person' class='form-control' name='rg-person' value='".$return['rg_person']."' disabled><br>
                                          <label for='treatment-person' class='lead fw-normal'>Tratamento:</label>
                                          <input type='text' id='treatment-person' class='form-control' placeholder='Ex: Doutor' name='treatment-person' value='".$return['treatment_person']."' disabled><br>
                                      </div> 
                                      </div> 
                                ";
                                
                                echo $form;
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
                                    
                                    echo '<h2 class="py-2">Informações - '.$return['name_person'].' 
                                        </h2>     
                                    ';

                                    $form = "
                                    <div class='row'>
                                        <div class='col-lg-6 col-sm-12'>
                                            <label for='name-person' class='lead fw-normal'>Nome:</label>
                                            <input type='text' id='name-person' class='form-control' name='name-person' value='".$return['name_person']."' disabled><br>
                                            <label for='email-person' class='lead fw-normal'>Email:</label>
                                            <input type='email' id='email-person' class='form-control' name='email-person' value='".$return['email_person']."' disabled><br>
                                            <label for='telephone-person' class='lead fw-normal'>Telefone:</label>
                                            <input type='tel' id='telephone-person' class='form-control' name='telephone-person' value='".$return['telephone_person']."' disabled><br>
                                        </div>
                                        <div class='col-lg-6 col-sm-12'>
                                            <label for='cpf-person' class='lead fw-normal'>CPF:</label>
                                            <input type='text' id=''cpf-person' class='form-control' name='cpf-person' value='".$return['cpf_person']."' disabled><br>
                                            <label for='rg-person' class='lead fw-normal'>RG:</label>
                                            <input type='text' id='rg-person' class='form-control' name='rg-person' value='".$return['rg_person']."' disabled><br>
                                            <label for='treatment-person' class='lead fw-normal'>Tratamento:</label>
                                            <input type='text' id='treatment-person' class='form-control' placeholder='Ex: Doutor' name='treatment-person' value='".$return['treatment_person']."' disabled><br>
                                        </div> 
                                        </div> 
                                    ";
                            
                                    echo $form;

                                }

                                $query = 'SELECT * FROM internship_data WHERE fk_student = :id';
                            
                                $stmt = $conn->prepare($query);

                                $stmt->bindValue(':id', $idDec);

                                $stmt->execute();

                                $return = $stmt->fetch(PDO::FETCH_ASSOC);  
                                
                                if($return)
                                {
                                    if($return['nature_internship_data'] == true)
                                    {
                                        
                                        echo '<h3 class="py-2">Informações - Estágio obrigatório de '.$name_person.' 
                                            </h3>';
                                    }
                                    else
                                    {
                                        
                                    echo '<h3 class="py-2">Informações - Estágio não obrigatório de '.$name_person.' 
                                            <a href="supervisorEdit.php?type=intern&id='.$idHex.'"> <!--botão de edição -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                                                </svg>
                                            </a>
                                        </h3>';
                                        
                                    }
                                    
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
                                    ";
                            
                                    echo $form;
                                }                               

                                } else if ($type == 'company') {
                            // Edição de Empresas
                            } else if($type == "reports"){
                                
                                if($_SESSION["email"] == 1)
                                {
                                   header('Location: supervisorPage.php');
                                }
                                $query = 'SELECT p.name_person, ir.* FROM person p, internship_reports ir, internship_data id WHERE p.id_person = :id AND p.id_person = id.fk_student AND ir.fk_internship_data = id.id_internship_data ';

                                $stmt = $conn->prepare($query);
  
                                $stmt->bindValue(':id', $idDec);
  
                                $stmt->execute();
  
                                $return = $stmt->fetch(PDO::FETCH_ASSOC);

                                echo '<h2 class="py-2">Relatórios - '.$return['name_person'].' </h2>     
                                ';

                                $query = "SELECT r.* FROM person p, internship_data i, internship_reports r WHERE p.id_person = i.fk_student AND i.fk_student = ".$idDec." AND i.fk_supervisor = ".$_SESSION["idUser"]." AND r.fk_internship_data = i.id_internship_data";

                                $stmt = $conn->prepare($query);
                            
                                $stmt->execute();
                            
                                $return = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                                $filterReport = Array();
                            
                                
                                foreach($return as $report) {
                                    array_push($filterReport, $report);
                                }
                            
                                $count = count($filterReport);
                                //echo var_dump($filterPerson);

                                $line = 1;
                                $cont = 0;
                                if($count > 0) {
                                    echo '
                                    <table class="table table-striped">
                                        <thead class="table-dark">
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Tipo de Relatório</th>
                                                <th scope="col">Data de Criação</th>
                                                <th scope="col">Versão</th>
                                                <th scope="col">Link do relatório</th>
                                                <th scope="col">Definir status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="person-content">';
                            

                                    //aprovar | negar | atualizar 
                                    foreach($filterReport as $key => $report) {  
                                        $idHex = codeId($report['id_internship_reports']);
                                        $date = date('d/m/Y', strtotime($report['date_internship_report']));
                                        if($report['supervisor_signature_internship_report'] == null && $report['denied_internship_report'] != TRUE)
                                        {
                                            if($report['type_internship_report'] == 'Relatório Final (Obrigatório)')
                                            {
                                                $idFinalReport = $report['id_internship_reports'];
                                                $_SESSION['link'] = $report['link_internship_report'];
                                                $action = '<a href="../visualizeReport.php?type='.$report['type_internship_report'].'&id='.$idHex.'" class="btn btn-primary" style="margin-right: 18px;">
                                                Atualizar Documento
                                            </a>';
                                            }
                                            else 
                                            {
                                                $action = '<a href="../../app/php/supervisor/logic/allowReportLogic.php?type=allow&id='.$idHex.'" class="btn btn-primary" style="margin-right: 18px;">
                                                                Aprovar
                                                            </a>
                                                            <a href="../../app/php/supervisor/logic/allowReportLogic.php?type=deny&id='.$idHex.'" class="btn btn-primary">
                                                                Negar
                                                            </a>';
                                            }
                                            echo '
                                                <tr>
                                                    <th scope="row">'.($line).'</th>
                                                    <td>'. $report['type_internship_report'].'</td>
                                                    <td>'. $date.'</td>
                                                    <td>'. $report['version_internship_report'].'</td>
                                                    <td><a href="../../app/php/email/reports_upload/'.$report['link_internship_report'].'" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Visualizar relatório."> Visualizar relatório</a></td>
                                                    <td>
                                                        '.$action.'
                                                    </td>
                                                </tr>
                                                ';
                                            
                                            $line++;
                                        }
                                        else
                                        {
                                            $cont++;
                                        }
                                    }
                            
                                    echo '
                                        </tbody>
                                    </table>
                                    ';

                                    if ($cont == $count)
                                    {
                                        echo '<div class="col-12 h5 mt-5 text-secondary text-center">Nenhum relatório encontrado</div>';   
                                    }
                                }
                                else
                                {
                                    echo '<div class="col-12 h5 mt-5 text-secondary text-center">Nenhum relatório encontrado</div>';
                                }
                            }else if($type == "internship_data"){
                                if($_SESSION["email"] == 1)
                                {
                                   header('Location: supervisorPage.php');
                                }
                                $query = "SELECT * FROM internship_data WHERE id_internship_data = ".$idDec."";

                                $stmt = $conn->prepare($query);

                                $stmt->execute();
                                
                                $return = $stmt->fetch(PDO::FETCH_ASSOC);  
                            
                                if ($return['scholarship_internship_data'] == "True")
                                {
                                    $radio_scholarship = "<input type='radio' name='scholarship_internship_data' id='scholarship-internship-data' value='True' style='font-size:40px;' checked disabled> Sim<br> 
                                                          <input type='radio' name='scholarship_internship_data' id='scholarship-internship-data' value='False' disabled> Não<br>
                                                          <br>";
                                }
                                else
                                {
                                    $radio_scholarship = "<input type='radio' name='scholarship_internship_data' id='scholarship-internship-data' value='True' style='font-size:40px;' disabled> Sim<br> 
                                                          <input type='radio' name='scholarship_internship_data' id='scholarship-internship-data' value='False' checked disabled> Não<br>
                                                          <br>";
                                }
                
                                if($return['nature_internship_data'] == "True")
                                {
                                    $radio_nature = "<input type='radio' name='nature_internship_data' id='nature-internship-data' value='True' style='font-size:40px;' checked disabled> Obrigatório<br> 
                                                     <input type='radio' name='nature_internship_data' id='nature-internship-data' value='False' disabled> Não Obrigatório<br>
                                                     <br>";
                                }
                                else
                                {
                                    $radio_nature = "<input type='radio' name='nature_internship_data' id='nature-internship-data' value='True' style='font-size:40px;' disabled> Obrigatório<br> 
                                                            <input type='radio' name='nature_internship_data' id='nature-internship-data' value='False' checked disabled> Não Obrigatório<br>
                                                            <br>";
                                }
                                
                                $start_date = date_create($return['start_date_internship_data']);
                                $end_date = date_create($return['end_date_internship_data']);
                                //echo codeId($return["fk_student"]);
                                echo "
                                <h2>Estágio</h2>   
                                <form action='../../app/php/email/sendEmailSupervisor.php' method='GET' name='form' id='form'>   
                                
                                    <label class='lead fw-normal'>Deseja validar estas informações?</label> &nbsp;  
                                   
                                    <input type='radio' name='type' id='type' onClick='submit()' value='validate_internship' style='transform: scale(1.5);'> Sim &nbsp;&nbsp;
                                    <input type='radio' name='type' id='type' onClick='submit()' value='invalidate_internship' style='transform: scale(1.5);'> Não 
                                    <input type='hidden' name='id' value='".codeId($idDec)."'>
                                </form>
                                <br>
                                <div class='row'>
                                    <div class='col-lg-6 col-sm-12'>

                                        
                                        <label for='role-internship-data' class='lead fw-normal'>Função:</label>
                                        <input type='text' id='role-internship-data' class='form-control' placeholder = 'Ex: Programador, Analista de Sistemas...' name='role_internship_data' value='".$return['role_internship_data']."' disabled><br>
                                        
                                        <label for='area-internship-data' class='lead fw-normal'>Área de atuação:</label>  
                                        <input type='text' id='area-internship-data' class='form-control' placeholder = 'Ex: Informática, Sistemas Elétricos...' name='area_internship_data' value='".$return['area_internship_data']."' disabled><br>
                                     
                                        <label for='start-date-internship-data' class='lead fw-normal'>Data de início:</label>  
                                        <input type='date' id='start-date-internship-data' class='form-control' name='start_date_internship_data' value='".$return["start_date_internship_data"]."' disabled><br>
                                        

                                        <label for='scholarship-internship-data' class='lead fw-normal'>Recebe bolsa?</label><br>
                                        ".$radio_scholarship."
                                        
                                        <label for='start-date-internship-data' class='lead fw-normal'>Natureza do estágio:</label><br>
                                        ".$radio_nature."
                                   
                                        </div>
                                    <div class='col-lg-6 col-sm-12'>
                                    
                                        <label for='week-hours-internship-data' class='lead fw-normal'>Horas semanais:</label>
                                        <input type='number' id='week-hours-internship-data' class='form-control'  name='week_hours_internship_data' min='0' max='30' value='".$return['week_hours_internship_data']."' disabled><br>
                                    
                                        <label for='total-hours-internship-data' class='lead fw-normal'>Total aproximado de horas de estágio:</label>  
                                        <input type='number' id='total-hours-internship-data' class='form-control' name='total_hours_internship_data'  max='2880' min='0' value='".$return['total_hours_internship_data']."' disabled><br>
                                    
                                        <label for='end-date-internship-data' class='lead fw-normal'>Data de término:</label>  
                                        <input type='date' id='end-date-internship-data' class='form-control' name='end_date_internship_data' value='".$return["end_date_internship_data"]."' disabled><br>

                                        <label for='scholarship-value-internship-data' class='lead fw-normal'>Valor da bolsa:</label>
                                        <input type='number' id='scholarship-value-internship-data' class='form-control'  name='scholarship_value_internship_data' value='".$return['scholarship_value_internship_data']."' disabled><br>
                                    
                                        <label for='description-internship-data' class='lead fw-normal'>Descrição das atividades:</label>
                                        <textarea id='description-internship-data' class='form-control'  name='description_internship_data' required style='height:110px;' disabled>".$return['description_internship_data']."</textarea><br>

                                    </div>
                                </div>               
                                
                                
                                ";                
                            }
                            

                            else {
                            header('Location: supervisorPage.php');
                            } 
                        ?>
                    <!--div class="modal fade" id="sendReportModal" tabindex="-1" aria-labelledby="sendReportModal" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content" style="width:600px;">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="send-report">Enviar Relatório</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="../../app/php/email/sendEmailReportSupervisor.php" method="POST" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="report-name" class="col-form-label">Tipo de relatório:</label>
                                                <select class='form-control' id='report-name' name='report-name'>
                                                    <option value="Relatório Final (Obrigatório)">Relatório Final (estágio obrigatório I e II)</option>
                                                </select>   
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="file-report" class="col-form-label">Selecione o arquivo de seu relatório:</label>
                                                <input type="file" class="form-control" id="file-report" name="file-report"/>
                                                <input type="hidden" class="form-control" id="id-internship" name="id-internship" value=<?php echo $idFinalReport; ?>>
                                                <p>Atenção, os arquivos só podem conter no máxino 2MB!</p>
                                            </div>
                                            
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                            <input type="submit" class="btn btn-primary" value="Enviar relatório">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div-->
            </div>
        </div> 
    </div>
    <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Bootstrap -->
    <script src="../../public/bootstrap/bootstrap.min.js"></script>

    <script src="../../js/supervisor.js"></script>
</body>
</html>