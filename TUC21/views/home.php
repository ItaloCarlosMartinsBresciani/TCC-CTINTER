home<?php
    session_start();
    if(!isset($_SESSION['isAuth'])){
        header("Location: ../index.php ");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    HOME
    <h1>HOOOOOOMEEEE siiiiiiiiiiiiiiiuuuuuuu</h1>

    <a href="../app/php/logout.php">Logout</a>
</body>
</html>