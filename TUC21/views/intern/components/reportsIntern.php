<?php

session_start();
if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 1){
    echo "<script>alert('Usuário não reconhecido.');</script>";
    header("Location: ../../../index.php ");
    exit();
}

require_once('../../../app/db/connect.php');
require_once('../../../app/php/functions.php');

//Verificando se o estágio foi validado
$query = "SELECT * FROM internship_data WHERE fk_student = ".$_SESSION["idUser"]." AND validated_company = TRUE AND validated_advisor = TRUE AND finished = FALSE";

$stmt = $conn->prepare($query);

$stmt->execute();

$return = $stmt->fetch(PDO::FETCH_ASSOC);



if($return == null)
{
    echo "
    <h2>Relatórios</h2>
    <br>
    <p>Você não possui acesso a essa página pois a validação do seu estágio ainda está pendente. Retorne assim que receber a mensagem de que a validação foi efetivada!</p>";
    exit();
}

//recuperando o ID da empresa
$query = "SELECT * FROM internship_data WHERE fk_student = ".$_SESSION["idUser"]."";

$stmt = $conn->prepare($query);

$stmt->execute();

$return = $stmt->fetch(PDO::FETCH_ASSOC);

$id_intern = $_SESSION["idUser"];
$id_company = $return["fk_company"];
$course = $return["course_internship_data"];
$id_internship_data = $return["id_internship_data"];

//pegando as informações da empresa
$query = "SELECT * FROM company WHERE id_company = ".$id_company."";

$stmt = $conn->prepare($query);

$stmt->execute();

$return_company = $stmt->fetch(PDO::FETCH_ASSOC);

//pegando as informações do estagiário
$query = "SELECT s.*, p.* FROM student s, person p WHERE p.id_person = s.fk_id AND p.id_person =".$id_intern."";

$stmt = $conn->prepare($query);

$stmt->execute();

$return_intern = $stmt->fetch(PDO::FETCH_ASSOC);

//data atual
$current_day = date('d'); 
$current_month = date('m'); 
$current_year = date('Y'); 

if ($current_month == "01") 
    $mes = "Janeiro";    
else if ($current_month == "02")
    $mes = "Fevereiro";
else if ($current_month == "03")
    $mes = "Março";
else if ($current_month == "04")
    $mes = "Abril";
else if ($current_month == "05")
    $mes = "Maio";
else if ($current_month == "06")
    $mes = "Junho";
else if ($current_month == "07")
    $mes = "Julho";
else if ($current_month == "08")
    $mes = "Agosto";
else if ($current_month == "09")
    $mes = "Setembro";
else if ($current_month == "10")
    $mes = "Outubro";
else if ($current_month == "11")
    $mes = "Novembro    ";
else if ($current_month == "12")
    $mes = "Dezembro";
?>
<h2>Relatórios</h2>

<p>
    Página de upload de relatórios necessários ao longo do estágio
</p>

<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sendReportModal">Upload de relatórios</button>

<br><br>

