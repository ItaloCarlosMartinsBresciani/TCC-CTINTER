<?php 
    session_start();
    if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 1){
        echo "<script>alert('Usuário não reconhecido.');</script>";
        header("Location: ../../../index.php ");
        exit();
    }
    

?>

<h2>Supervisor</h2>

<p>
    Página de visualização de seu supervisor
</p>

<?php

    require_once('../../../app/db/connect.php');
    require_once('../../../app/php/functions.php');

    $id = $_SESSION['idUser'];

    try{
        //pegando o id do orientador
        $query = "SELECT fk_supervisor FROM internship_data WHERE fk_student = $id";

        $stmt = $conn->prepare($query);

        $stmt->execute();

        $return = $stmt->fetch(PDO::FETCH_ASSOC);

        $id_supervisor = $return["fk_supervisor"];

        // var_dump ($id_supervisor);

        
        if ($id_supervisor == NULL){
            echo '<div class="col-12 mt-5 text-secondary text-center">Você ainda não possui um supervisor. <br>Peça para sua empresa designar um funcionário como seu supervisor!</div>';
            exit();
        }
        
        //pegando as informações do orientador
        $query = "SELECT p.* FROM person p, company_employee c  WHERE p.id_person = ".$id_supervisor." AND p.id_person = c.fk_id";

        $stmt = $conn->prepare($query);

        $stmt->execute();

        $return = $stmt->fetch(PDO::FETCH_ASSOC);

        $idHex = codeId($id);

        if($return['valid']) {

            $form = "
            <div class='row'>
                <div class='w-100 rounded'>
                    <label for='name-supervisor' class='lead fw-normal'>Nome:</label>
                    <input type='text' id='name-supervisor' class='form-control' name='name-supervisor' value='".$return['name_person']."' disabled><br>
                    <label for='email-supervisor' class='lead fw-normal'>E-mail:</label>
                    <a href='mailto:".$return['email_person']."?subject=Hello%20again'>
                        <input type='email' id='email-supervisor' class='form-control' name='email-supervisor' value='".$return['email_person']."'  data-toggle='tooltip' data-placement='top' title='Clique para enviar um e-mail' disabled>
                    </a><br>
                    <label for='telephone-supervisor' class='lead fw-normal'>Telefone:</label>
                    <input type='tel' id='telephone-supervisor' class='form-control' name='telephone-supervisor' value='".$return['telephone_person']."' disabled><br>
                    <label for='treatment-supervisor' class='lead fw-normal'>Tratamento:</label>
                    <input type='text' id='treatment-supervisor' class='form-control' name='treatment-supervisor' value='".$return['treatment_person']."' disabled><br>
                </div> 
            </div>
            ";

            echo $form;
        }
    }
    catch(Exception $ex)
    {
        echo $ex->getMessage();
    }
?>