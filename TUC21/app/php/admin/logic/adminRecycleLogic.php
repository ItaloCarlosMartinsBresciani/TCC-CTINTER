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
      header('Location: ../../../../views/admin/adminRecycle.php');
    }   
} else {
    header('Location: ../../../../views/admin/adminRecycle.php');
}

if ($type === 'person') {
    try {
        $query = 'UPDATE person SET valid = TRUE WHERE id_person = :id';

        $stmt = $conn->prepare($query);
        
        $stmt->bindValue(':id', $idDec);
        
        $stmt->execute();
        $_SESSION['feedback'] = 'successRecycle';
        header('Location: ../../../../views/admin/adminRecycle.php?type=person');
    } catch (Exception $e) {
        $_SESSION['feedback'] = 'errorRecycle';
        header('Location: ../../../../views/admin/adminRecycle.php?type=person');
    }
} else if ($type == 'university') {
    try {
        $query = 'UPDATE university SET valid = TRUE WHERE id_university = :id';

        $stmt = $conn->prepare($query);
        
        $stmt->bindValue(':id', $idDec);
        
        $stmt->execute();

        $_SESSION['feedback'] = 'successRecycle';
        header('Location: ../../../../views/admin/adminRecycle.php?type=university');
    } catch (Exception $e) {
        $_SESSION['feedback'] = 'errorRecycle';
        header('Location: ../../../../views/admin/adminRecycle.php?type=university');
    }
}else if ($type == 'company') {
    try {
        $query = 'UPDATE company SET valid = TRUE WHERE id_company = :id';

        $stmt = $conn->prepare($query);
        
        $stmt->bindValue(':id', $idDec);
        
        $stmt->execute();

        $_SESSION['feedback'] = 'successRecycle';
        header('Location: ../../../../views/admin/adminRecycle.php?type=company');
    } catch (Exception $e) {
        $_SESSION['feedback'] = 'errorRecycle';
        header('Location: ../../../../views/admin/adminRecycle.php?type=companyy');
    }
}
