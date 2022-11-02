<?php 
    session_start();
    if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 1){
        echo "<script>alert('Usuário não reconhecido.');</script>";
        header("Location: ../../../index.php ");
        exit();
    }
    

?>

<h2>Orientador</h2>

<p>
    Página de visualização de seu orientador
</p>

<?php

    require_once('../../../app/db/connect.php');
    require_once('../../../app/php/functions.php');

    $id = $_SESSION['idUser'];

    //pegando o id do orientador
    $query = "SELECT fk_advisor FROM internship_data WHERE fk_student = $id";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC);

    $idAdvisor = $return["fk_advisor"];

    //pegando as informações do orientador
    $query = "SELECT p.*, a.department_advisor FROM person p, advisor a  WHERE id_person = $idAdvisor AND p.id_person = a.fk_id";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC);

    $idHex = codeId($id);

    if($return['valid']) {

        $form = "
        <div class='row'>
            <div class='w-100 rounded'>
                <label for='name-advisor' class='lead fw-normal'>Nome:</label>
                <input type='text' id='name-advisor' class='form-control' name='name-advisor' value='".$return['name_person']."' disabled><br>
                <label for='email-advisor' class='lead fw-normal'>E-mail:</label>
                <a href='mailto:".$return['email_person']."?subject=Hello%20again'>
                    <input type='email' id='email-advisor' class='form-control' name='email-advisor' value='".$return['email_person']."'  data-toggle='tooltip' data-placement='top' title='Clique para enviar um e-mail' disabled>
                </a><br>
                <label for='telephone-advisor' class='lead fw-normal'>Telefone:</label>
                <input type='tel' id='telephone-advisor' class='form-control' name='telephone-advisor' value='".$return['telephone_person']."' disabled><br>
                <label for='treatment-advisor' class='lead fw-normal'>Tratamento:</label>
                <input type='text' id='treatment-advisor' class='form-control' name='treatment-advisor' value='".$return['treatment_person']."' disabled><br>
                <label for='department-advisor' class='lead fw-normal'>Departamento:</label>
                <input type='text' id='department-advisor' class='form-control' name='department-advisor' value='".$return['department_advisor']."' disabled><br>
            </div> 
        </div>
        ";

        echo $form;
    }
?>