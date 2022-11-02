<?php
    session_start();
    require_once("connect.php");

    unset($_SESSION['isAuth']);
    session_destroy();

    $conn = null;
    
    header('Location: ../index.php');
    exit();
