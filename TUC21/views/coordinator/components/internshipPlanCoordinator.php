<?php

session_start();

if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 9){
    echo "<script>alert('Usuário não reconhecido.');</script>";
    header("Location: ../../../index.php ");
    exit();
}

require_once('../../../app/db/connect.php');
require_once('../../../app/php/functions.php');

?>
<h2>Planos de Estágio</h2>

<p>
    Página de validação dos planos de estágio
</p>

<!--<nav class="navbar navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand">Pesquisa</a>
    <div class="d-flex">
        <input class="form-control me-2" type="search" id="search-text-person" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" onclick="getInternContent()">Search</button>
    </div>
  </div>
</nav>-->

<div id="person-content">
    <?php
        if(isset($_POST['textSearch'])) {
            $textSearch = strtolower(cleanString($_POST['textSearch']));
        } 
    
        //Selecionando todos os estagiários da universidade do coordenador

        //selecionando id da universidade
        $query = "SELECT fk_university FROM person p, university_employee u WHERE p.id_person = u.fk_id AND p.id_person = ".$_SESSION["idUser"]." AND p.valid = TRUE AND u.active = TRUE";
    
        $stmt = $conn->prepare($query);
    
        $stmt->execute();
    
        $return = $stmt->fetch(PDO::FETCH_ASSOC);

        $id_university = $return['fk_university'];
        
        //selecionando estagiários matriculados nessa universidade
        $query = "SELECT p.*, ip.id_internship_plan, ip.coordinator_approval_internship_plan, ip.advisor_approval_internship_plan, ip.supervisor_approval_internship_plan, ip.date_internship_plan, s.total_hours_student FROM person p, internship_data i, internship_plan ip, student s 
        WHERE p.id_person = i.fk_student AND p.id_person = s.fk_id AND s.fk_university = ".$id_university." AND ip.fk_internship_data = i.id_internship_data ORDER BY id_person";
    
        $stmt = $conn->prepare($query);
    
        $stmt->execute();
    
        $return = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $filterPerson = Array();
    
        if(!empty($textSearch)) {
            foreach($return as $person) {
                if (strpos(strtolower($person['name_person']), $textSearch) !== false) {
                        array_push($filterPerson, $person);
                } 
            }
        }
        else {
            foreach($return as $person) {
                array_push($filterPerson, $person);
            }
        }
    
        $count = count($filterPerson);

        if($count > 0) 
        { //se existem estagiários dessa universidade
            $var = 0;

            foreach($filterPerson as $key => $person) 
            { 
                if($person['coordinator_approval_internship_plan'] == null && $person['advisor_approval_internship_plan'] != null && $person['supervisor_approval_internship_plan'] != null)
                {
                    $var = $var + 1;
                }
            }
            
            if($var > 0)
            {
                echo '
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Contato</th>
                            <th scope="col">Data</th>
                            <th scope="col">Download</th>
                            <th scope="col">Aprovação</th>
                        </tr>
                    </thead>
                    <tbody id="person-content">';
                    
                foreach($filterPerson as $key => $person) { 
                    if($person['coordinator_approval_internship_plan'] == null && $person['advisor_approval_internship_plan'] != null && $person['supervisor_approval_internship_plan'] != null)
                    {    
                        if (isset($person['total_hours_student']))
                        {
                            $date = date_create($person['date_internship_plan']);
                            $idHex = codeId($person['id_internship_plan']);
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
                                    <a href="tel:'.$person['telephone_person'].'" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                                        </svg>
                                    </a>
                                </td>
                                <td>'. date_format($date, 'd/m/Y').'</td>
                                <td>
                                    <a href="documents/proposta_de_plano_de_estagio.php?id='.$idHex.'" class="btn btn-primary text-white"><i class="fa fa-arrow-down"></i></a>
                                </td>
                                <td>
                                    <a href="../putInternshipPlanInformation_c.php?id='.$idHex.'" class="btn btn-primary text-white"><i class="fa fa-gavel"></i></a>
                                </td>
                            </tr>';           
                        }                 
                    }
                }
                
            
                echo '
                </tbody>
        </table>'; 
            }
            else
            {
                echo '<div class="col-12 h5 mt-5 text-secondary text-center">Nenhum plano de estágio encontrado</div>';
            }
        }
        else
        {
            echo '<div class="col-12 h5 mt-5 text-secondary text-center">Nenhum plano de estágio encontrado</div>';
        }
    ?>
    </div>