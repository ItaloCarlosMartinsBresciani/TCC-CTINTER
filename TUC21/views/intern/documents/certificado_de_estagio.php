<?php

session_start();

require_once("../../../app/php/fpdf/fpdf.php");
require_once('../../../app/db/connect.php');
require_once('../../../app/php/functions.php');

$pdf= new FPDF("P","pt","A4");


function pt2mm($pt){

    return($pt/2.83465);

}

function mm2pt($mm){
    return($mm*2.83465);
}

try
{
    //recuperando o ID da empresa
    $query = "SELECT * FROM internship_data WHERE fk_student = ".$_SESSION["idUser"]."";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC);

    $id_intern = $_SESSION["idUser"];
    $id_company = $return["fk_company"];
    $course = $return["course_internship_data"];

    //pegando as informações da empresa
    $query = "SELECT * FROM company WHERE id_company = ".$id_company."";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return_company = $stmt->fetch(PDO::FETCH_ASSOC);

    //pegando as informações do estagiário
    $query = "SELECT s.*, p.* FROM student s, person p WHERE p.id_person = s.fk_id AND p.id_person =".$id_intern."";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return_intern = $stmt->fetch(PDO::FETCH_ASSOC);

    $query = "SELECT fk_supervisor, fk_advisor FROM internship_data WHERE fk_student =".$id_intern."";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return_internship_data = $stmt->fetch(PDO::FETCH_ASSOC);
    $id_advisor = $return_internship_data['fk_advisor'];
    $id_supervisor  =  $return_internship_data['fk_supervisor'];

    //pegando as informações do orientador
    $query = "SELECT u.*, p.* FROM university_employee u, person p WHERE p.id_person = u.fk_id AND p.id_person =".$id_advisor."";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return_advisor = $stmt->fetch(PDO::FETCH_ASSOC);

    //pegando as informações do supervisor
    $query = "SELECT c.*, p.* FROM company_employee c, person p WHERE p.id_person = c.fk_id AND p.id_person =".$id_supervisor."";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return_supervisor = $stmt->fetch(PDO::FETCH_ASSOC);
}
catch(Exception $ex)
{
    echo $ex->getMessage();// ook niceeee ue? 
}
//data atual
$current_day = date('d'); 
$current_month = date('m'); 
$current_year = date('Y'); 

if ($current_month == "01") 
    $mes = "Janeiro";    
else if ($current_month == "02")
    $mes = "Fevereiro";
else if ($current_month == "03")
    $mes = "Março";
else if ($current_month == "04")
    $mes = "Abril";
else if ($current_month == "05")
    $mes = "Maio";
else if ($current_month == "06")
    $mes = "Junho";
else if ($current_month == "07")
    $mes = "Julho";
else if ($current_month == "08")
    $mes = "Agosto";
else if ($current_month == "09")
    $mes = "Setembro";
else if ($current_month == "10")
    $mes = "Outubro";
else if ($current_month == "11")
    $mes = "Novembro    ";
else if ($current_month == "12")
    $mes = "Dezembro";


/*Formatação do pdf ---------------------------------------------------------------------------------------------------------------------------- */

$pdf->AddPage();

$pdf->SetMargins(mm2pt(30),mm2pt(30),mm2pt(20));

$pdf->SetFont('arial','B',13);

//$pdf->Image("imagens/unesp.png",mm2pt(30),mm2pt(10),mm2pt(125));

//$pdf->Image("imagens/logoFEB.png",mm2pt(170),mm2pt(10),mm2pt(22));

$pdf->Ln(mm2pt(15));

$pdf->Cell(450,60, utf8_decode("CERTIFICADO DE ESTÁGIO"),0,0,"C",false);

$pdf->SetFont('arial','',11);

$pdf->ln(mm2pt(23));

$start_date = date_create($return["start_date_internship_data"]);

$end_date = date_create($return["end_date_internship_data"]);

$pdf->MultiCell(450,21,utf8_decode('    Certificamos que o(a) Sr(a) '.$return_intern["name_person"].', aluno(a) do Curso Técnico Habilitação em '.$return["course_internship_data"].' do COLÉGIO TÉCNICO INDUSTRIAL "PROF. ISAAC PORTAL ROLDAN" - UNESP - BAURU estagiou nesta Empresa no período de '.date_format($start_date,"d/m/Y").' a '.date_format($end_date,"d/m/Y").' cumprindo '.$return["total_hours_internship_data"].' horas, tendo realizado o treinamento técnico-profissional na(s) seguinte(s) atividade(s): '),0,"J",false);

$pdf->ln(mm2pt(5));

$pdf->MultiCell(450,21,utf8_decode($return["description_internship_data"]),0,"C",false);

$pdf->ln(mm2pt(20));

$pdf->Cell(450,20,utf8_decode("".$current_day." de ".$mes." de ".$current_year.""),0,0,"C",false);

$pdf->ln(mm2pt(20));

$pdf->Cell(450,20,utf8_decode("_______________________________"),0,0,"C",false);

$pdf->SetFont('arial','I',10);

$pdf->ln(mm2pt(6));

$pdf->Cell(450,20,utf8_decode($return_supervisor["name_person"]),0,0,"C",false);

$pdf->ln(mm2pt(6));

$pdf->Cell(450,20,utf8_decode($return_supervisor["rg_person"]),0,0,"C",false);

$pdf->Output("certificado_de_estagio.pdf","I");

?>