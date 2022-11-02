<?php 
    session_start();
    if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 2){
        echo "<script>alert('Usuário não reconhecido.');</script>";
        header("Location: ../../../index.php ");
        exit();
    }

require_once('../../../app/db/connect.php');
require_once('../../../app/php/functions.php');

$query = "SELECT * FROM advisor where fk_id = ".$_SESSION["idUser"]."";

$stmt = $conn->prepare($query);

$stmt->execute();

$return = $stmt->fetchAll(PDO::FETCH_ASSOC);

$filterPerson = Array();

foreach($return as $person) {
    array_push($filterPerson, $person);
}

$count = count($filterPerson);

if($count <= 0)
{
    echo "<h2>Orientador</h2>";

    echo"<p>
            Você ainda não foi registrado no sistema de estágio. Caso deseje se cadastrar, clique no botão abaixo.
        </p>";

        

    echo '<a href="../putAdvisorInformation.php?id_advisor='.$_SESSION['idUser'].'">
            <input type="button" class="btn btn-primary" value="Cadastrar-se"> 
          </a>';
}
else 
{
    echo "<h2>Orientandos</h2>";

    echo "<p>
            Página de visualização de orientandos
          </p>";

    echo '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sendEmailModal">Adicionar Orientando</button>';
}

?>
<div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invite-intern">Adicionar Orientando</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../app/php/email/sendEmailAdvisor.php" method="POST"> 
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="company-name" class="col-form-label">Selecione o orientando:</label>
                        <?php                                                    
                            require_once('../../../app/db/connect.php');
                            require_once('../../../app/php/functions.php');

                            $query = "SELECT p.id_person, p.name_person FROM student s, person p WHERE s.fk_id = p.id_person ORDER BY p.name_person";

                            $stmt = $conn->prepare($query);
                        
                            $stmt->execute();
                        
                            $return = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                            $filterPerson = Array();
                        
                            foreach($return as $person) {
                                array_push($filterPerson, $person);
                            }
                            $count = count($filterPerson);
                        
                            if($count > 0) {
                                echo "<select class='form-control' id='message-subject' name='id-student'>";

                                foreach($filterPerson as $key => $person) {  
                                    $query = "SELECT * FROM internship_data WHERE fk_student = ".$person["id_person"]." AND finished = FALSE";

                                    $stmt = $conn->prepare($query);
                                
                                    $stmt->execute();
                                
                                    $return = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    if(!$return)
                                        echo "<option value='".$person["id_person"]."'>".$person["name_person"]."</option>";   
                                }
                                echo "</select>";   
                                
                            }
                        ?>
                    </div>
                    <div class="mb-3">
                        <label for="company-name" class="col-form-label">Selecione a empresa:</label>
                        <?php                                              
                            $query = "SELECT * FROM company";

                            $stmt = $conn->prepare($query);
                        
                            $stmt->execute();
                        
                            $return = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                            $filterCompany = Array();
                        
                            foreach($return as $company) {
                                array_push($filterCompany, $company);
                            }
                        
                            $count = count($filterCompany);
                        
                            if($count > 0) {
                                echo "<select class='form-control' id='message-subject' name='id-company'>";

                                foreach($filterCompany as $key => $company) {  
                                    echo "<option value='".$company["id_company"]."'>".$company["name_company"]."</option>";   
                                }
                                echo "</select>";   
                            }
                        ?>
                    </div>
                    <div class="mb-3">
                        <label for="course-student" class="col-form-label">Curso:</label>
                        <input type="text" class="form-control" id="course-student" name="course-student">
                    </div>
                    <div class="mb-3">
                        <label for="message-subject" class="col-form-label">Assunto:</label>
                        <input type="text" class="form-control" id="message-subject" name="message-subject" value="Estagio CTI">
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Conteúdo:</label>
                        <textarea class="form-control" id="message-text" name="message-text">Suas informações já foram adicionadas ao sistema de estágio e ao entrar no site, você já poderá ver as informações da empresa na qual trabalha e, após terminar de preencher os dados relacionados a seu estágio, poderá imprimir os documentos necessários para iniciar seu estágio.</textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <input type="submit" class="btn btn-primary" value="Enviar E-mail">
                </div>
            </form>
        </div>
    </div>
</div>



<nav class="navbar navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand">Pesquisa</a>
    <div class="d-flex">
        <a href="professorRecycle.php?type=student" class="btn btn-success mx-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-recycle" viewBox="0 0 16 16">
                <path d="M9.302 1.256a1.5 1.5 0 0 0-2.604 0l-1.704 2.98a.5.5 0 0 0 .869.497l1.703-2.981a.5.5 0 0 1 .868 0l2.54 4.444-1.256-.337a.5.5 0 1 0-.26.966l2.415.647a.5.5 0 0 0 .613-.353l.647-2.415a.5.5 0 1 0-.966-.259l-.333 1.242-2.532-4.431zM2.973 7.773l-1.255.337a.5.5 0 1 1-.26-.966l2.416-.647a.5.5 0 0 1 .612.353l.647 2.415a.5.5 0 0 1-.966.259l-.333-1.242-2.545 4.454a.5.5 0 0 0 .434.748H5a.5.5 0 0 1 0 1H1.723A1.5 1.5 0 0 1 .421 12.24l2.552-4.467zm10.89 1.463a.5.5 0 1 0-.868.496l1.716 3.004a.5.5 0 0 1-.434.748h-5.57l.647-.646a.5.5 0 1 0-.708-.707l-1.5 1.5a.498.498 0 0 0 0 .707l1.5 1.5a.5.5 0 1 0 .708-.707l-.647-.647h5.57a1.5 1.5 0 0 0 1.302-2.244l-1.716-3.004z"/>
            </svg>
        </a>
        <input class="form-control me-2" type="search" id="search-text-person" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" onclick="getInternContent()">Search</button>
    </div>
  </div>
</nav>

<div id="person-content">
    <?php
        require_once('../content/internContent.php');
    ?>
</div>