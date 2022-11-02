<?php
session_start();
if(!isset($_SESSION['isAuth'])){
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

    $query = "SELECT * FROM company WHERE id_company = $id";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC);

    $name_company = $return['name_company'];

    $idHex = codeId($id);

    if($return) {

        echo '<h2 class="py-2">Informações - '.$return['name_company'].' 
                <a href="companyEdit.php?type=company&id='.$idHex.'"> <!--botão de edição -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                    </svg>
                </a>
            </h2>     
        ';

        $form = "
        <div class='row'>
                <div class='col-lg-6 col-sm-12'>
                    <label for='name-company' class='lead fw-normal'>Nome:</label>
                    <input type='text' id='name-company' class='form-control' name='name-company' value='".$return['name_company']."' disabled><br>
                    <label for='email-company' class='lead fw-normal'>E-mail:</label>
                    <input type='email' id='email-company' class='form-control' name='email-company' value='".$return['email_company']."' disabled><br>
                    <label for='telephone-company' class='lead fw-normal'>Telefone:</label>
                    <input type='tel' id='telephone-company' class='form-control' name='telephone-company' value='".$return['telephone_company']."' disabled><br>
                    <label for='branch-line-company' class='lead fw-normal'>Ramal:</label>
                    <input type='tel' id='branch-line-company' class='form-control' name='branch-line-company' value='".$return['branch_line_company']."' disabled><br>
                    <label for='cnpj-company' class='lead fw-normal'>CNPJ:</label>
                    <input type='text' id=''cnpj-company' class='form-control' name='cnpj-company' value='".$return['cnpj_company']."' disabled><br>
                    <label for='state-company' class='lead fw-normal'>Estado:</label>
                    <input type='text' id='state-company' class='form-control' name='state-company' value='".$return['state_company']."' disabled><br>
                </div>
                <div class='col-lg-6 col-sm-12'>
                <label for='cep-company' class='lead fw-normal'>CEP:</label>
                    <input type='text' id=''cep-company' class='form-control' name='cep-company' value='".$return['cep_company']."' disabled><br>
                    <label for='address-company' class='lead fw-normal'>Endereço:</label>
                    <input type='text' id='address-company' class='form-control' name='address-company' value='".$return['address_company']."' disabled><br>
                    <label for='number-company' class='lead fw-normal'>Número:</label>
                    <input type='text' id='number-company' class='form-control' name='number-company' value='".$return['number_company']."' disabled><br>
                    <label for='district-company' class='lead fw-normal'>Bairro:</label>
                    <input type='text' id='district-company' class='form-control' name='district-company' value='".$return['district_company']."' disabled><br>
                    <label for='city-company' class='lead fw-normal'>Cidade:</label>
                    <input type='text' id='city-company' class='form-control' name='city-company' value='".$return['city_company']."' disabled><br>

                </div> 
                </div> 
        ";

        echo $form;
    }

    $id_person = $_SESSION['idUser'];

    //echo "Id: ".$id_person;

    $query = "SELECT * FROM change_data_companies WHERE fk_id = ".$id." AND pending_allowance = TRUE OR allowed = TRUE OR blocked_edition = TRUE";
   
    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($return == null)
    {
        echo "<p>
        Você não possui permissão para editar seus dados. Deseja enviar uma solicitação?
        </p>";

        echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#sendEmailModal'>Solicitar edição</button>";

        $email_admin = $ENV_MAIL_USER;

        echo "<div class='modal fade' id='sendEmailModal' tabindex='-1' aria-labelledby='sendEmailModalLabel' aria-hidden='true'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='sendEmailModalLabel'>Permissão para Edição</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                    <form action='../../app/php/email/sendEmailCompany.php?type=editionRequest' method='POST'> 
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
                                <textarea class='form-control' id='message-text' name='message-text' style='height:200px;'>A empresa ".$name_company." está pedindo permissão para modificar seus dados. \nAcesse sua conta no Sistema de Controle de Estágio, entre na página HOME e clique no botão de PERMITIR EDIÇÃO para responder ao pedido.</textarea>
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