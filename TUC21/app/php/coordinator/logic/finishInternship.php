<?php

    require_once('../../../db/connect.php');
    require_once('../../functions.php');
    session_start();

    if (isset($_GET['id'])) { 
        try {
          $idHex = cleanString($_GET['id']);
          $idDec = decodeId($idHex);
        }
        catch (TypeError) {
            $_SESSION['feedback'] = 'errorClosure';
            $_SESSION['btn'] = 1;
          header('Location: ../../../../views/coordinator/coordinatorPage.php');
        }   
    } else {
        $_SESSION['feedback'] = 'errorClosure';
        $_SESSION['btn'] = 1;
        header('Location: ../../../../views/coordinator/coordinatorPage.php');
    }

    try 
    {

        $query = "SELECT * FROM internship_reports WHERE fk_internship_data = :id AND (type_internship_report = 'Relatório Final (Obrigatório)' OR type_internship_report = 'Relatório Final (Não Obrigatório)') AND denied_internship_report = FALSE";
                                
        $stmt = $conn->prepare($query);

        // echo $idDec;
        $stmt->bindValue(':id', $idDec);

        $stmt->execute();

        $return = $stmt->fetch(PDO::FETCH_ASSOC);  

        if ($return)
        {
            if($return["supervisor_signature_internship_report"] != null && $return["advisor_signature_internship_report"]  != null && $return["coordinator_signature_internship_report"]  != null)
            {
                $query = "UPDATE internship_data SET finished = TRUE, finished_date = NOW() WHERE id_internship_data = :id";

                $stmt = $conn->prepare($query);
                
                $stmt->bindValue(':id', $idDec);

                $stmt->execute();
                
                $query = "SELECT ir.*, p.name_person, p.email_person FROM internship_data id, internship_reports ir, person p WHERE id.id_internship_data = $idDec AND p.id_person = id.fk_student AND ir.fk_internship_data = id.id_internship_data";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $return = $stmt->fetch(PDO::FETCH_ASSOC); 
                $type_report = $return['type_internship_report'];
                $messageEmail =  $return['email_person'];

                $query = "SELECT name_person, email_person FROM person WHERE id_person = ".$_SESSION["idUser"];
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $return = $stmt->fetch(PDO::FETCH_ASSOC); 
                $name_coordinator = $return['name_person'];
                $email_coordinator =  $return['email_person'];
                
                $messageSubject = "Finalização do estágio realizada com sucesso!";
                $mail_body = "O(A) coordenador(a) acabou de finalizar seu estágio! \n Ao entrar no Sistema de Controle de Estágio, você voltará a visualizar as empresas e os orientadores disponíveis e perderá o acesso à interface de estagiário.\n\nAtenciosamente, \n".$name_coordinator.".";
                $mail_body = urlencode($mail_body); //transforma os caracteres de string para serem aceitos no URL;
                
                $_SESSION['feedback'] = 'successClosure';
                $_SESSION['btn'] = 1;

                header("Location: https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=".$messageEmail."&su=".$messageSubject."&body=".$mail_body);        
            }
            else
            {
                echo "<script>alert('Alguma entidade ainda não assinou o relatório de desligamento do estágio');</script>";
                echo "<script>location= '../../../../views/coordinator/coordinatorPage.php';</script> ";
            }
        }
        else 
        {
            echo "<script>alert('O relatório de desligamento do estágio ainda não foi cadastrado');</script>";
            echo "<script>location= '../../../../views/coordinator/coordinatorPage.php';</script> ";
        }
    }
    catch(Exception $e)
    {        
        $_SESSION['feedback'] = 'errorClosure';
        $_SESSION['btn'] = 1;
        //echo $e->getMessage();
        header('Location: ../../../../views/coordinator/coordinatorPage.php');
    }
?>