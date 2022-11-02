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

<h2>Documentos</h2>

<p>
    Página de validação dos documentos elaborados pelos estagiários
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

<?php
$query = "SELECT fk_university FROM university_employee WHERE fk_id = ".$_SESSION['idUser'];

$stmt = $conn->prepare($query);

$stmt->execute();

$return = $stmt->fetch(PDO::FETCH_ASSOC);

$id_university = $return["fk_university"];

//selecionando os estagiários do Orientador que possuem relatórios
$query = "SELECT p.*, r.date_internship_report, r.advisor_signature_internship_report, r.supervisor_signature_internship_report, r.coordinator_signature_internship_report, r.denied_internship_report FROM person p, internship_data i, internship_reports r, student s WHERE p.id_person = i.fk_student AND r.fk_internship_data = i.id_internship_data AND s.fk_id = i.fk_student AND s.fk_university = ".$id_university." order by p.id_person";

$stmt = $conn->prepare($query);

$stmt->execute();

$return = $stmt->fetchAll(PDO::FETCH_ASSOC);

$filterPerson = Array();

foreach($return as $person) {
    array_push($filterPerson, $person);
}

$count = count($filterPerson);

$line = 1;
$cont = 0;
$id = -1;

if($count > 0 )
{
    $var = 0;
    foreach($filterPerson as $key => $person) { 
        if($person['supervisor_signature_internship_report'] != null && $person['advisor_signature_internship_report'] != null && $person['coordinator_signature_internship_report'] == null && $person['denied_internship_report'] != TRUE)
        {
            $var = $var + 1;
        }
    }
    
   if($var > 0)
   {
        echo "<table class='table table-striped'>
        <thead class='table-dark'>
            <tr>
                <th scope='col'>#</th>
                <th scope='col'>Nome do Estagiário</th>
                <th scope='col'>Contato</th>
                <th scope='col'>Relatórios</th> 
                <th scope='col'>Data do Upload</th>          
            </tr>
        </thead>
        <tbody>";

        foreach($filterPerson as $key => $person) {  
            if ($person['coordinator_signature_internship_report'] == null && $person['supervisor_signature_internship_report'] != null && $person['advisor_signature_internship_report'] != null && $person['denied_internship_report'] != TRUE)
            {
                if ($id != $person["id_person"])
                {  
                    $date = date('d/m/Y', strtotime($person['date_internship_report']));
                    $idHex = codeId($person['id_person']);
                    echo "
                            <tr>
                            <th scope='row'>".($line)."</th>
                            <td>". $person['name_person']."</td>
                            <td>
                                <a href='mailto:".$person['email_person']."?subject=Hello%20again' class='btn btn-primary text-white'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-envelope-fill' viewBox='0 0 16 16'>
                                        <path d='M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z'/>
                                    </svg>
                                </a>
                                <a href='tel:".$person['telephone_person']."' class='btn btn-primary'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-telephone-fill' viewBox='0 0 16 16'>
                                        <path fill-rule='evenodd' d='M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z'/>
                                    </svg>
                                </a>
                            </td>
                            <td>
                                <a href='coordinatorInfo.php?type=reports&id=".$idHex."' class='btn btn-primary'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-info-lg' viewBox='0 0 16 16'>
                                        <path d='m10.277 5.433-4.031.505-.145.67.794.145c.516.123.619.309.505.824L6.101 13.68c-.34 1.578.186 2.32 1.423 2.32.959 0 2.072-.443 2.577-1.052l.155-.732c-.35.31-.866.434-1.206.434-.485 0-.66-.34-.536-.939l1.763-8.278zm.122-3.673a1.76 1.76 0 1 1-3.52 0 1.76 1.76 0 0 1 3.52 0z'/>
                                    </svg>
                                </a>
                                
                            </td>
                            <td>".$date."</td>
                        </tr>
                    </tbody>
                    </table>
                    "; 
                    $line++;     
                    $id = $person["id_person"];
                }      
            }
            else 
            {
               $cont++;
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