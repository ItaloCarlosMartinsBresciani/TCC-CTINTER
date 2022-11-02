<?php 
    session_start();
    if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 10){
        echo "<script>alert('Usuário não reconhecido.');</script>";
        header("Location: ../../../index.php ");
        exit();
    }
    
    
?>

<h2>Instituição de Ensino</h2>

<p>
    Página das informações de sua instituições de ensino
</p>

<?php
    require_once('../../../app/db/connect.php');
    require_once('../../../app/php/functions.php');
    require_once('../../../app/php/email/envEmail.php');

    // Informação de Instituições de ensino
    $id_principal = $_SESSION['idUser']; //id diretor

    $query = "SELECT * FROM university WHERE fk_principal = $id_principal";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC);

    $id_university = $return['id_university'];
    
    $idHex = codeId($id_university);

    if($return['valid']) {
        echo '<h2 class="py-2">Informações - '.$return['name_university'].'
                <a href="principalEdit.php?type=university&id='.$idHex.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                    </svg>
                </a>
            </h2>
        ';
            
        $form = 
        "
            <div class='row'>
            <div class='col-lg-6 col-sm-12'>
                <label for='cnpj_university' class='lead fw-normal'>CNPJ:</label>
                <input type='text' id='cnpj_university' class='form-control' name='cnpj_university' value='".$return['cnpj_university']."' disabled><br>
                <label for='name_university' class='lead fw-normal'>Nome:</label>
                
                <input type='text' id='name_university' class='form-control' name='name_university' value='".$return['name_university']."' disabled><br>
                
                <label for='state_registration_university' class='lead fw-normal'>Inscrição estadual:</label>
                <input type='text' id='state_registration_university' class='form-control' name='state_registration_university' value='".$return['state_registration_university']."' disabled><br>
                <label for='corporate_name_university' class='lead fw-normal'>Razão social:</label>
                <input type='text' id='corporate_name_university' class='form-control' name='corporate_name_university' value='".$return['corporate_name_university']."' disabled><br>
                <label for='legal_representative_university' class='lead fw-normal'>Representante legal:</label>
                <input type='text' id='legal_representative_university' class='form-control' name='legal_representative_university' value='".$return['legal_representative_university']."' disabled><br>
                <label for='activity_branch_university' class='lead fw-normal'>Ramo de atividade:</label>
                <input type='text' id='activity_branch_university' class='form-control' name='activity_branch_university' value='".$return['activity_branch_university']."' disabled><br>
                <label for='address_university' class='lead fw-normal'>Endereço:</label>
                <input type='text' id='address_university' class='form-control' name='address_university' value='".$return['address_university']."' disabled><br>
                <label for='home_page_university' class='lead fw-normal'>Homepage:</label>
                <input type='text' id='home_page_university' class='form-control' name='home_page_university' value='".$return['home_page_university']."' disabled><br>
            </div>
            <div class='col-lg-6 col-sm-12'>
                <label for='district_university' class='lead fw-normal'>Bairro:</label>
                <input type='text' id='district_university' class='form-control' name='district_university' value='".$return['district_university']."' disabled ><br>
                <label for='cep_university' class='lead fw-normal'>CEP:</label>
                <input type='text' id='cep_university' class='form-control' name='cep_university' value='".$return['cep_university']."' disabled><br>
                <label for='mailbox_university' class='lead fw-normal'>Caixa postal:</label>
                <input type='text' id='mailbox_university' class='form-control' name='mailbox_university' value='".$return['mailbox_university']."' disabled><br>
                <label for='city_university' class='lead fw-normal'>Cidade:</label>
                <input type='text' id='city_university' class='form-control' name='city_university' value='".$return['city_university']."' disabled ><br>
                <label for='state_university' class='lead fw-normal'>Estado:</label>
                <select  class='form-control' name='state_university' id='state_university' disabled>
                    <option selected value='".$return['state_university']."'>".$return['state_university']."</option>
                </select> <br>
                <label for='telephone_university' class='lead fw-normal'>Telefone:</label>
                <input type='tel' id='telephone_university' class='form-control' name='telephone_university' value='".$return['telephone_university']."' disabled><br>
                <label for='email_university' class='lead fw-normal'>E-mail:</label>
                <input type='text' id='email_university' class='form-control' name='email_university' value='".$return['email_university']."' disabled><br> 

            </div>
            </div>
        ";   

        echo $form;  
        
        $query = "SELECT * FROM change_data_universities WHERE fk_id = ".$id_university." AND pending_allowance = TRUE OR allowed = TRUE OR blocked_edition = TRUE";
   
        $stmt = $conn->prepare($query);
    
        $stmt->execute();
    
        $return = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        if($return == null)
        {
            echo "<p>
            Você não possui permissão para editar os dados da universidade. Deseja enviar uma solicitação?
            </p>";
    
            echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#sendEmailModal'>Solicitar edição</button>";
    
            //-------------------------------------------------------------------------------------------
            //Selecionando o nome da universidade
            $query = "SELECT u.name_university, p.name_person FROM university u, person p WHERE u.id_university = ".$id_university." AND p.id_person = ".$id_principal."";
       
            $stmt = $conn->prepare($query);
    
            $return = $stmt->execute();
    
            $return = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $name_university = $return[0]['name_university'];
            $name_principal = $return[0]['name_person'];
    
            $email_admin = $ENV_MAIL_USER;
    
            echo "<div class='modal fade' id='sendEmailModal' tabindex='-1' aria-labelledby='sendEmailModalLabel' aria-hidden='true'>
                <div class='modal-dialog'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title' id='sendEmailModalLabel'>Permissão para Edição</h5>
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                        </div>
                        <form action='../../app/php/email/sendEmailPrincipal.php?type=editionRequestUniversity' method='POST'> 
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
                                    <textarea class='form-control' id='message-text' name='message-text' style='height:200px;'>O(A) diretor(a) ".$name_principal." da universidade ".$name_university." está pedindo permissão para modificar os dados da universidade. \n Acesse sua conta no Sistema de Controle de Estágio, entre na página HOME e clique no botão de PERMITIR EDIÇÃO para responder ao pedido.</textarea>
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
    
        
    } else {
        header('Location: principalPage.php');
    }

    ?>
