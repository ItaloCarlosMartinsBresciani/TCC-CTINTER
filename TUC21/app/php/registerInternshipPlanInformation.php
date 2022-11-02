<?php
session_start();
    
require_once('functions.php');
require_once('../db/connect.php');

if (isset($_GET['type'])) {
    try {
        $type =  cleanString($_GET['type']);
        $id = $_SESSION['idUser'];
        if ($type != 'intern')
        {
            if (isset($_GET['id_internship']))
            {
                try
                {
                    $id_internship_plan = cleanString(decodeId($_GET['id_internship']));
                }
                catch(TypeError)
                {
                    echo "<script>alert('Não foi passado nenhum id de estágio válido!');</script>";
                    echo "<script>location= '../../views/index.php';</script>";
                }
            }
            else
            {
                header('Location: ../../views/index.php');
            }
        }
    }
    catch (TypeError) {
        header('Location: ../../views/index.php');
    }   
} else {
    header('Location: ../../views/index.php');
}

if ($type == 'intern')
{
    try
    {        


        // if (isset($_FILES)) {
           
    
        //     $permitedFormats = array("jpg","png","jpeg","webpm");
        //     $filetype = $_FILES['uploadfile']['type'];
        //     $extension = explode("/",$filetype);
    
        //     if ( !in_array( $permitedFormats ) ) {
        //         header('location: ../public/views/user.php?error=2');
        //         exit;
        //     }
    
        //     $tempname = $_FILES["uploadfile"]["tmp_name"];
        //     $folder = "/email/reports_upload/";
    
        //     $rename = $_SESSION['idUser'].'Upload'.date('Ymd').$_SESSION['idUser']*100+rand(0,100000).".".end($extension);
    
        //     if (move_uploaded_file($tempname, $folder.$rename)) {
            
        //         $query = "INSERT INTO user_picture VALUES(DEFAULT,'$rename', ".$_SESSION['idUser'].")";
    
        //         $stmt = $conn -> query($query);
            
        //         if ($stmt) {
        //             header('location: ../public/views/user.php');
        //             exit;
        //         } else {
        //             //Failed to insert into user_picture table
        //             header('location: ../public/views/user.php?error=3');
        //             exit;
        //         }
        //     } else {
        //         //Failed to upload image
        //         header('location: ../public/views/user.php?error=3');
        //         exit;
        //     }
        // } else {
        //     header('location: ../public/views/user.php?error=3');
        //     exit;
        // }
    



        //Selecionando o id do estágio ligado ao estagiário
        $query = "SELECT id_internship_data FROM internship_data WHERE valid and finished = false and fk_student = ".$id;
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $return = $stmt->fetch(PDO::FETCH_ASSOC); 
        $id_internship = $return['id_internship_data'];

        //Inserindo os novos dados na tabela de estudante para preencher o documento
        $over_total_student = ($_SESSION["paid_hours_student"] / $_SESSION["total_hours_student"]) * 100 ;
        
        $query = "UPDATE student SET total_hours_student = :total_hours_student, 
                                     paid_hours_student = :paid_hours_student, 
                                     over_total_student = :over_total_student, 
                                     semester_observations_student = :semester_observations_student, 
                                     year_observations_student = :year_observations_student,
                                     monday = :monday,
                                     tuesday = :tuesday,
                                     wednesday = :wednesday,
                                     thursday = :thursday,
                                     friday = :friday,
                                     saturday = :saturday,
                                     end_monday = :end_monday,
                                     end_tuesday = :end_tuesday,
                                     end_wednesday = :end_wednesday,
                                     end_thursday = :end_thursday,
                                     end_friday = :end_friday,
                                     end_saturday = :end_saturday


                  WHERE fk_id = ".$id;
                  
        $stmt = $conn->prepare($query);


        $stmt->bindValue(':total_hours_student', $_SESSION["total_hours_student"]);
        $stmt->bindValue(':paid_hours_student', $_SESSION["paid_hours_student"]);
        $stmt->bindValue(':over_total_student', $over_total_student);
        $stmt->bindValue(':semester_observations_student', $_SESSION["semester_observations_student"]);
        $stmt->bindValue(':year_observations_student', $_SESSION["year_observations_student"]);
        $stmt->bindValue(':monday', $_SESSION["monday"]);
        $stmt->bindValue(':tuesday', $_SESSION["tuesday"]);
        $stmt->bindValue(':wednesday', $_SESSION["wednesday"]);
        $stmt->bindValue(':thursday', $_SESSION["thursday"]);
        $stmt->bindValue(':friday', $_SESSION["friday"]);
        $stmt->bindValue(':saturday', $_SESSION["saturday"]);
        $stmt->bindValue(':end_monday', $_SESSION["end_monday"]);
        $stmt->bindValue(':end_tuesday', $_SESSION["end_tuesday"]);
        $stmt->bindValue(':end_wednesday', $_SESSION["end_wednesday"]);
        $stmt->bindValue(':end_thursday', $_SESSION["end_thursday"]);
        $stmt->bindValue(':end_friday', $_SESSION["end_friday"]);
        $stmt->bindValue(':end_saturday', $_SESSION["end_saturday"]);

       
        $stmt->execute();

        //echo $id; id é 67

        $query = "SELECT ip.supervisor_approval_internship_plan, ip.advisor_approval_internship_plan 
        FROM internship_plan ip, internship_data i 
        WHERE ip.fk_internship_data = i.id_internship_data AND i.fk_student =".$id;
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $return = $stmt->fetch(PDO::FETCH_ASSOC); 

        if ($return)
        {
            //Atualizando aprovações 
            if ($return['supervisor_approval_internship_plan'] == false)
            {
               // echo $id_internship;
                $query = "UPDATE internship_plan SET supervisor_approval_internship_plan = null
                          WHERE fk_internship_data = ".$id_internship;
                $stmt = $conn->prepare($query);
                $stmt->execute();
            }
            if ($return['supervisor_approval_internship_plan'] == false)
            {
                $query = "UPDATE internship_plan SET advisor_approval_internship_plan = null
                          WHERE fk_internship_data = ".$id_internship;
                $stmt = $conn->prepare($query);
                $stmt->execute();
            }       
        }
        else
        {
            //Inserindo documento na tabela de planos de estágio
            $query = "INSERT INTO internship_plan (fk_internship_data) VALUES(:id_internship)";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':id_internship', $id_internship);
            $stmt->execute();
        }

        $_SESSION['feedback'] = 'successInternshipPlan';
        $_SESSION['btn'] = 1;
        
        header('Location: ../../views/intern/internPage.php');
        //header('Location: email/sendEmailphoto.php');
        
    }
    catch(Exception $ex)
    {
        $_SESSION['feedback'] = 'errorInternshipPlan';
        echo $ex->getMessage();
        exit();
       // header('Location: ../../views/home.php');
    }
}

?>