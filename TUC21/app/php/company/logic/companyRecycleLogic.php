<?php

session_start();

if(!isset($_SESSION['isAuth'])){
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
      header('Location: ../../../../views/company/companyRecycle.php');
    }   
} else {
    header('Location: ../../../../views/company/companyRecycle.php');
}

if ($type == 'person') {
    try {
        $query = 'UPDATE person SET valid = TRUE, deleted = FALSE WHERE id_person = :id';

        $stmt = $conn->prepare($query);
        
        $stmt->bindValue(':id', $idDec);
        
        $stmt->execute();
        $_SESSION['feedback'] = 'successRecycle';
        header('Location: ../../../../views/company/companyRecycle.php?type=person');
    } catch (Exception $e) {
        $_SESSION['feedback'] = 'errorRecycle';
        header('Location: ../../../../views/company/companyRecycle.php?type=person');
    }
} 
if ($type == 'intern') {
    try {
        $query = 'UPDATE person p, internship_data i SET p.valid = TRUE, p.deleted = FALSE WHERE p.id_person = :id AND p.id_person = i.fk_student';

        $stmt = $conn->prepare($query);
        
        $stmt->bindValue(':id', $idDec);
        
        $stmt->execute();
        $_SESSION['feedback'] = 'successRecycle';
        header('Location: ../../../../views/company/companyRecycle.php?type=intern');
    } catch (Exception $e) {
        $_SESSION['feedback'] = 'errorRecycle';
        header('Location: ../../../../views/company/companyRecycle.php?type=intern');
    }
} 
if ($type == 'employee') {
    try {
        $query = 'UPDATE person SET valid = TRUE, deleted = FALSE WHERE id_person = :id';

        $stmt = $conn->prepare($query);
        
        $stmt->bindValue(':id', $idDec);
    
        $stmt->execute();
        $_SESSION['feedback'] = 'successRecycle';
        header('Location: ../../../../views/company/companyRecycle.php?type=employee');
    } catch (Exception $e) {
        $_SESSION['feedback'] = 'errorRecycle';
        header('Location: ../../../../views/company/companyRecycle.php?type=employee');
    }
} 
