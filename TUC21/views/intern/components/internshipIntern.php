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
$query = "SELECT * FROM internship_data WHERE fk_student = ".$_SESSION["idUser"]." AND validated_company = TRUE AND validated_advisor = TRUE";

$stmt = $conn->prepare($query);

$stmt->execute();

$return = $stmt->fetch(PDO::FETCH_ASSOC);

if($return == null)
{
    echo "
    <h2>Estágio</h2>
    <br>
    <p>Você não possui acesso a essa página pois a validação do seu estágio ainda está pendente. Retorne assim que receber a mensagem de que a validação foi efetivada!</p>";
    exit();
}
?>

<h2>Estágio</h2>

<p>
    Página de consulta dos dados do estágio
</p>

<?php


    try
    {
        $id_person = $_SESSION['idUser'];

        $query = 'SELECT * FROM internship_data WHERE fk_student = :id and valid = TRUE';
                                
        $stmt = $conn->prepare($query);

        $stmt->bindValue(':id', $id_person);

        $stmt->execute();

        $return = $stmt->fetch(PDO::FETCH_ASSOC);  

        $id_internship = $return['id_internship_data'];
        
        if($return)
        {
            $idHex = codeId($id_internship);
            $name_internship = $return["name_internship_data"];
            $fk_supervisor = $return["fk_supervisor"];
            echo '<h3 class="py-2">Informações - Estágio '.$name_internship.' 
                    <a href="internEdit.php?type=internship&id='.$idHex.'"> <!--botão de edição -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                            <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                        </svg>
                    </a>
                </h3>
                ';
                
            
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
                </div>
                <div class='col-lg-6 col-sm-12'>
                    ".$scholarship."
                    
                    <label for='end-date-internship-data' class='lead fw-normal'>Data de término:</label>
                    <input type='date' id='end-date-internship-data' class='form-control'  name='end-date-internship-data' value='".$return['end_date_internship_data']."' disabled><br>
                    <label for='total-hours-internship-data' class='lead fw-normal'>Total de horas:</label>
                    <input type='number' id='total-hours-internship-data' class='form-control'  name='total-hours-internship-data' value='".$return['total_hours_internship_data']."' disabled><br>
                    <label for='description-internship-data' class='lead fw-normal'>Descrição: </label>
                    <textarea id='description-internship-data' class='form-control' name='description-internship-data'  style='height:130px;' disabled>".$return['description_internship_data']."</textarea><br>

                </div> 
            </div> 
            ";

            echo $form;
        }

        //Vendo se solicitação está permitida
        $query = "SELECT * FROM change_data_internship WHERE fk_id = ".$id_internship." AND pending_allowance = TRUE OR allowed = TRUE OR blocked_edition = TRUE";
    
        $stmt = $conn->prepare($query);

        $stmt->execute();

        $return = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($return == null)
        {
            echo "<p>
            Você não possui permissão para editar os dados do seu estágio. Deseja enviar uma solicitação?
            </p>";

            echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#sendEmailModal'>Solicitar edição</button>";

            //selecionando o nome do estagiário
            $query = "SELECT p.name_person FROM person p WHERE p.id_person = ".$id_person;
    
            $stmt = $conn->prepare($query);

            $return = $stmt->execute();

            $return = $stmt->fetch(PDO::FETCH_ASSOC);

            $name_intern = $return['name_person'];

            //selecionando o nome do supervisor
            $query = "SELECT email_person FROM person WHERE id_person = ".$fk_supervisor."";

            $stmt = $conn->prepare($query);

            $return = $stmt->execute();

            $return = $stmt->fetch(PDO::FETCH_ASSOC);

            $email_supervisor = $return['email_person'];

            echo "<div class='modal fade' id='sendEmailModal' tabindex='-1' aria-labelledby='sendEmailModalLabel' aria-hidden='true'>
                <div class='modal-dialog'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title' id='sendEmailModalLabel'>Permissão para Edição</h5>
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                        </div>
                        <form action='../../app/php/email/sendEmailIntern.php?type=internship' method='POST'> 
                            <div class='modal-body'>
                                <div class='mb-3'>
                                    <label for='message-email' class='col-form-label'>E-mail:</label>
                                    <input type='email' class='form-control' id='message-email' name='message-email' value=".$email_supervisor.">
                                </div>
                                <div class='mb-3'>
                                    <label for='message-subject' class='col-form-label'>Assunto:</label>
                                    <input type='text' class='form-control' id='message-subject' name='message-subject' value='Estagio CTI'>
                                </div>
                                <div class='mb-3'>
                                    <label for='message-text' class='col-form-label'>Conteúdo:</label>
                                    <textarea class='form-control' id='message-text' name='message-text' style='height:200px;'>O(A) aluno(a) ".$name_intern." está pedindo permissão para modificar os dados relativos ao estágio ".$name_internship.". \n Acesse sua conta no Sistema de Controle de Estágio, entre na página HOME e clique no botão de PERMITIR EDIÇÃO para responder ao pedido.</textarea>
                                </div>
                            </div>

                            <div class='modal-footer'>
                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Fechar</button>
                                <input type='submit' class='btn btn-primary' value='Enviar E-mail'>
                            </div>
                        </form>
                    </div>
                </div>
            </div>";
        }
    }
    catch(Exception $ex)
    {
        echo "Houve um erro na seleção de informações no banco!<br>";
        echo $ex->getMessage();
        
    }
?>