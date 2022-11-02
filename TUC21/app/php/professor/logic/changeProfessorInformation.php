<?php
session_start();
if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 2){
    echo "<script>alert('Usuário não reconhecido.');</script>";
    header("Location: ../../../index.php ");
    exit();
}

require_once('../../functions.php');
require_once('../../../db/connect.php');

$id = $_SESSION['idUser'];

//conferindo se o professor é coordenador
$query = "SELECT * FROM university_employee WHERE role_university_employee = 'Coordenador' AND fk_id = $id";

$stmt = $conn->prepare($query);

$stmt->execute();

$return = $stmt->fetch(PDO::FETCH_ASSOC);

if ($return)
{
    $_SESSION['access_level'] = 9;
    header('Location: ../../../../views/coordinator/coordinatorPage.php');
}
else
{
    echo "<script>alert('Usuário não reconhecido.');</script>";
    header("Location: ../../../index.php ");
}
    