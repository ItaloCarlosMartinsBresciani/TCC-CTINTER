<?php

session_start();

if(!isset($_SESSION['isAuth']) || $_SESSION['idUser'] != -1){
    header("Location: ../../../../index.php ");
    exit();
}

require_once('../../functions.php');
require_once('../../../db/connect.php');

if (isset($_GET['type']) && isset($_GET['id'])) {
    $type = cleanString($_GET['type']);

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

if ($type == 'person') {
    try {
        $query = 'UPDATE person SET valid = FALSE, deleted_date = CURRENT_DATE WHERE id_person = :id';

        $stmt = $conn->prepare($query);
        
        $stmt->bindValue(':id', $idDec);
        
        $stmt->execute();

        $_SESSION['feedback'] = 'successExclude';
        $_SESSION['btn'] = 1;
        header('Location: ../../../../views/admin/adminPage.php');
    } catch (Exception $e) {
        $_SESSION['feedback'] = 'errorExclude';
        $_SESSION['btn'] = 1;
        header('Location: ../../../../views/admin/adminPage.php');
    }
} else if ($type == 'university') {
    try {
        $query = 'UPDATE university SET valid = FALSE, deleted_date = CURRENT_DATE WHERE id_university = :id';

        $stmt = $conn->prepare($query);
        
        $stmt->bindValue(':id', $idDec);
        
        $stmt->execute();

        $_SESSION['feedback'] = 'successExclude';
        $_SESSION['btn'] = 2;
        header('Location: ../../../../views/admin/adminPage.php');
    } catch (Exception $e) {
        $_SESSION['feedback'] = 'errorExclude';
        $_SESSION['btn'] = 2;
        header('Location: ../../../../views/admin/adminPage.php');
    }
}
else if ($type == 'company') {
    try {
        $query = 'UPDATE company SET valid = FALSE, deleted_date = CURRENT_DATE WHERE id_company = :id';

        $stmt = $conn->prepare($query);
        
        $stmt->bindValue(':id', $idDec);
        
        $stmt->execute();

        $_SESSION['feedback'] = 'successExclude';
        $_SESSION['btn'] = 2;
        header('Location: ../../../../views/admin/adminPage.php');
    } catch (Exception $e) {
        $_SESSION['feedback'] = 'errorExclude';
        $_SESSION['btn'] = 2;
        header('Location: ../../../../views/admin/adminPage.php');
    }
}
