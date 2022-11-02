<?php 
    session_start();
    if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 2){
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
    
    $name_professor = $return['name_person'];

?>
<p>Bem-vindo(a), <?php echo $name_professor?>!</p>


<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function(){
        setInterval(function(){
            $("#count").load('homeProfessor.php')
        }, 1000);
    });
</script>

<?php


    require_once('../../../app/db/connect.php');
    require_once('../../../app/php/functions.php');

    $query = "SELECT * FROM internship_data WHERE fk_advisor = ".$_SESSION["idUser"]." AND validated_advisor = FALSE AND validated_company = TRUE";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $cont = 0;
    foreach($return as $return_internship){

        if($return_internship["description_internship_data"] != null && $return_internship["validated_advisor"] == FALSE && $return_internship["validated_company"] == TRUE){
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

    $query = 'SELECT p.id_person, p.name_person, p.email_person  
                FROM person p, student s, internship_data i, change_data c 
                WHERE p.id_person = c.fk_id 
		        AND c.fk_id = s.fk_id
                AND p.id_person = s.fk_id
                AND s.fk_id != i.fk_student 
                AND c.pending_allowance = TRUE 
                AND p.access_level = 1 
                AND s.fk_professor = '.$_SESSION["idUser"].'
                AND i.fk_advisor = '.$_SESSION["idUser"].';';

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetchAll(PDO::FETCH_ASSOC); 

    $filterPerson = Array();

    foreach($return as $person) {
        array_push($filterPerson, $person);
    }

    $count = count($filterPerson);

    echo "<h3 style='margin-top:15px;'>Solicitações de Edição</h3>";
    
    $aux = 0; //variável para continuar a contegem nas duas tabelas

    if($count > 0) {
        echo '
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">E-mail</th>
                    <th scope="col">Edição</th>
                </tr>
            </thead>
            <tbody id="person-content">';

        foreach($filterPerson as $key => $person) {
            //$date = date('d/m/Y', strtotime($person['register_date']));
            $idHex = codeId($person['id_person']);

            // if (!$return_advisor)
            // {
            //     if($person[''] )
            //     {
                    
            //     }
            // }

            echo '
            <tr>
                <th scope="row">'.($key + 1).'</th>
                <td>'. $person['name_person'].'</td>
                <td>
                    <a href="mailto:'.$person['email_person'].'?subject=Hello%20again" class="btn btn-primary text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                            <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>
                        </svg>
                    </a>
                </td>
                <td>
                    <a href="../../app/php/professor/logic/allowEditionLogic.php?type=allow&id='.$idHex.'" class="btn btn-primary">
                        Permitir
                    </a>
                    <a href="../../app/php/professor/logic/allowEditionLogic.php?type=deny&id='.$idHex.'" class="btn btn-primary">
                        Negar
                    </a>
                </td>
            </tr>
            ';
            $aux = $key;
        }
    } 
    $aux += 1;

    $query = "SELECT * FROM advisor where fk_id = ".$_SESSION["idUser"]."";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return_advisor = $stmt->fetch(PDO::FETCH_ASSOC);

    if($return_advisor)
    {
        $query = 'SELECT p.id_person, p.name_person, p.email_person  FROM person p, internship_data i, change_data c WHERE p.id_person = i.fk_student AND c.fk_id = i.fk_student AND i.fk_advisor = '.$_SESSION["idUser"].' AND c.pending_allowance = TRUE ';
        $stmt = $conn->prepare($query);

        $stmt->execute();

        $return = $stmt->fetchAll(PDO::FETCH_ASSOC); 

        $filterPerson = Array();

        foreach($return as $person) {
            array_push($filterPerson, $person);
        }

        $count_ad = count($filterPerson);

        if($count_ad > 0) {
            if ($count <= 0)
            {
                echo '
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Edição</th>
                        </tr>
                    </thead>
                    <tbody id="person-content">';
            }

            foreach($filterPerson as $key => $person) {
                //$date = date('d/m/Y', strtotime($person['register_date']));
                $idHex = codeId($person['id_person']);

                // if (!$return_advisor)
                // {
                //     if($person[''] )
                //     {
                        
                //     }
                // }

                echo '
                <tr>
                    <th scope="row">'.($key + 1 + $aux).'</th>
                    <td>'. $person['name_person'].'</td>
                    <td>
                        <a href="mailto:'.$person['email_person'].'?subject=Hello%20again" class="btn btn-primary text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                                <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>
                            </svg>
                        </a>
                    </td>
                    <td>
                        <a href="../../app/php/professor/logic/allowEditionLogic.php?type=allow&id='.$idHex.'" class="btn btn-primary">
                            Permitir
                        </a>
                        <a href="../../app/php/professor/logic/allowEditionLogic.php?type=deny&id='.$idHex.'" class="btn btn-primary">
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
        } 
    }
    else if ($count > 0)
    {
        echo '
            </tbody>
        </table>
        ';
    }

    if ($count <= 0 && $count_ad <= 0) {
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
            <form action="professorInfo.php" method="GET"> 
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="hidden"  class="form-control" name="type" value="internship_data">
                       
                        
                        <label for="internship-id" class="col-form-label">Selecione o nome do estagiário cujo estágio deseja analisar:</label>
                        <?php
                            $query = "SELECT i.id_internship_data, p.name_person, description_internship_data  FROM internship_data i, person p WHERE i.fk_student = p.id_person AND i.fk_advisor = ".$_SESSION['idUser']." AND i.validated_advisor = FALSE AND i.validated_company = TRUE";
                            
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