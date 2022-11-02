<?php 
    session_start();
    if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 7){
        echo "<script>alert('Usuário não reconhecido.');</script>";
        header("Location: ../../../index.php ");
        exit();
    }
    
    
?>

<h2>Home</h2>

<?php
    require_once('../../../app/db/connect.php');
    require_once('../../../app/php/functions.php');

    $query = "SELECT name_person FROM person WHERE id_person = ".$_SESSION['idUser'];
   
    $stmt = $conn->prepare($query);

    $return = $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $name_supervisor = $return['name_person'];

?>
<p>Bem-vindo(a), <?php echo $name_supervisor?>!</p>


<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function(){
        setInterval(function(){
            $("#count").load('homeSupervisor.php')
        }, 1000);
    });
</script>



<?php


    require_once('../../../app/db/connect.php');
    require_once('../../../app/php/functions.php');

    $query = "SELECT * FROM internship_data WHERE fk_supervisor = ".$_SESSION["idUser"]." AND validated_advisor = FALSE AND validated_company = FALSE";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $cont = 0;
    foreach($return as $return_internship){

        if($return_internship["description_internship_data"] != null && $return_internship["validated_advisor"] == FALSE && $return_internship["validated_company"] == FALSE){
            $cont++;
        }
    }  
    if ($cont > 0)
    {
        echo "
        <br>
            <h4>Informações do Estágio</h4>

            <p>
                O estagiário acabou de adicionar os dados ao nosso sistema, deseja analisar as informações?
            </p>

            <button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#sendEmailModal'>Analisá-las</button><br><br>";
            
    }

    $query = 'SELECT i.id_internship_data, i.name_internship_data, p.name_person FROM internship_data i, change_data_internship c, person p WHERE p.id_person = fk_student AND i.id_internship_data = c.fk_id AND c.pending_allowance = TRUE';

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetchAll(PDO::FETCH_ASSOC); 

    $filterInternship = Array();

    foreach($return as $internship) {
        array_push($filterInternship, $internship);
    }

    $count = count($filterInternship);
    
    echo "<br><h3>Solicitações de Edição</h3>";

    if($count > 0) {
        echo '
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Estagiário</th>
                    <th scope="col">Edição</th>
                </tr>
            </thead>
            <tbody id="person-content">';

        foreach($filterInternship as $key => $internship) {
            //$date = date('d/m/Y', strtotime($person['register_date']));
            $idHex = codeId($internship['id_internship_data']);

            echo '
            <tr>
                <th scope="row">'.($key + 1).'</th>
                <td>'. $internship['name_internship_data'].'</td>
                <td>'. $internship['name_person'].'</td>
                <td>
                    <a href="../../app/php/supervisor/logic/allowEditionLogic.php?type=allow&id='.$idHex.'" class="btn btn-primary">
                        Permitir
                    </a>
                    <a href="../../app/php/supervisor/logic/allowEditionLogic.php?type=deny&id='.$idHex.'" class="btn btn-primary">
                        Negar
                    </a>
                </td>
            </tr>
            ';
        }

        echo '
            </tbody>
        </table>
        ';
    } else {
        echo '<div class="col-12 h5 mt-5 text-secondary text-center">Nenhuma solicitação encontrada</div>';
    }
?>


<div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendEmailModalLabel">Analisar Informações de Estágio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            <form action="supervisorInfo.php" method="GET"> 
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="hidden"  class="form-control" name="type" value="internship_data">
                       
                        
                        <label for="internship-id" class="col-form-label">Selecione o nome do estagiário cujo estágio deseja analisar:</label>
                        <?php
                            $query = "SELECT i.id_internship_data, p.name_person, description_internship_data  FROM internship_data i, person p WHERE i.fk_student = p.id_person AND i.fk_supervisor = ".$_SESSION['idUser']." AND i.validated_advisor = FALSE AND i.validated_company = FALSE";
                            
                            $stmt = $conn->prepare($query);

                            $stmt->execute();
                            
                            $return = $stmt->fetchAll(PDO::FETCH_ASSOC);                            
                            

                            $filterInternship = Array();

                            foreach($return as $internship) {
                                array_push($filterInternship, $internship);
                            }
                        
                            $count = count($filterInternship);
                        
                            if($count > 0) 
                            {
                                echo "<select class='form-control' id='id' name='id'>";

                                foreach($filterInternship as $internship) {  
                                    if ($internship["description_internship_data"] != null)
                                        echo "<option value=".codeId($internship["id_internship_data"]).">".$internship["name_person"]."</option>";
                                }
                                echo "</select>";                                 
                            }
                        ?>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <input type="submit" class="btn btn-primary" value="Enviar">
                </div>
            </form>
        </div>
    </div>
</div>

