<?php 
    session_start();
    if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 1){
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

    // Selecionando Instituição de ensino em que o aluno estuda
    $id_student = $_SESSION['idUser']; //id student

    $query = "SELECT * FROM student WHERE fk_id = $id_student";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC);

    $id_university = $return['fk_university'];

    // Informações Instituições de ensino em que o professor trabalha
    $query = "SELECT * FROM university WHERE id_university = $id_university";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC);

    $idHex = codeId($id_university);

    if($return['valid']) {
        echo '<h2 class="py-2">Informações - '.$return['name_university'].'</h2>';
            
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
                <label for='email_university' class='lead fw-normal'>Email:</label>
                <input type='text' id='email_university' class='form-control' name='email_university' value='".$return['email_university']."' disabled><br> 

            </div>
            </div>
        ";   

        echo $form;     
    } 
    else {
        echo '<div class="col-12 h5 mt-5 text-secondary text-center">Nenhuma Intituição de Ensino Encontrada</div>';
        //header('Location: studentPage.php');
    }

