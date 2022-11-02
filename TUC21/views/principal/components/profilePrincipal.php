<?php
session_start();
if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 10){
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
    require_once('../../../app/php/email/envEmail.php');

    $id = $_SESSION['idUser'];

    $query = "SELECT * FROM person WHERE id_person = $id";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC);

    $idHex = codeId($id);

    if($return['valid']) {

        echo '<h2 class="py-2">Informações - '.$return['name_person'].' 
                <a href="principalEdit.php?type=profile&id='.$idHex.'"> <!--botão de edição -->
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
                    <label for='email-person' class='lead fw-normal'>E-mail:</label>
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

    $id_person = $_SESSION['idUser'];

    $query = "SELECT * FROM change_data WHERE fk_id = ".$id_person." AND (pending_allowance = TRUE OR allowed = TRUE OR blocked_edition = TRUE)";
   
    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC);

    if($return == null)
    {
        echo "<p>
        Você não possui permissão para editar seus dados. Deseja enviar uma solicitação?
        </p>";

        echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#sendEmailModal'>Solicitar edição</button>";

        //-------------------------------------------------------------------------------------------
        //Selecionando o nome do diretor e o id da universidade
        $query = "SELECT p.name_person, u.fk_university FROM person p, university_employee u WHERE u.fk_id = ".$id_person." AND u.fk_id = p.id_person";
   
        $stmt = $conn->prepare($query);

        $return = $stmt->execute();

        $return = $stmt->fetch(PDO::FETCH_ASSOC);

        $id_university = $return['fk_university'];
        
        $name_principal = $return['name_person'];

        
        //------------------------------------------------------------------------------------------
        //Selecionando o email do diretor da universidade
        /*$query = "SELECT p.email_person FROM person p, university_employee u WHERE u.fk_university = ".$id_university." AND p.id_person = u.fk_id AND p.access_level = 10 AND p.deleted = FALSE";
        
        $stmt = $conn->prepare($query);

        $return = $stmt->execute();

        $return = $stmt->fetchAll(PDO::FETCH_ASSOC);*/

        $email_admin = $ENV_MAIL_USER;

        echo "<div class='modal fade' id='sendEmailModal' tabindex='-1' aria-labelledby='sendEmailModalLabel' aria-hidden='true'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='sendEmailModalLabel'>Permissão para Edição</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                    <form action='../../app/php/email/sendEmailPrincipal.php?type=editionRequest' method='POST'> 
                        <div class='modal-body'>
                            <div class='mb-3'>
                                <label for='message-email' class='col-form-label'>E-mail:</label>
                                <input type='email' class='form-control' id='message-email' name='message-email' value=".$email_admin.">
                            </div>
                            <div class='mb-3'>
                                <label for='message-subject' class='col-form-label'>Assunto:</label>
                                <input type='text' class='form-control' id='message-subject' name='message-subject' value='Estagio CTI'>
                            </div>
                            <div class='mb-3'>
                                <label for='message-text' class='col-form-label'>Conteúdo:</label>
                                <textarea class='form-control' id='message-text' name='message-text' style='height:200px;'>O(A) diretor(a) ".$name_principal." está pedindo permissão para modificar seus dados pessoais. \n Acesse sua conta no Sistema de Controle de Estágio, entre na página HOME e clique no botão de PERMITIR EDIÇÃO para responder ao pedido.</textarea>
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