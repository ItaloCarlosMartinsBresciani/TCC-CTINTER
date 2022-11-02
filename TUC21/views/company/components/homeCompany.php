<?php

session_start();
if(!isset($_SESSION['isAuth'])){
    echo "<script>alert('Usuário não reconhecido.');</script>";
    header("Location: ../../../index.php ");
    exit();
}
?>

<h2>Home</h2>
<?php
    require_once('../../../app/db/connect.php');
    require_once('../../../app/php/functions.php');

    $query = "SELECT name_company FROM company WHERE id_company = ".$_SESSION['idUser'];
   
    $stmt = $conn->prepare($query);

    $return = $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<p>Bem-vindo(a), <?php echo $return["name_company"]?>!</p>

<?php
// Validação de Edição ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$id_company = $_SESSION['idUser'];

echo '<br><h4>Solicitações de Edição</h4>';

$query = 'SELECT p.id_person, p.name_person, p.email_person  FROM person p, change_data c, company_employee ce WHERE p.id_person = c.fk_id AND p.id_person = ce.fk_id AND c.pending_allowance = TRUE AND p.access_level = 7 AND ce.fk_company = '.$id_company.';';

$stmt = $conn->prepare($query);

$stmt->execute();

$return = $stmt->fetchAll(PDO::FETCH_ASSOC); 

$filterPerson = Array();

foreach($return as $person) {
    array_push($filterPerson, $person);
}

$count = count($filterPerson);


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
                <a href="../../app/php/company/logic/allowEditionLogic.php?type=allow&id='.$idHex.'" class="btn btn-primary">
                    Permitir
                </a>
                <a href="../../app/php/company/logic/allowEditionLogic.php?type=deny&id='.$idHex.'" class="btn btn-primary">
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
} else {
    echo '<div class="col-12 h5 mt-5 text-secondary text-center">Nenhuma solicitação encontrada</div>';
}
?>
