<?php
    session_start();
    require_once("php/loginValidate.php");
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="container">

    <a class="btn" href="php/logout.php">
        <i class="fas fa-sign-out-alt"></i> 
        <span>Logout</span>
    </a>

    </div>

    <script src="https://kit.fontawesome.com/a39639353a.js" crossorigin="anonymous"></script>
</body>
</html>
