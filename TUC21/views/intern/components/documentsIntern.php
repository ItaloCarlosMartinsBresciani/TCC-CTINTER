

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
$query = "SELECT * FROM internship_data WHERE fk_student = ".$_SESSION["idUser"]." AND validated_company = TRUE AND validated_advisor = TRUE AND validated_coordinator = TRUE AND finished = FALSE";

$stmt = $conn->prepare($query);

$stmt->execute();

$return = $stmt->fetch(PDO::FETCH_ASSOC);



if($return == null)
{
    echo "
    <h2>Documentos</h2>
    <br>
    <p>Você não possui acesso a essa página pois a validação do seu estágio ainda está pendente. Retorne assim que receber a mensagem de que a validação foi efetivada!</p>";
    exit();
}




// if($return == null)
// {
//     echo "
//     <h2>Documentos</h2>
//     <br>
//     <p>Você não possui acesso a essa página pois a validação do seu estágio ainda está pendente. Retorne assim que receber a mensagem de que a validação foi efetivada!</p>";
//     exit();
// }

$id_internship = $return["id_internship_data"];
//$id_internship = 5;

//recuperando o ID da empresa
$query = "SELECT * FROM internship_data WHERE fk_student = ".$_SESSION["idUser"]."";

$stmt = $conn->prepare($query);

$stmt->execute();

$return = $stmt->fetch(PDO::FETCH_ASSOC);

$id_intern = $_SESSION["idUser"];
$id_company = $return["fk_company"];
$course = $return["course_internship_data"];

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


$query = "SELECT * FROM internship_plan WHERE fk_internship_data = ".$_SESSION["idUser"]."";

$stmt = $conn->prepare($query);

$stmt->execute();

$return_sus = $stmt->fetch(PDO::FETCH_ASSOC);

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


<h2>Documentos</h2>

<p>
    Página de download dos documentos necessários ao longo do estágio
</p><br>


<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nome do Documento</th>
            <th scope="col">Download PDF</th>                     
        </tr>
    </thead>
    <tbody>

        <tr>
            <th scope="row">1</th>
            <td>Protocolo de Estágio</td>
            <td>
                <a href="documents/protocolo_de_estagio.php" class="btn btn-primary text-white" data-toggle="tooltip" data-placement="top" title="Baixar em PDF">
                    <i class="fas fa-file-pdf"></i>
                </a>
           </td>
        </tr>

       <tr> 
        
            <th scope="row">2</th>
            <td>Plano de Estágio</td>
            <td>
            <!-- -->

                <?php

                $query = "SELECT * FROM internship_plan WHERE fk_internship_data = ".$id_internship." AND denied_internship_plan = FALSE";
                
                $stmt = $conn->prepare($query);

                $stmt->execute();

                $return_plan = $stmt->fetch(PDO::FETCH_ASSOC);
                 
                $idHex = codeId($return_plan['id_internship_plan']);


                if ($return_plan)
                {
                    echo '<a href="documents/proposta_de_plano_de_estagio.php?id='.$idHex.'" class="btn btn-primary text-white" data-toggle="tooltip" data-placement="top" title="Baixar em PDF">
                            <i class="fas fa-file-pdf"></i>
                          </a>';
                }
                else
                {
                    echo '<a href="../putInternshipPlanInformation_i.php" class="btn btn-primary text-white" data-btoggle="tooltip" data-placement="top" title="Baixar em PDF">
                            <i class="fas fa-file-pdf"></i>
                          </a>';
                }
            
                
                ?>
            </td>
           

    </tr>

        <tr>
            <th scope="row">3</th>
            <td>Convênio de Estágio</td>
            <td>
                <a href="documents/convenio_de_estagio.php" class="btn btn-primary text-white" data-toggle="tooltip" data-placement="top" title="Baixar em PDF">
                    <i class="fas fa-file-pdf"></i>
                </a>
            </td>
           
        </tr>

        <tr>
            <th scope="row">4</th>
            <td>Termo de Compromisso</td>
            <td>
                <a href="documents/termo_de_compromisso.php" class="btn btn-primary text-white" data-toggle="tooltip" data-placement="top" title="Baixar em PDF">
                    <i class="fas fa-file-pdf"></i>
                </a>
            </td>
            
        </tr>

        <tr>
            <th scope="row">5</th>
            <td>Termo de Aditivo de Estágio - Alterar Data final de estágio</td>
            <td>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AddInfoMMEModal"><i class="fas fa-file-pdf"></i></button>
                <div class="modal fade" id="AddInfoMMEModal" tabindex="-1" aria-labelledby="AddInfoMMEModal" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content" style="width:600px;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="send-report">Preencher Informações Necessárias ao Documento:</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="documents/termo_aditivo_alterar_data.php" method="POST" enctype="multipart/form-data" required>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="change_date_modality" class="col-form-label">Mudança de data do estágio:</label>
                                    <input type="date" class="form-control" id="change_date_modality" name="change_date_modality" required>
                                </div> 
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                <input type="submit" class="btn btn-primary" value="Enviar">
                            </div>
                        </form>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <th scope="row">6</th>
            <td>Termo de Aditivo de Estágio - Outros fins</td>
            <td>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AddInfoSCEModal"><i class="fas fa-file-pdf"></i></button>
                <div class="modal fade" id="AddInfoSCEModal" tabindex="-1" aria-labelledby="AddInfoSCEModal" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content" style="width:600px;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="send-report">Preencher Informações Necessárias ao Documento:</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="documents/termo_aditivo_outros_fins.php" method="POST" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="reason" class="col-form-label">Outros fins:</label>
                                    <textarea type="text"  class="form-control" id="reason" name="reason" Placeholder="Outras alterações relacionadas ao estágio" required></textarea>
                                </div> 
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                <input type="submit" class="btn btn-primary" value="Enviar">
                            </div>
                        </form>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <th scope="row">7</th>
            <td>Finalizar estágio - Certificado de estágio</td>
            <td>
                <a href="documents/certificado_de_estagio.php" class="btn btn-primary text-white" data-toggle="tooltip" data-placement="top" title="Baixar em PDF">
                    <i class="fas fa-file-pdf"></i>
                </a>
            </td>
        </tr>

        <tr>
            <th scope="row">8</th>
            <td>Finalizar estágio - Apresentação</td>
            <td>
                <a href="documents/apresentacao.php" class="btn btn-primary text-white" data-toggle="tooltip" data-placement="top" title="Baixar em PDF">
                    <i class="fas fa-file-pdf"></i>
                </a>
            </td>
        </tr>

        <tr>
            <th scope="row">9</th>
            <td>Finalizar estágio - Protocolo de Estágio</td>
            <td>
                <a href="documents/finalizacao_protocolo_de_estagio.php" class="btn btn-primary text-white" data-toggle="tooltip" data-placement="top" title="Baixar em PDF">
                    <i class="fas fa-file-pdf"></i>
                </a>
            </td>
        </tr>
    </tr>
    </tbody>
</table>