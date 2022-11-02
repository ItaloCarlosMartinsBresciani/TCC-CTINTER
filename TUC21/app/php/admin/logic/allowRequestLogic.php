<?php

session_start();

if(!isset($_SESSION['isAuth']) || $_SESSION['idUser'] != -1){
    header("Location: ../../../index.php ");
    exit();
}

require_once('../../functions.php');
require_once('../../../db/connect.php');

if (isset($_GET['type']) && isset($_GET['id']) && isset($_GET['category'])) {
    $type = cleanString($_GET['type']);
    $category = cleanString($_GET['category']);

    try {
        $id = cleanString($_GET['id']);

        $idDec = decodeId($id);
    }
    catch (Exception $e) {
        header('Location: ../../../../views/admin/adminPage.php');
    }   
} else {
    header('Location: ../../../../views/admin/adminPage.php');
}

try
{
    if ($category == 'person')
    {
        if ($type == 'allow') 
        {
            $query = "UPDATE change_data SET blocked_edition = FALSE WHERE fk_id = $idDec";
            $stmt = $conn->prepare($query);
            $stmt->execute();

            $_SESSION['feedback'] = 'successAllowEdition';
            $_SESSION['btn'] = 1;
        }
        else if ($type == 'block') 
        {
            $query = "UPDATE change_data SET blocked_edition = TRUE WHERE fk_id = $idDec";
            $stmt = $conn->prepare($query);
            $stmt->execute();

            $_SESSION['feedback'] = 'successBlockEdition';
            $_SESSION['btn'] = 1;
        }
        header('Location: ../../../../views/admin/adminPage.php'); 
    }
    else if ($category == 'university')
    {
        if ($type == 'allow') 
        {
            $query = "UPDATE change_data_universities SET blocked_edition = FALSE WHERE fk_id = $idDec";
            $stmt = $conn->prepare($query);
            $stmt->execute();

            $_SESSION['feedback'] = 'successAllowEdition';
            $_SESSION['btn'] = 1;
        }
        else if ($type == 'block') 
        {
            $query = "UPDATE change_data_universities SET blocked_edition = TRUE WHERE fk_id = $idDec";
            $stmt = $conn->prepare($query);
            $stmt->execute();

            $_SESSION['feedback'] = 'successBlockEdition';
            $_SESSION['btn'] = 1;
        }
        header('Location: ../../../../views/admin/adminPage.php'); 
    }
}
catch (Exception $ex)
{
    if ($type == 'allow') 
    {
        $_SESSION['feedback'] = 'errorAllowEdition';
        $_SESSION['btn'] = 1;
    }
    else if ($type == 'deny') 
    {
        $_SESSION['feedback'] = 'errorBlockEdition';
        $_SESSION['btn'] = 1;
    }
   header('Location: ../../../../views/admin/adminPage.php');
}