<?php
    if (!isset($_SESSION['isAuth']) || !isset($_SESSION['idUser'])) {
        header("Location: index.php ");
        exit();
    }
    