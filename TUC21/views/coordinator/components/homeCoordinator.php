<?php 
    session_start();
    if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 9){
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
    
    $name_coordinator = $return['name_person'];

?>
<p>Bem-vindo(a), <?php echo $name_coordinator?>!</p>


<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function(){
        setInterval(function(){
            $("#count").load('homeCoordinator.php')
        }, 1000);
    });
</script>

<?php


    require_once('../../../app/db/connect.php');
    require_once('../../../app/php/functions.php');

    $query = "SELECT fk_university FROM university_employee WHERE fk_id = ".$_SESSION["idUser"];

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC);

    $id_university = $return['fk_university'];

    $query = "SELECT i.* FROM internship_data i, student s WHERE i.validated_advisor = TRUE AND i.validated_company = TRUE AND i.validated_coordinator = FALSE AND i.fk_student = s.fk_id AND s.fk_university = ".$id_university;

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $cont = 0;
    foreach($return as $return_internship){

        if($return_internship["description_internship_data"] != null && $return_internship["validated_coordinator"] != TRUE && $return_internship["validated_advisor"] == TRUE && $return_internship["validated_company"] == TRUE){
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
?>


<div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendEmailModalLabel">Analisar Informações de Estágio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            <form action="coordinatorInfo.php" method="GET"> 
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="hidden"  class="form-control" name="type" value="internship_data">
                       
                        
                        <label for="internship-id" class="col-form-label">Selecione o nome do estagiário cujo estágio deseja analisar:</label>
                        <?php
                            $query = "SELECT i.id_internship_data, p.name_person, description_internship_data  FROM internship_data i, person p, student s WHERE i.fk_student = p.id_person AND i.validated_advisor = TRUE AND i.validated_company = TRUE AND i.validated_coordinator = FALSE AND s.fk_id = i.fk_student AND s.fk_university = ".$id_university;
                            
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