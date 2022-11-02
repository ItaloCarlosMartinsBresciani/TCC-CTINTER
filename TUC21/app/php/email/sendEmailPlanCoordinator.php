<?php
session_start();

if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 9){
    echo "<script>alert('Usuário não reconhecido.');</script>";
    echo "<script>location= '../../../index.php';</script>";
    exit();
}

require_once '../../db/connect.php'; 
require_once('../functions.php');
require_once '../../../vendor/autoload.php';

if(isset($_GET["type"]) && $_GET["id"]){
    $type = cleanString($_GET["type"]);
    $id_internship_plan = decodeId(cleanString($_GET["id"]));
}
else{
    $_SESSION['feedback'] = 'error';
    $_SESSION['btn'] = 1;
    echo "<script>location= '../../../views/coordinator/coordinatorPage.php';</script>";
}

if($type == 'approve')
{
    try 
    {
        //atualizando aprovação do coordenador
        $query = "UPDATE internship_plan SET coordinator_approval_internship_plan = NOW(), coordinator_opinion_internship_plan = :coordinator_opinion_internship_plan, valid = true WHERE id_internship_plan = :id";
        
        $stmt = $conn->prepare($query);

        $stmt->bindValue(':coordinator_opinion_internship_plan', $_SESSION['opinion_coordinator']);
        $stmt->bindValue(':id', $id_internship_plan);
        $stmt->execute();

        //selecionando o nome e email do coordenador
        $query= "SELECT name_person, email_person FROM person WHERE id_person = ".$_SESSION["idUser"];

        $stmt = $conn->prepare($query);

        $stmt->execute();
        
        $return = $stmt->fetch(PDO::FETCH_ASSOC);
            
        if($return)
        {
            $name_coordinator =  $return['name_person']; 
            $email = $return['email_person'];
        }
    

        //selecionando o id do estágio
        $query= "SELECT fk_internship_data FROM internship_plan WHERE id_internship_plan = ".$id_internship_plan;

        $stmt = $conn->prepare($query);

        $stmt->execute();
        
        $return = $stmt->fetch(PDO::FETCH_ASSOC);

        $id_internship_data = $return['fk_internship_data'];
        
        //selecionando o nome e email do estagiário
        $query= "SELECT p.name_person, p.email_person FROM person p, internship_data i WHERE i.id_internship_data = ".$id_internship_data." AND p.id_person = i.fk_student";

        $stmt = $conn->prepare($query);

        $stmt->execute();
        
        $return = $stmt->fetch(PDO::FETCH_ASSOC);
        
        
        if($return)
        {
            $name_intern =  $return['name_person']; 
            $email_intern = $return['email_person'];
        }
        try 
        {
            $messageEmail = $email_intern; //email do estagiário que vai receber o relatório
            $messageSubject = "Estagio CTI";
            $mail_body = "Olá estagiário(a) ".$name_intern."! \nO Plano de Estágio foi aprovado por todas as entidades.\nEntre em sua página no Sistema de Controle de Estágios da CTI para visualizar e imprimir este documento. \n\nAtenciosamente, \n".$name_coordinator; 
            $mail_body = urlencode($mail_body); //transforma os caracteres de string para serem aceitos no URL

            $_SESSION["email"] = 1;
            //Enviar
            header("Location: https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=".$messageEmail."&su=".$messageSubject."&body=".$mail_body);
        }  
        catch (Exception $e) 
        {     
            echo "<script>alert('".$e->getMessage()."');</script>";  
            $_SESSION['feedback'] = 'errorEmail';
            if($_SESSION['access_level'] == 7)
            {
                echo "<script>location= '../../../views/coordinator/coordinatorPage.php';</script>";
                exit();
            } 
        }
    }
    catch (Exception $e) 
    {     
        //echo $e->getMessage();  
        $_SESSION['feedback'] = 'error';
        echo "<script>location= '../../../views/coordinator/coordinatorPage.php';</script>";
        exit();
    }
}
else if ($type == 'disapprove')//caso não seja aprovado
{
    try
    {
        //selecionando o id do estágio
        $query= "SELECT fk_internship_data FROM internship_plan WHERE id_internship_plan = ".$id_internship_plan;

        $stmt = $conn->prepare($query);

        $stmt->execute();
        
        $return = $stmt->fetch(PDO::FETCH_ASSOC);

        $id_internship_data = $return['fk_internship_data'];

        //selecionando o nome e email do estagiário
        $query= "SELECT p.id_person, p.name_person, p.email_person FROM person p, internship_data i WHERE i.id_internship_data = ".$id_internship_data." AND p.id_person = i.fk_student";

        $stmt = $conn->prepare($query);

        $stmt->execute();
        
        $return = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if($return)
        {
            $id_intern =  $return['id_person']; 
            $name_intern =  $return['name_person']; 
            $email_intern = $return['email_person'];
        }
        //atualizando aprovação do supervisor
        $query = "UPDATE student SET total_hours_student = NULL, 
                                    paid_hours_student = NULL, 
                                    over_total_student = NULL, 
                                    semester_observations_student = NULL, 
                                    year_observations_student = NULL
                WHERE fk_id = :id";
        
        $stmt = $conn->prepare($query);
        
        $stmt->bindValue(':id', $id_intern);

        $stmt->execute();

        $query = "UPDATE internship_plan SET advisor_approval_internship_plan = NULL, supervisor_approval_internship_plan = NULL, coordinator_approval_internship_plan = NULL, coordinator_opinion_internship_plan = NULL, valid = FALSE
                WHERE id_internship_plan = :id";
        
        $stmt = $conn->prepare($query);
        
        $stmt->bindValue(':id', $id_internship_plan);

        $stmt->execute();
        

        //selecionando o nome e email do coordenador
        $query= "SELECT name_person, email_person FROM person WHERE id_person = ".$_SESSION["idUser"];

        $stmt = $conn->prepare($query);

        $stmt->execute();
        
        $return = $stmt->fetch(PDO::FETCH_ASSOC);
            
        if($return)
        {
            $name_coordinator =  $return['name_person']; 
            $email = $return['email_person'];
        }

        try 
        {   
            $messageEmail = $email_intern; //email do orientador que vai receber o relatório
            $messageSubject = "Estagio CTI";
            $mail_body = "Olá estagiário(a) ".$name_intern."! \nO Plano de Estágio acabou de ser desaprovado pelo Coordenador.\nEntre em sua página no Sistema de Controle de Estágios da CTI para refazê-lo segundo as seguintes correções:\n[Digitar as devidas correções] \n\nAtenciosamente, \n".$name_coordinator; 
            $mail_body = urlencode($mail_body); //transforma os caracteres de string para serem aceitos no URL

             
            $_SESSION["email"] = 1;
            //Enviar
            header("Location: https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=".$messageEmail."&su=".$messageSubject."&body=".$mail_body);
        }  
        catch (Exception $e) 
        {     
            echo "<script>alert('".$e->getMessage()."');</script>";  
            $_SESSION['feedback'] = 'errorEmail';
            if($_SESSION['access_level'] == 7)
            {
                echo "<script>location= '../../../views/coordinator/coordinatorPage.php';</script>";
                exit();
            } 
        }
    }
    catch (Exception $e) 
    {     
        echo "<script>alert('".$e->getMessage()."');</script>";  
        echo $e->getMessage();
        $_SESSION['feedback'] = 'error';
        if($_SESSION['access_level'] == 7)
        {
            echo "<script>location= '../../../views/coordinator/coordinatorPage.php';</script>";
            exit();
        } 
    }
}

