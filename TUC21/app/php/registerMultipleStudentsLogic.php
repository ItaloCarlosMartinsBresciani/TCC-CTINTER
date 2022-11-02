<?php


use Google\Service\CloudTrace\Span;

session_start();
    
require_once('functions.php');
require_once('../db/connect.php');

$key = cleanString($_GET['key']);

$id_professor = cleanString($_GET['id_professor']);

$header = $_POST['check-header'];

$count_data = 0;
$count_student = 0;


try
{
    if($header){
        $data = $_POST['students-table'];
        $haystack = explode("*", $data);   
        if(!$haystack[1])
        {
            $_SESSION['feedback'] = 'errorData';
            $_SESSION['btn'] = 1;
            echo "<script>location = '../../views/professor/professorPage.php';</script>";
            exit();
        }
        $teste = preg_replace( "/\r|\n/", "", $haystack[1]);
        $student = explode(";", $haystack[1]);
        $size = sizeof($student);
        // echo $size;
        // var_dump($student);
    }
    else{
        $haystack = $_POST['students-table'];
        $student = explode(";", $haystack);
        $size = sizeof($student);
    }
}
catch(Exception $e)
{
    $_SESSION['feedback'] = 'errorRegister';
    $_SESSION['btn'] = 1;
    echo "<script>location = '../../views/professor/professorPage.php';</script>";
    exit();
}

function verifyCPF( $cpfstudent )
{
    /*$cpfstudent = "$cpfstudent";*/
    if (strpos($cpfstudent, "-") !== false)
    {
        $cpfstudent = str_replace("-", "", $cpfstudent);
    }
    if (strpos($cpfstudent, ".") !== false)
    {
        $cpfstudent = str_replace(".", "", $cpfstudent);
    }
    $sum = 0;
    $cpfstudent = str_split( $cpfstudent );
    $cpftrueverifier = array();
    $cpfnumbers = array_splice( $cpfstudent , 0, 9 );
    $cpfdefault = array(10, 9, 8, 7, 6, 5, 4, 3, 2);
    for ( $i = 0; $i <= 8; $i++ )
    {
        $sum += $cpfnumbers[$i]*$cpfdefault[$i];
    }
    $sumresult = $sum % 11;  
    if ( $sumresult < 2 )
    {
        $cpftrueverifier[0] = 0;
    }
    else
    {
        $cpftrueverifier[0] = 11-$sumresult;
    }
    $sum = 0;
    $cpfdefault = array(11, 10, 9, 8, 7, 6, 5, 4, 3, 2);
    $cpfnumbers[9] = $cpftrueverifier[0];
    for ( $i = 0; $i <= 9; $i++ )
    {
        $sum += $cpfnumbers[$i]*$cpfdefault[$i];
    }
    $sumresult = $sum % 11;
    if ( $sumresult < 2 )
    {
        $cpftrueverifier[1] = 0;
    }
    else
    {
        $cpftrueverifier[1] = 11 - $sumresult;
    }
    $returner = false;
    if ( $cpfstudent == $cpftrueverifier )
    {
        $returner = true;
    }


    $cpfver = array_merge($cpfnumbers, $cpfstudent);

    if ( count(array_unique($cpfver)) == 1 || $cpfver == array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0) )

    {

        $returner = false;

    }
    return $returner;
}

//$firstAppear = strpos($haystack, $needle);
//foreach ($limit as $value)

//var_dump($student);

/*for($t = 0; $t < $size; $t++)
{
    $lines = explode("|", $student[$t]);
    $sizeline = sizeof($lines);
    for($i = 0; $i < $sizeline; $i++)
        echo $lines[$i]."<br>";
}*/
//   0      1      2        3       4       5       6       7                  8
//(nome | email | rg | tratamento| cpf | telefone | RA | código curso | ano de entrada)

try
{    
    for($t = 0; $t < $size; $t++)
    {
        $lines = explode("|", $student[$t]);

        if (sizeof($lines) < 9)
        {
            $_SESSION['feedback'] = 'errorData';
            $_SESSION['btn'] = 1;
            echo "<script>location = '../../views/professor/professorPage.php';</script>";
            exit();
        }

        if (verifyCPF($lines[4]))
        {
            $query = 'INSERT INTO person VALUES(DEFAULT, :cpf_person, :name_person, :email_person, :telephone_person, :rg_person, DEFAULT, DEFAULT, :treatment_person, 1, DEFAULT, :who_edited, :who_invited, DEFAULT, TRUE);';

            $stmt = $conn->prepare($query);

            $stmt->bindValue(':cpf_person', $lines[4]);
            $stmt->bindValue(':name_person', preg_replace( "/\r|\n/", "", $lines[0]));
            $stmt->bindValue(':email_person', $lines[1]);
            $stmt->bindValue(':telephone_person', $lines[5]);
            $stmt->bindValue(':rg_person', $lines[2]);
            $stmt->bindValue(':treatment_person', $lines[3]);
            $stmt->bindValue(':who_edited', preg_replace( "/\r|\n/", "", $lines[0]));
            $stmt->bindValue(':who_invited', "Professor");

            $stmt->execute();

            //Obtendo id estudante
            $query = 'SELECT id_person FROM person WHERE email_person = :email';

            $stmt = $conn->prepare($query);

            $stmt->bindValue(':email', $lines[1]);

            $stmt->execute();

            $return = $stmt->fetch(PDO::FETCH_ASSOC); 
            
            $idStudent = $return['id_person'];

            
            // Obtendo id Universidade
            $query = 'SELECT fk_university FROM university_employee WHERE fk_id = '.$id_professor.'';
            $stmt = $conn->prepare($query);

            $stmt->execute();

            $return = $stmt->fetch(PDO::FETCH_ASSOC); 
            
            $idUniversity = $return['fk_university']; 


            // Cadastro de estudante na tabela de estudantes da universidade
            $query = 'INSERT INTO student VALUES('.$idStudent.', :ra_student, NULL, DEFAULT, :course_code_student, NULL, NULL, NULL, :year_entry_student, NULL, NULL, '.$idUniversity.', '.$id_professor.');';

            $stmt = $conn->prepare($query);

            $stmt->bindValue(':ra_student', $lines[6]);
            $stmt->bindValue(':course_code_student', $lines[7]);
            $stmt->bindValue(':year_entry_student', $lines[8]);

            $stmt->execute();

            // Cadastro de estudante na tabela de edição
            $query = "INSERT INTO change_data VALUES (DEFAULT, FALSE, NULL, DEFAULT, DEFAULT, ".$idStudent.")";

            $stmt = $conn->prepare($query);

            $stmt->execute();

            $_SESSION['feedback'] = 'sucessRegister';
            $_SESSION['btn'] = 1;
        }
        else 
        {
            $_SESSION['feedback'] = 'errorCPF';
            $_SESSION['btn'] = 1;
            echo "<script>location = '../../views/professor/professorPage.php';</script>";
            exit();
        }
    }
    $_SESSION['feedback'] = 'sucessRegister';
    $_SESSION['btn'] = 1;
    
}
catch (Exception $e)
{        
    echo $e->getMessage();
    $_SESSION['feedback'] = 'errorRegister';
    $_SESSION['btn'] = 1;
    echo "<script>location = '../../views/professor/professorPage.php';</script>";
    exit();
}

echo "<script>location = '../../views/professor/professorPage.php';</script>";
exit();

