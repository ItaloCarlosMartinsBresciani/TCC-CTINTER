<?php

session_start();

if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 7){
    echo "<script>alert('Usuário não reconhecido.');</script>";
    header("Location: ../../../index.php ");
    exit();
}

require_once('../../../app/db/connect.php');
require_once('../../../app/php/functions.php');

?>
<h2>Documentos</h2>

<p>
    Página de validação dos documentos realizados pelos estagiários
</p>

<!--<nav class="navbar navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand">Pesquisa</a>
    <div class="d-flex">
        <a href="supervisorRecycle.php?type=person" class="btn btn-success mx-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-recycle" viewBox="0 0 16 16">
                <path d="M9.302 1.256a1.5 1.5 0 0 0-2.604 0l-1.704 2.98a.5.5 0 0 0 .869.497l1.703-2.981a.5.5 0 0 1 .868 0l2.54 4.444-1.256-.337a.5.5 0 1 0-.26.966l2.415.647a.5.5 0 0 0 .613-.353l.647-2.415a.5.5 0 1 0-.966-.259l-.333 1.242-2.532-4.431zM2.973 7.773l-1.255.337a.5.5 0 1 1-.26-.966l2.416-.647a.5.5 0 0 1 .612.353l.647 2.415a.5.5 0 0 1-.966.259l-.333-1.242-2.545 4.454a.5.5 0 0 0 .434.748H5a.5.5 0 0 1 0 1H1.723A1.5 1.5 0 0 1 .421 12.24l2.552-4.467zm10.89 1.463a.5.5 0 1 0-.868.496l1.716 3.004a.5.5 0 0 1-.434.748h-5.57l.647-.646a.5.5 0 1 0-.708-.707l-1.5 1.5a.498.498 0 0 0 0 .707l1.5 1.5a.5.5 0 1 0 .708-.707l-.647-.647h5.57a1.5 1.5 0 0 0 1.302-2.244l-1.716-3.004z"/>
            </svg>
        </a>
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
    
        //Selecionando todos os estagiários desse supervisor
        $query = "SELECT p.*, r.supervisor_signature_internship_report, r.denied_internship_report FROM person p, internship_data i, internship_reports r WHERE p.id_person = i.fk_student AND i.fk_supervisor = ".$_SESSION["idUser"]." AND r.fk_internship_data = i.id_internship_data ORDER BY id_person";
        //$query = "SELECT fk_id FROM student WHERE fk_university = $id_university";
    
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
        
        $line = 1;
        $cont = 0;

        $id = -1;

        if($count > 0) 
        {
                $var = 0;
            
                foreach($filterPerson as $key => $person) 
                { 
                    if($person['supervisor_signature_internship_report'] == null && $person['denied_internship_report'] != TRUE)
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
                                <th scope="col">Relatórios</th>
                            </tr>
                        </thead>
                        <tbody id="person-content">';

                        $line = 1;
                        $cont = 0;
                        
                    foreach($filterPerson as $key => $person) 
                    { 
                        if($person['supervisor_signature_internship_report'] == null && $person['denied_internship_report'] != TRUE)
                        {   
                            if ($id != $person["id_person"])
                            {            
                                $id = $person["id_person"];
                                $idHex = codeId($person['id_person']);
                                echo '
                                <tr>
                                    <th scope="row">'.($line).'</th>
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
                                    <td>
                                        <a href="supervisorInfo.php?type=reports&id='.$idHex.'" class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-lg" viewBox="0 0 16 16">
                                                <path d="m10.277 5.433-4.031.505-.145.67.794.145c.516.123.619.309.505.824L6.101 13.68c-.34 1.578.186 2.32 1.423 2.32.959 0 2.072-.443 2.577-1.052l.155-.732c-.35.31-.866.434-1.206.434-.485 0-.66-.34-.536-.939l1.763-8.278zm.122-3.673a1.76 1.76 0 1 1-3.52 0 1.76 1.76 0 0 1 3.52 0z"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>';
                                $line++;
                                        
                                $id = $person["id_person"];
                            }
                        
                            else 
                            {
                                $cont++;
                            }
                        }   
                    }
                }
                else
                {
                    echo '<div class="col-12 h5 mt-5 text-secondary text-center">Nenhum documento encontrado</div>';
                }
            
        }
        else
        {
            echo '<div class="col-12 h5 mt-5 text-secondary text-center">Nenhum documento encontrado</div>';
        }
    ?>
</div>