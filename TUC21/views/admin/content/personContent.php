<?php 
    if(!isset($_SESSION)) {
        session_start();
    }

    if(!isset($_SESSION['isAuth']) || $_SESSION['idUser'] != -1){
        header("Location: ../../../index.php ");
        exit();
    }

    require_once('../../../app/db/connect.php');
    require_once('../../../app/php/functions.php');

    $textSearch = '';

    if(isset($_POST['textSearch'])) {
        $textSearch = strtolower(cleanString($_POST['textSearch']));
    } 
    
    $query = 'SELECT p.id_person, p.name_person, p.telephone_person, p.email_person, p.access_level, p.register_date, p.valid FROM person p WHERE valid ORDER BY access_level';

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
                    <th scope="col">Função</th>
                    <th scope="col">Data</th>
                    <th scope="col">Contato</th>
                    <th scope="col">Configurações</th>
                    <th scope="col">Edição</th>
                </tr>
            </thead>
            <tbody id="person-content">';

        foreach($filterPerson as $key => $person) {
            $date = date('d/m/Y', strtotime($person['register_date']));
            $idHex = codeId($person['id_person']);
            if($person["valid"])
            {

                if($person["access_level"] == 1){
                    $function = 'Estudante';
                }
                else if($person["access_level"] == 2){
                    
                    $query = 'SELECT a.*, u.* FROM advisor a, university_employee u WHERE a.fk_id = u.fk_id AND a.fk_id ='.$person["id_person"];

                    $stmt = $conn->prepare($query);

                    $stmt->execute();

                    $return_advisor = $stmt->fetch(PDO::FETCH_ASSOC); 
                    
                    $query = "SELECT u.* FROM university_employee u WHERE u.role_university_employee = 'Coordenador' AND u.fk_id =".$person["id_person"];

                    $stmt = $conn->prepare($query);

                    $stmt->execute();

                    $return = $stmt->fetch(PDO::FETCH_ASSOC); 

                    if (isset($return_advisor["cic_advisor"]) && !isset($return))
                    {
                        $function = 'Orientador';   
                    }
                    else
                    {
                        if ($return)
                            $function = 'Coordenador';
                        else 
                            $function = 'Professor';
                    }
                        // $function = $return["role_university_employee"];
                        // echo $return["role_university_employee"];
                        // echo var_dump($return);
                }
                else if($person["access_level"] == 7){
                    $function = 'Supervisor';
                }
                // else if($person["access_level"] == 9){
                //     $function = 'Coordenador';
                // }
                else if($person["access_level"] == 10){
                    $function = 'Diretor';
                }
                



                //Verificando se a solicitação de edição está bloqueada
                $query = "SELECT blocked_edition FROM change_data WHERE fk_id = ".$person['id_person']." AND blocked_edition = FALSE";

                $stmt = $conn->prepare($query);

                $stmt->execute();

                $return = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($return) //se ele não está bloqueado
                {
                    $edition = '<a href="../../app/php/admin/logic/allowRequestLogic.php?category=person&type=block&id='.$idHex.'" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Bloquear edição.">
                                    <i class="fas fa-times-circle"></i>
                                </a>';        
                }
                else //se ele está bloqueado
                {
                    $edition = '<a href="../../app/php/admin/logic/allowRequestLogic.php?category=person&type=allow&id='.$idHex.'" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Permitir edição.">
                                    <i class="fas fa-check-circle"></i>
                                </a>'; 
                }

                echo '
                <tr>
                    <th scope="row">'.($key + 1).'</th>
                    <td>'. $person['name_person'].'</td>
                    <td>'.$function.'</td>
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
                    <td>
                        <a href="adminInfo.php?type=person&id='.$idHex.'" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-lg" viewBox="0 0 16 16">
                                <path d="m10.277 5.433-4.031.505-.145.67.794.145c.516.123.619.309.505.824L6.101 13.68c-.34 1.578.186 2.32 1.423 2.32.959 0 2.072-.443 2.577-1.052l.155-.732c-.35.31-.866.434-1.206.434-.485 0-.66-.34-.536-.939l1.763-8.278zm.122-3.673a1.76 1.76 0 1 1-3.52 0 1.76 1.76 0 0 1 3.52 0z"/>
                            </svg>
                        </a>
                        <a href="adminEdit.php?type=person&id='.$idHex.'" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                            </svg>
                        </a>
                        <button class="btn btn-danger" onclick="exclude(10,'.$idHex.')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                            </svg>
                        </button>
                    </td>
                    <td>
                        '.$edition.'
                    </td>
                </tr>
                ';
            }
        }

        echo '
            </tbody>
        </table>
        ';
    } else {
        echo '<div class="col-12 h5 mt-5 text-secondary text-center">Nenhuma pessoa encontrada</div>';
    }
    
?>