<?php

session_start();

if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 2){
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
      header('Location: ../../../../views/professor/professorPage.php');
    }   
} else {
    header('Location: ../../../../views/professor/professorPage.php');
}

if ($type == 'person') {
    try {
        $query = 'UPDATE person SET valid = FALSE, deleted = TRUE, deleted_date = NOW() WHERE id_person = :id';

        $stmt = $conn->prepare($query);
        
        $stmt->bindValue(':id', $idDec);
        
        $stmt->execute();

        $_SESSION['feedback'] = 'successExclude';
        $_SESSION['btn'] = 1;
        header('Location: ../../../../views/professor/professorPage.php');
    } catch (Exception $e) {
        $_SESSION['feedback'] = 'errorExclude';
        $_SESSION['btn'] = 1;
        header('Location: ../../../../views/professor/professorPage.php');
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
        header('Location: ../../../../views/professor/professorPage.php');

    } catch (Exception $e) {
        $_SESSION['feedback'] = 'errorExclude';
        $_SESSION['btn'] = 1;
        header('Location: ../../../../views/professor/professorPage.php');
        //echo $e->getMessage();
    }
 } 