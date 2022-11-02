
<?php
//fala galerinha do youtube
use Google\Service\CloudTrace\Span;

//echo "<script>alert('Entrei!!!!!!!');</script>";

session_start();
    
    require_once('functions.php');
    require_once('../db/connect.php');

    if (isset($_GET['id_intern']) && isset($_GET['id_internship_data'])) {
        try {
        $id_intern_Hex = cleanString($_GET['id_intern']);
        $id_internship_Hex = cleanString($_GET['id_internship_data']);

          $id_intern = decodeId($id_intern_Hex);
          $id_internship = decodeId($id_internship_Hex);
        }
        catch (TypeError) {
          //header('Location: ../../views/intern/internPage.php');
        }   
    } else {
      //header('Location: ../../views/intern/internPage.php');
    }


    $inf[] = "role_internship_data";
    $inf[] = "area_internship_data";
    $inf[] = "total_hours_internship_data";
    $inf[] = "week_hours_internship_data";
    $inf[] = "daily_hours";
    $inf[] = "lunch_time";
    $inf[] = "start_date_internship_data";
    $inf[] = "end_date_internship_data";
    //$inf[] = "start_time_internship_data";
    //$inf[] = "end_time_internship_data";
    $inf[] = "scholarship_internship_data";
    $inf[] = "scholarship_value_internship_data";
    $inf[] = "nature_internship_data";
    $inf[] = "description_internship_data";
            

    foreach($inf as $value) {  
         
        $_SESSION[$value] = cleanString($_SESSION[$value]);   
        
        
        if(empty($_SESSION[$value]) && $value != "scholarship_value_internship_data")
        { //se depois de limpar não há informação dentro
            echo "<script>alert('Os dados digitados não são válidos! Tente o cadastro novamente!');</script>";
            //echo "<script>location = '../../views/putInternshipDataInformation.php?id_intern=".$id_intern_Hex."&id_internship_data=".$id_internship_Hex."';</script>";
            //exit();
        }
    } 

    // Cadastro do Estagiário
    try
    {
        // if ($_SESSION["scholarship_internship_data"] == False)
        // {
        //     $_SESSION["scholarship_value_internship_data"] = 0;
        // }

        $query = "SELECT c.name_company FROM company c, internship_data i WHERE c.id_company = i.fk_company AND i.id_internship_data = ".$id_internship."";
        
        $stmt = $conn->prepare($query);

        $stmt->execute();

        $return = $stmt->fetch(PDO::FETCH_ASSOC);

        $date_created = date_create($_SESSION["start_date_internship_data"]);
        $date = date_format($date_created, 'd/m/Y');
        $name = $_SESSION["role_internship_data"]."-".$return["name_company"]."-".$date;
        
        $query = 'UPDATE internship_data
                  SET name_internship_data = :name_internship_data,
                  role_internship_data = :role_internship_data,
                  area_internship_data = :area_internship_data,
                  total_hours_internship_data = :total_hours_internship_data,
                  week_hours_internship_data = :week_hours_internship_data,
                  daily_hours = :daily_hours,
                  lunch_time = :lunch_time,
                  start_date_internship_data = :start_date_internship_data,
                  end_date_internship_data = :end_date_internship_data,
                  -- start_time_internship_data = :start_time_internship_data,
                  -- end_time_internship_data = :end_time_internship_data,
                  scholarship_internship_data = :scholarship_internship_data,';

        if (!empty($_SESSION["scholarship_value_internship_data"]))
        {
          $query = $query.'scholarship_value_internship_data = :scholarship_value_internship_data,';
        }
        else
        {          
          $query = $query.'scholarship_value_internship_data = 0,';
        }
        $query = $query.'nature_internship_data = :nature_internship_data,
                         description_internship_data = :description_internship_data         
                         WHERE id_internship_data = '.$id_internship.'';

       // echo $query;    

        $stmt = $conn->prepare($query);

        $stmt->bindValue(':name_internship_data', $name);
        $stmt->bindValue(':role_internship_data', $_SESSION["role_internship_data"]);
        $stmt->bindValue(':area_internship_data', $_SESSION["area_internship_data"]);
        $stmt->bindValue(':total_hours_internship_data', $_SESSION["total_hours_internship_data"]);
        $stmt->bindValue(':week_hours_internship_data', $_SESSION["week_hours_internship_data"]);
        $stmt->bindValue(':daily_hours', $_SESSION["daily_hours"]);
        $stmt->bindValue(':lunch_time', $_SESSION["lunch_time"]);
        $stmt->bindValue(':start_date_internship_data', $_SESSION["start_date_internship_data"]);
        $stmt->bindValue(':end_date_internship_data', $_SESSION["end_date_internship_data"]);
        // $stmt->bindValue(':start_time_internship_data', $_SESSION["start_time_internship_data"]);
        // $stmt->bindValue(':end_time_internship_data', $_SESSION["end_time_internship_data"] );   
        $stmt->bindValue(':scholarship_internship_data', $_SESSION["scholarship_internship_data"] );        
        
        if (!empty($_SESSION["scholarship_value_internship_data"]))
          $stmt->bindValue(':scholarship_value_internship_data', $_SESSION["scholarship_value_internship_data"]);
        

        $stmt->bindValue(':nature_internship_data', $_SESSION["nature_internship_data"] );
        $stmt->bindValue(':description_internship_data', $_SESSION["description_internship_data"] );        

        $stmt->execute();

        //inserindo na tabela change_data_internship
        $query = "INSERT INTO change_data_internship VALUES (DEFAULT, FALSE, NULL, DEFAULT, DEFAULT, ".$id_internship.")";

        $stmt = $conn->prepare($query);

        $stmt->execute();

        echo "<script>alert('Dados inseridos com sucesso!');</script>";
        echo "<script>location = '../../views/intern/internPage.php';</script>";
        exit();
    }
    catch (Exception $e)
    {        
        //enviar email com o erro (".$e->getMessage().")
      //echo "<script>alert('".$e->getMessage()."');</script>";
    
        echo "<script>alert('Erro na inserção dos dados!');</script>";
        echo $e->getMessage();
        echo "<script>location = '../../views/putInternshipDataInformation.php?id_intern=".$id_intern."';</script>";
        exit();
    }