<div class="modal fade" id="sendReportModal" tabindex="-1" aria-labelledby="sendReportModal" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content" style="width:600px;">
            <div class="modal-header">
                <h5 class="modal-title" id="send-report">Enviar Relatório</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../app/php/email/sendEmailReportsIntern.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="report-name" class="col-form-label">Selecione o tipo de relatório:</label>
                        <select class='form-control' id='report-name' name='report-name'>
                            <option value="Relatório de Bimestral">Relatório Bimestral</option>
                            <option value="Declaração de Situação Funcional">Declaração de Situação Funcional</option>
                            <option value="Finalização de estágio - Avaliação do Estagiário">Finalização de estágio - Avaliação do Estagiário</option>
                            <option value="Finalização de estágio - Conteúdo">Finalização de estágio - Conteúdo</option>
                            <!-- <option value="Questionário">Questionário</option> -->
                            <?php
                                $query = "SELECT * FROM internship_reports WHERE fk_internship_data = ".$id_internship_data." AND type_internship_report = 'Relatório Final (Obrigatório)' AND denied_internship_report = FALSE";

                                $stmt = $conn->prepare($query);

                                $stmt->execute();

                                $return = $stmt->fetch(PDO::FETCH_ASSOC);
                                if(!$return)
                                    echo "<option value='Questionário'>Questionário</option>";
                            ?>
                        </select>   
                    </div>
                    <div class="mb-3">
                        <label for="company-name" class="col-form-label">Selecione o estágio:</label>
                        <?php      
                        
                            $query = "SELECT id_internship_data, name_internship_data FROM internship_data WHERE fk_student = ".$_SESSION["idUser"]."";

                            $stmt = $conn->prepare($query);
                        
                            $stmt->execute();
                        
                            $return = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                            $filterPerson = Array();
                        
                            foreach($return as $person) {
                                array_push($filterPerson, $person);
                            }
                        
                            $count = count($filterPerson);
                            //echo var_dump($filterPerson);
                        
                            if($count > 0) {
                                echo "<select class='form-control' id='id-internship-data' name='id-internship-data'>";

                                foreach($filterPerson as $key => $person) {  
                                    echo "<option value='".$person["id_internship_data"]."'>".$person["name_internship_data"]."</option>";   
                                }
                                echo "</select>";   
                            }
                        ?>
                    </div>  
                    <div class="mb-3">
                        <!--<select class='form-control' id='message-subject' name='version-report'>
                            <option value='1'>Versão 1</option>
                            <option value='2'>Versão 2</option>
                            <option value='3'>Versão 3</option>
                        </select>
                        <label for="message-text" class="col-form-label">Link do Relatório no Drive (em PDF): <font color="red">*</font></label>
                        <input type="text" class="form-control" id="report-link" name="report-link" required>
                        <p>Antes de copiar o link no campo acima, deve-se permitir o acesso ao documento por todos que possuem o link, entrando na aba 'compartilhar'</p>-->
                        <label for="file-report" class="col-form-label">Selecione o arquivo de seu relatório:</label>
                        <input type="file" class="form-control" id="file-report" name="file-report" />
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

<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nome do Documento</th>
            <th scope="col">Download em Word</th>           
        </tr>
    </thead>
    <tbody>
       <tr>
            <th scope="row">1</th>
            <td>Relatório Bimestral</td>
            <td><a href="reports/Modelo-Relatorio Bimestral.docx" download class="btn btn-primary text-white" data-toggle="tooltip" data-placement="top" title="Baixar em Word"> 
                <i class="fas fa-download"></i>
            </a>
            </td>
        </tr>
        <tr>
            <th scope="row">2</th>
            <td>Declaração de Situação Funcional</td>
            <td><a href="reports/Declaracao de Situação Funcional.docx" download class="btn btn-primary text-white" data-toggle="tooltip" data-placement="top" title="Baixar em Word"><i class="fa fa-download"></i></a></td>
        </tr>
        <tr>
            <th scope="row">3</th>
            <td>Finalizar estágio - Avaliação do Estagiário</td>
            <td><a href="reports/02 - Avaliação do Estagiário.docx" download class="btn btn-primary text-white" data-toggle="tooltip" data-placement="top" title="Baixar em Word"><i class="fa fa-download"></i></a></td>
        </tr>
        <tr>
            <th scope="row">4</th>
            <td>Finalizar estágio - Conteúdo</td>
            <td><a href="reports/04 - Conteúdo.docx" download class="btn btn-primary text-white" data-toggle="tooltip" data-placement="top" title="Baixar em Word"><i class="fa fa-download"></i></a></td>
        </tr>
        <tr>
            <th scope="row">5</th>
            <td>Questionário</td>
            <td>
                
                <?php
                //pegando as informações do estagiário
                $query = "SELECT * FROM internship_reports WHERE fk_internship_data =".$id_internship_data." AND type_internship_report = 'Relatório Final (Obrigatório)' AND denied_internship_report = FALSE";

                $stmt = $conn->prepare($query);

                $stmt->execute();

                $return = $stmt->fetch(PDO::FETCH_ASSOC);

                if($return && isset($return["supervisor_signature_internship_report"]) && isset($return["advisor_signature_internship_report"]) && isset($return["coordinator_signature_internship_report"]))
                    echo '<a href="'.$return["link_internship_report"].'" download class="btn btn-primary text-white" data-toggle="tooltip" data-placement="top" title="Baixar em Word"><i class="fa fa-eye"></i></a>';           
                else 
                {
                    echo '<a href="reports/05 - Questionário.docx" download class="btn btn-primary text-white" data-toggle="tooltip" data-placement="top" title="Baixar em Word"><i class="fa fa-download"></i></a>';
                }
                ?>
                
            </td>
        </tr>
    </tbody>
</table>