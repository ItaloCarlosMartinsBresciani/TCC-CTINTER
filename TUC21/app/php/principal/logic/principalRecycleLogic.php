<?php

session_start();

if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 10){
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
      header('Location: ../../../../views/principal/principalRecycle.php');
    }   
} else {
    header('Location: ../../../../views/principal/principalRecycle.php');
}

if ($type == 'person') {
    try {
        $query = 'UPDATE person SET valid = TRUE, deleted = FALSE WHERE id_person = :id';

        $stmt = $conn->prepare($query);
        
        $stmt->bindValue(':id', $idDec);
        
        $stmt->execute();
        $_SESSION['feedback'] = 'successRecycle';
        header('Location: ../../../../views/principal/principalRecycle.php?type=person');
    } catch (Exception $e) {
        $_SESSION['feedback'] = 'errorRecycle';
        header('Location: ../../../../views/principal/principalRecycle.php?type=person');
    }
} 
if ($type == 'student') {
    try {
        $query = 'UPDATE person  SET valid = TRUE, deleted = FALSE WHERE id_person = :id';

        $stmt = $conn->prepare($query);
        
        $stmt->bindValue(':id', $idDec);
    
        $stmt->execute();
        $_SESSION['feedback'] = 'successRecycle';
        header('Location: ../../../../views/principal/principalRecycle.php?type=intern');
    } catch (Exception $e) {
        $_SESSION['feedback'] = 'errorRecycle';
        header('Location: ../../../../views/principal/principalRecycle.php?type=intern');
    }
} 
