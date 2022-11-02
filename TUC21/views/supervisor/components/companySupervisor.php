<?php 
    session_start();
    if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 7){
        echo "<script>alert('Usuário não reconhecido.');</script>";
        header("Location: ../../../index.php ");
        exit();
    }
    
    
?>

<h2>Empresa</h2>

<p>
    Página das informações de sua Empresa
</p>

<?php
    require_once('../../../app/db/connect.php');
    require_once('../../../app/php/functions.php');

    // Selecionando Instituições de ensino em que o professor trabalha
    $id_supervisor = $_SESSION['idUser']; //id professor

    $query = "SELECT * FROM company_employee WHERE fk_id = $id_supervisor";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC);

    $id_company = $return['fk_company'];

    // Informações Instituições de ensino em que o professor trabalha
    $query = "SELECT * FROM company WHERE id_company = $id_company";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC);

    $idHex = codeId($id_company);

    if($return['valid']) {
        echo '<h2 class="py-2">Informações - '.$return['name_company'].'</h2>';
            
        $form = 
        "
            <div class='row'>
            <div class='col-lg-6 col-sm-12'>
                <label for='cnpj_company' class='lead fw-normal'>CNPJ:</label>
                <input type='text' id='cnpj_company' class='form-control' name='cnpj_company' value='".$return['cnpj_company']."' disabled><br>

                <label for='name_university' class='lead fw-normal'>Nome:</label>
                <input type='text' id='name_company' class='form-control' name='name_company' value='".$return['name_company']."' disabled><br>
                
                <label for='address_company' class='lead fw-normal'>Endereço:</label>
                <input type='text' id='address_company' class='form-control' name='address_company' value='".$return['address_company']."' disabled><br>

                <label for='number_company' class='lead fw-normal'>Número:</label>
                <input type='text' id='number_company' class='form-control' name='number_company' value='".$return['number_company']."' disabled><br>

                <label for='district_company' class='lead fw-normal'>Bairro:</label>
                <input type='text' id='district_company' class='form-control' name='district_company' value='".$return['district_company']."' disabled><br>

                <label for='city_company' class='lead fw-normal'>Cidade:</label>
                <input type='text' id='city_company' class='form-control' name='city_company' value='".$return['city_company']."' disabled><br>

              
            </div>
            <div class='col-lg-6 col-sm-12'>
                <label for='cep_company' class='lead fw-normal'>CEP:</label>
                <input type='text' id='cep_company' class='form-control' name='cep_company' value='".$return['cep_company']."' disabled><br>

                <label for='state_registration_company' class='lead fw-normal'>Registro Estadual:</label>
                <input type='text' id='state_registration_company' class='form-control' name='state_registration_company' value='".$return['state_registration_company']."' disabled ><br>

                
                <label for='branch_line_company' class='lead fw-normal'>Ramal:</label>
                <input type='text' id='branch_line_company' class='form-control' name='branch_line_company' value='".$return['branch_line_company']."' disabled><br>

                <label for='telephone_company' class='lead fw-normal'>Telefone:</label>
                <input type='tel' id='telephone_company' class='form-control' name='telephone_company' value='".$return['telephone_company']."' disabled><br>

                <label for='email_company' class='lead fw-normal'>E-mail:</label>
                <input type='text' id='email_company' class='form-control' name='email_company' value='".$return['email_company']."' disabled><br> 

                <label for='state_company' class='lead fw-normal'>Estado:</label>
                <select  class='form-control' name='state_company' id='state_company' disabled>
                    <option selected value='".$return['state_company']."'>".$return['state_company']."</option>
                </select> <br>

            </div>
            </div>
        ";   

        echo $form;     
    } else {
        header('Location: supervisorPage.php');
    }

