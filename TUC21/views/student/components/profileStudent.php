<?php
session_start();
if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 1){
    echo "<script>alert('Usuário não reconhecido.');</script>";
    header("Location: ../../../index.php ");
    exit();
}
?>

<h2>Perfil</h2>

<p>
    Página de consulta dos dados pessoais
</p>

<?php

    require_once('../../../app/db/connect.php');
    require_once('../../../app/php/functions.php');

    $id = $_SESSION['idUser'];

    //Dados da pessoa

    $query = "SELECT * FROM person WHERE id_person = $id";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC);

    //Dados do estudante

    $query2 = "SELECT * FROM student WHERE fk_id = $id";

    $stmt2 = $conn->prepare($query2);

    $stmt2->execute();

    $return2 = $stmt2->fetch(PDO::FETCH_ASSOC);

    $idHex = codeId($id);

    if($return['valid']) {

        echo '<h2 class="py-2">Informações - '.$return['name_person'].' 
                <a href="studentEdit.php?type=person&id='.$idHex.'" > <!--botão de edição -->
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
                    <label for='cep-student' class='lead fw-normal'>CEP:</label>
                    <input type='text' id='cep-student' class='form-control' name='cep-student' value='".$return2['cep_student']."' disabled><br>
                    <label for='city-student' class='lead fw-normal'>Cidade:</label>
                    <input type='text' id='city-student' class='form-control' name='city-student' value='".$return2['city_student']."' disabled><br>
                </div>
                <div class='col-lg-6 col-sm-12'>
                    <label for='cpf-person' class='lead fw-normal'>CPF:</label>
                    <input type='text' id=''cpf-person' class='form-control' name='cpf-person' value='".$return['cpf_person']."' disabled><br>
                    <label for='rg-person' class='lead fw-normal'>RG:</label>
                    <input type='text' id='rg-person' class='form-control' name='rg-person' value='".$return['rg_person']."' disabled><br>
                    <label for='treatment-person' class='lead fw-normal'>Tratamento:</label>
                    <input type='text' id='treatment-person' class='form-control' placeholder='Ex: Doutor' name='treatment-person' value='".$return['treatment_person']."' disabled><br>
                    <label for='address-student' class='lead fw-normal'>Endereço:</label>
                    <input type='text' id='address-student' class='form-control' name='address-student' value='".$return2['address_student']."' disabled><br>
                    <label for='district-student' class='lead fw-normal'>Distrito:</label>
                    <input type='text' id='district-student' class='form-control' name='district-student' value='".$return2['district_student']."' disabled><br>
                </div> 
                </div> 
        ";

        echo $form;
    }
    else
    {
        echo '<div class="col-12 h5 mt-5 text-secondary text-center">Nenhuma Informação Encontrada</div>';
    }

    $id_person = $_SESSION['idUser'];

    //echo "Id: ".$id_person;

    $query = "SELECT * FROM change_data WHERE fk_id = ".$id_person." AND pending_allowance = TRUE OR allowed = TRUE OR blocked_edition = TRUE";
   
    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($return == null)
    {
        echo "<p>
        Você não possui permissão para editar seus dados. Deseja enviar uma solicitação?
        </p>";

        echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#sendEmailModal'>Solicitar edição</button>";

        $query = "SELECT p.name_person, s.fk_professor FROM person p, student s WHERE s.fk_id = ".$id_person." AND s.fk_id = p.id_person";
   
        $stmt = $conn->prepare($query);

        $return = $stmt->execute();

        $return = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $id_professor = $return[0]['fk_professor'];
        $name_student = $return[0]['name_person'];

        $query = "SELECT email_person FROM person WHERE id_person = ".$id_professor."";

        $stmt = $conn->prepare($query);

        $return = $stmt->execute();

        $return = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $email_professor = $return[0]['email_person'];

        echo "<div class='modal fade' id='sendEmailModal' tabindex='-1' aria-labelledby='sendEmailModalLabel' aria-hidden='true'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='sendEmailModalLabel'>Permissão para Edição</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                    <form action='../../app/php/email/sendEmailStudent.php' method='POST'> 
                        <div class='modal-body'>
                            <div class='mb-3'>
                                <label for='message-email' class='col-form-label'>E-mail:</label>
                                <input type='email' class='form-control' id='message-email' name='message-email' value=".$email_professor.">
                            </div>
                            <div class='mb-3'>
                                <label for='message-subject' class='col-form-label'>Assunto:</label>
                                <input type='text' class='form-control' id='message-subject' name='message-subject' value='Estagio CTI'>
                            </div>
                            <div class='mb-3'>
                                <label for='message-text' class='col-form-label'>Conteúdo:</label>
                                <textarea class='form-control' id='message-text' name='message-text' style='height:200px;'>O(A) aluno(a) ".$name_student." está pedindo permissão para modificar seus dados pessoais. \n Acesse sua conta no Sistema de Controle de Estágio, entre na página HOME e clique no botão de PERMITIR EDIÇÃO para responder ao pedido.</textarea>
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
?>