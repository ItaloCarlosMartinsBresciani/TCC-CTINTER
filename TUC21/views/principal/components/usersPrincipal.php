<?php 
    session_start();
    if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 10){
        echo "<script>alert('Usuário não reconhecido.');</script>";
        header("Location: ../../../index.php ");
        exit();
    }
    
    
?>

<h2>Pessoas</h2>

<p>
    Página de alteração e exclusão de pessoas
</p>

<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sendEmailCoordinatorModal">Convidar Coordenador</button>

<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sendEmailModal">Convidar Professores</button><br>

<div class="modal fade" id="sendEmailCoordinatorModal" tabindex="-1" aria-labelledby="sendEmailCoordinatorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendEmailCoordinatorModalLabel">Adicionar Coordenador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../app/php/email/sendEmailPrincipal.php?type=coordinatorInvitation" method="POST"> 
                <div class="modal-body">
                    <?php 
                    require_once('../../../app/db/connect.php');
                    require_once('../../../app/php/functions.php');

                    $query = "SELECT fk_university FROM university_employee WHERE fk_id = ".$_SESSION['idUser'];

                    $stmt = $conn->prepare($query);
                
                    $stmt->execute();
                
                    $return = $stmt->fetch(PDO::FETCH_ASSOC);

                    $id_university = $return["fk_university"];

                    $query = "SELECT p.email_person, p.name_person FROM university_employee u, person p WHERE u.fk_id = p.id_person AND u.role_university_employee = 'Coordenador' AND p.deleted = false AND u.fk_university = ".$id_university;
 
                    $stmt = $conn->prepare($query);
                
                    $stmt->execute();
                
                    $return_c = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $count2 = $stmt->rowCount();
                        

                    if ($count2 < 3 && $count2 >= 0)
                    {
                        echo '<div class="mb-3">
                        <label for="message-email" class="col-form-label">Selecionar professor:</label>';
    
                        $query = "SELECT p.email_person, p.name_person FROM university_employee u, person p WHERE u.fk_id = p.id_person AND u.role_university_employee = 'Professor' AND u.fk_university = ".$id_university;

                        $stmt = $conn->prepare($query);
                    
                        $stmt->execute();
                    
                        $return = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                        $filterPerson = Array();
                    
                        foreach($return as $person) {
                            array_push($filterPerson, $person);
                        }

                        $count = count($filterPerson);

                        if($count > 0) {
                            echo "<select class='form-control' id='message-email' name='message-email'>";

                            foreach($filterPerson as $key => $person) {  
                                echo "<option value='".$person["email_person"]."'>".$person["name_person"]."</option>";   
                            }
                            echo "</select>";   
                        } 
                        echo "</div>";
                        echo '<div class="mb-3">
                                <label for="message-subject" class="col-form-label">Assunto:</label>
                                <input type="text" class="form-control" id="message-subject" name="message-subject" value="Se tornar Coordenador">
                                </div>
                                <div class="mb-3">
                                    <label for="message-text" class="col-form-label">Conteúdo:</label>
                                    <textarea class="form-control" id="message-text" name="message-text" rows="5">A sua função no sistema de controle de estágio do CTI acabou de ser promovida para Coordenador de estágio. Para entrar nesta página, faça login em sua conta como professor, entre em seu perfil e clique no botão "Entrar como Coordenador".</textarea>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                    <input type="submit" class="btn btn-primary" value="Enviar E-mail">
                                </div>';
                        
                    }
                    else
                    {
                        echo "Já existem três coordenadores no sistema e não é possível inserir mais de três professores no papel de coordenador.";
                         
                    }
                    ?>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendEmailModalLabel">Convidar Professor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../app/php/email/sendEmailPrincipal.php?type=invitation" method="POST"> 
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="message-email" class="col-form-label">E-mail:</label>
                        <input type="email" class="form-control" id="message-email" name="message-email">
                    </div>
                    <div class="mb-3">
                        <label for="message-subject" class="col-form-label">Assunto:</label>
                        <input type="text" class="form-control" id="message-subject" name="message-subject" value="Estagio CTI">
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Conteúdo:</label>
                        <textarea class="form-control" id="message-text" name="message-text">Utilizando o link a seguir você poderá se cadastrar como professor(a) no controle de estágio da CTI.</textarea>
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
        <a href="principalRecycle.php?type=person" class="btn btn-success mx-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-recycle" viewBox="0 0 16 16">
                <path d="M9.302 1.256a1.5 1.5 0 0 0-2.604 0l-1.704 2.98a.5.5 0 0 0 .869.497l1.703-2.981a.5.5 0 0 1 .868 0l2.54 4.444-1.256-.337a.5.5 0 1 0-.26.966l2.415.647a.5.5 0 0 0 .613-.353l.647-2.415a.5.5 0 1 0-.966-.259l-.333 1.242-2.532-4.431zM2.973 7.773l-1.255.337a.5.5 0 1 1-.26-.966l2.416-.647a.5.5 0 0 1 .612.353l.647 2.415a.5.5 0 0 1-.966.259l-.333-1.242-2.545 4.454a.5.5 0 0 0 .434.748H5a.5.5 0 0 1 0 1H1.723A1.5 1.5 0 0 1 .421 12.24l2.552-4.467zm10.89 1.463a.5.5 0 1 0-.868.496l1.716 3.004a.5.5 0 0 1-.434.748h-5.57l.647-.646a.5.5 0 1 0-.708-.707l-1.5 1.5a.498.498 0 0 0 0 .707l1.5 1.5a.5.5 0 1 0 .708-.707l-.647-.647h5.57a1.5 1.5 0 0 0 1.302-2.244l-1.716-3.004z"/>
            </svg>
        </a>
        <input class="form-control me-2" type="search" id="search-text-person" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" onclick="getPersonContent()">Search</button>
    </div>
  </div>
</nav>

<div id="person-content">
    <?php
        require_once('../content/personContent.php');
    ?>
</div>