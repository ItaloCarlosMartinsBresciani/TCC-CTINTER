<?php 
    session_start();
    if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 1){
        echo "<script>alert('Usuário não reconhecido.');</script>";
        header("Location: ../../../index.php ");
        exit();
    }
    
    
?>

<h2>Coordenador de Estágio da Universidade</h2>

<p>
    Página de visualização dos dados do Coordenador de Estágio
</p>

<div id="person-content">
    <?php

        require_once('../../../app/db/connect.php');
        require_once('../../../app/php/functions.php');
    
        $id = $_SESSION['idUser'];
    
        try{
            //pegando o id do coordenador
            $query = "SELECT u.fk_id FROM university_employee u, student s WHERE s.fk_id = $id AND s.fk_university = u.fk_university AND role_university_employee = 'Coordenador'";
        
            $stmt = $conn->prepare($query);
        
            $stmt->execute();
        
            $return = $stmt->fetch(PDO::FETCH_ASSOC);
        
            $id_coordinator = $return['fk_id'];
        
            //pegando as informações do coordenador
            $query = "SELECT * FROM person WHERE id_person = ".$id_coordinator;
        
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
</div>