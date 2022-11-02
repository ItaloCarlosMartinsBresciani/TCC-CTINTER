<?php

session_start();

if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 9){
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
      header('Location: ../../../../views/coordinator/coordinatorPage.php');
    }   
} else {
    header('Location: ../../../../views/coordinator/coordinatorPage.php');
}

if ($type == 'person') {
    try {
        $query = 'UPDATE person SET valid = FALSE, deleted = TRUE, deleted_date = NOW() WHERE id_person = :id';

        $stmt = $conn->prepare($query);
        
        $stmt->bindValue(':id', $idDec);
        
        $stmt->execute();

        $_SESSION['feedback'] = 'successExclude';
        $_SESSION['btn'] = 1;
        header('Location: ../../../../views/coordinator/coordinatorPage.php');
    } catch (Exception $e) {
        $_SESSION['feedback'] = 'errorExclude';
        $_SESSION['btn'] = 1;
        header('Location: ../../../../views/coordinator/coordinatorPage.php');
        //echo $e->getMessage();
    }
 } 
 else if ($type == 'intern') {
    try {
        //$today= date('d-m-Y');
        $query = 'UPDATE person p, internship_data i SET p.valid = FALSE, p.deleted = TRUE, p.deleted_date = NOW() WHERE p.id_person = :id AND i.fk_student = p.id_person';

        $stmt = $conn->prepare($query);
        
        $stmt->bindValue(':id', $idDec);
        
        $stmt->execute();

        $_SESSION['feedback'] = 'successExclude';
        $_SESSION['btn'] = 1;
        header('Location: ../../../../views/coordinator/coordinatorPage.php');

    } catch (Exception $e) {
        $_SESSION['feedback'] = 'errorExclude';
        $_SESSION['btn'] = 1;
        header('Location: ../../../../views/coordinator/coordinatorPage.php');
        //echo $e->getMessage();
    }
 } 
 else if ($type == 'student') {
    try {
        $query = 'UPDATE person SET valid = FALSE, deleted = TRUE, deleted_date = NOW() WHERE id_person = :id';

        $stmt = $conn->prepare($query);
        
        $stmt->bindValue(':id', $idDec);
        
        $stmt->execute();

        $_SESSION['feedback'] = 'successExclude';
        $_SESSION['btn'] = 1;
        header('Location: ../../../../views/coordinator/coordinatorPage.php');

    } catch (Exception $e) {
        $_SESSION['feedback'] = 'errorExclude';
        $_SESSION['btn'] = 1;
        header('Location: ../../../../views/coordinator/coordinatorPage.php');
        //echo $e->getMessage();
    }
 } 