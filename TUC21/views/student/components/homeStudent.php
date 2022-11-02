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

