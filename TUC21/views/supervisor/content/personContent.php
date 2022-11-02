<?php 
    if(!isset($_SESSION)) {
        session_start();
    }

    

    require_once('../../../app/db/connect.php');
    require_once('../../../app/php/functions.php');

    $textSearch = '';

    if(isset($_POST['textSearch'])) {
        $textSearch = strtolower(cleanString($_POST['textSearch']));
    } 
    
    //Selecionar o id da universidade do professor
    $query = "SELECT fk_university FROM university_employee WHERE fk_id = ".$_SESSION["idUser"]."";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC); 

    $id_university = $return['fk_university'];

    /*$query = 'SELECT id_person, name_person, telephone_person, email_person, treatment_person, register_date, valid FROM person WHERE valid;';*/

    //Selecionar o funcionários da mesma universidade do professor
    $query = 'SELECT person.id_person, person.name_person, person.telephone_person, person.email_person, person.treatment_person, person.register_date, person.valid FROM person, university_employee WHERE person.valid AND person.id_person = university_employee.fk_id AND university_employee.fk_university = '.$id_university.';';

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetchAll(PDO::FETCH_ASSOC); 

    $filterPerson = Array();

    // echo '<pre>';
    // var_dump($return);
    // echo '</pre>';
    // exit;

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

    if($count > 0) {
        echo '
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Data</th>
                    <th scope="col">Contato</th>
                </tr>
            </thead>
            <tbody id="person-content">';

        foreach($filterPerson as $key => $person) {
            $date = date('d/m/Y', strtotime($person['register_date']));
            $idHex = codeId($person['id_person']);

            echo '
            <tr>
                <th scope="row">'.($key + 1).'</th>
                <td>'. $person['name_person'].'</td>
                <td>'.$date.'</td>
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
            </tr>
            ';
        }

        echo '
            </tbody>
        </table>
        ';
    } else {
        echo '<div class="col-12 h5 mt-5 text-secondary text-center">Nenhuma pessoa encontrada</div>';
    }
    
?>
