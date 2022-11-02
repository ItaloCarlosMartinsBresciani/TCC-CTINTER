 <?php 
    session_start();
    if(!isset($_SESSION['isAuth']) || $_SESSION['idUser'] != -1){
        echo "<script>alert('Usuário não reconhecido.');</script>";
        header("Location: ../../../index.php ");
        exit();
    }
    
    
?>

<h2>Home</h2>

<p>Bem-vindo(a), Admin!</p>


<br>
<h3>Solicitações de Edição</h3>

<?php

    
    require_once('../../../app/db/connect.php');
    require_once('../../../app/php/functions.php');

    //selecionar as informações dos professores que solicitaram edição
    $query = 'SELECT p.id_person, p.name_person, p.email_person  FROM person p, change_data c WHERE p.id_person = c.fk_id AND c.pending_allowance = TRUE AND p.access_level = 10 AND p.valid = TRUE';

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetchAll(PDO::FETCH_ASSOC); 

    $filterPerson = Array();

    foreach($return as $person) {
        array_push($filterPerson, $person);
    }

    $count = count($filterPerson);

    //selecionar as informações das universidades que solicitaram edição
    $query = 'SELECT u.id_university, u.name_university, p.email_person  FROM university u, change_data_universities c, person p WHERE p.id_person = u.fk_principal AND u.id_university = c.fk_id AND c.pending_allowance = TRUE AND u.valid = TRUE';

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetchAll(PDO::FETCH_ASSOC); 

    $filterUniversity = Array();

    foreach($return as $university) {
        array_push($filterUniversity, $university);
    }

    $count_university = count($filterUniversity);

    //selecionar as informações das empresas que solicitaram edição
    $query = 'SELECT e.id_company, e.name_company, e.email_company FROM company e, change_data_companies c WHERE e.id_company = c.fk_id AND c.pending_allowance = TRUE AND e.valid = TRUE ';

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetchAll(PDO::FETCH_ASSOC); 

    $filterCompany = Array();

    foreach($return as $company){
        array_push($filterCompany, $company);
    }

    $count_company = count($filterCompany);

    $cont = 0;

    

    if($count > 0) {

        foreach($filterPerson as $key => $person) {
            //$date = date('d/m/Y', strtotime($person['register_date']));
            $idHex = codeId($person['id_person']);

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

            $cont = $key + 1;
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
                    <a href="../../app/php/admin/logic/allowEditionLogic.php?category=person&type=allow&id='.$idHex.'" class="btn btn-primary">
                        Permitir
                    </a>
                    <a href="../../app/php/admin/logic/allowEditionLogic.php?category=person&type=deny&id='.$idHex.'" class="btn btn-primary">
                        Negar
                    </a>
                </td>
            </tr>
            ';
        }
    }
    if ($count_university > 0) 
    {

        if ($count <= 0 )
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
        foreach($filterUniversity as $key => $university) { 
            //$date = date('d/m/Y', strtotime($person['register_date']));
            $idHex = codeId($university['id_university']);

            echo '
            <tr>
                <th scope="row">'.($key + 1 + $cont).'</th>
                <td>'. $university['name_university'].'</td>
                <td>
                    <a href="mailto:'.$university['email_person'].'?subject=Hello%20again" class="btn btn-primary text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                            <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>
                        </svg>
                    </a>
                </td>
                <td>
                    <a href="../../app/php/admin/logic/allowEditionLogic.php?category=university&type=allow&id='.$idHex.'" class="btn btn-primary">
                        Permitir
                    </a>
                    <a href="../../app/php/admin/logic/allowEditionLogic.php?category=university&type=deny&id='.$idHex.'" class="btn btn-primary">
                        Negar
                    </a>
                </td>
            </tr>
            ';
        }
    }
    if ($count_company > 0) 
    {

        if ($count <= 0 && $count_university <= 0)
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
        foreach($filterCompany as $key => $company) { 
            //$date = date('d/m/Y', strtotime($person['register_date']));
            $idHex = codeId($company['id_company']);

            echo '
            <tr>
                <th scope="row">'.($key + 1 + $cont + $count_university).'</th>
                <td>'. $company['name_company'].'</td>
                <td>
                    <a href="mailto:'.$company['email_company'].'?subject=Hello%20again" class="btn btn-primary text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                            <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>
                        </svg>
                    </a>
                </td>
                <td>
                    <a href="../../app/php/admin/logic/allowEditionLogic.php?category=company&type=allow&id='.$idHex.'" class="btn btn-primary">
                        Permitir
                    </a>
                    <a href="../../app/php/admin/logic/allowEditionLogic.php?category=company&type=deny&id='.$idHex.'" class="btn btn-primary">
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
    if ($count <= 0 && $count_university <= 0 && $count_company <=0) {
        echo '<div class="col-12 h5 mt-5 text-secondary text-center">Nenhuma solicitação de edição encontrada</div>';
    }
?>



