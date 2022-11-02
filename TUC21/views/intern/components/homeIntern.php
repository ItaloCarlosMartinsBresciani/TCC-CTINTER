<?php 
    session_start();
    if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 1){
        echo "<script>alert('Usuário não reconhecido.');</script>";
        header("Location: ../../../index.php ");
        exit();
    }
    
    
?>

<h2>Home</h2>

<?php
    require_once('../../../app/db/connect.php');
    require_once('../../../app/php/functions.php');

    $query = "SELECT name_person FROM person WHERE id_person = ".$_SESSION['idUser'];
   
    $stmt = $conn->prepare($query);

    $return = $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $name_student = $return['name_person'];

?>
<p>Bem-vindo(a), <?php echo $name_student?>!</p>

<?php 

$query = "SELECT id_internship_data, description_internship_data FROM internship_data WHERE fk_student = ".$_SESSION["idUser"]."";

$stmt = $conn->prepare($query);

$return = $stmt->execute();

$return = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<br>";

$filterPerson = Array();


foreach($return as $person) {
    array_push($filterPerson, $person);
}

$count = count($filterPerson);


if($count > 0)
{
    foreach($filterPerson as $key => $person) {  
        if($person["description_internship_data"] == null){
            $id_intern = codeId($_SESSION["idUser"]);
            $id_internship = codeId($person["id_internship_data"]);
            echo "
            <h4>Informações do Estágio</h4>
        
            <p>
                Ainda não foram preenchidas todas as informações do seu estágio. Deseja preenchê-las agora?
            </p>
            
            <a href='../putInternshipDataInformation.php?id_intern=".$id_intern."&id_internship_data=".$id_internship."'> <button type='button' class='btn btn-primary'>Preencher Informações restantes</button></a>";
        }
    }
}

