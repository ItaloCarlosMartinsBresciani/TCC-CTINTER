<?php

session_start();

require_once("../../../app/php/fpdf/fpdf.php");
require_once('../../../app/db/connect.php');
require_once('../../../app/php/functions.php');

$today = date('d/m/Y');
$signature_date = date_create($_POST['signature_date_TC']);
if(isset($_POST['signature_date_TC']) && $signature_date <= $today)//date_format($signature_date, "d/m/Y")
{
    $signature_date = cleanString($_POST['signature_date_TC']);
}
else
{
    $_SESSION['feedback'] = "errorModalInfo";
    $_SESSION['btn'] = 1;
    header('Location: ../InternPage.php');
    exit();
}

$pdf= new FPDF("P","pt","A4");

function pt2mm($pt){

    return($pt/2.83465);

}

function mm2pt($mm){

    return($mm*2.83465);

}

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

$start_date = date_create($return['start_date_internship_data']);
$end_date = date_create($return['end_date_internship_data']);
// $start_time = date_create($return['start_time_internship_data']);
// $end_time = date_create($return['end_time_internship_data']);
$signature_date_TC = date_create($signature_date);

$pdf->AddPage();

$pdf->SetMargins(mm2pt(30),mm2pt(30),mm2pt(20));

$pdf->SetFont('arial','',12);

$pdf->Ln(mm2pt(20));

$pdf->Cell(450,60,utf8_decode("À"),0,0,"L",false);

$pdf->ln(mm2pt(5));

$pdf->SetFont('arial','B',12);

$pdf->Cell(450,60,utf8_decode(strtoupper($return_company["name_company"])),0,0,"L",false);

$pdf->ln(mm2pt(5));

$pdf->SetFont('arial','',12);

$pdf->Cell(450,60,utf8_decode("com cópia para a UNESP - Faculdade de Engenharia"),0,0,"L",false);


$pdf->ln(mm2pt(30));

$pdf->SetFont('arial','U',12);

$pdf->ln(mm2pt(30));

$pdf->SetFont('arial','',11.5);

$pdf->MultiCell(450,20,utf8_decode("Com base na cláusula 5º do Termo de Compromisso de Estágio assinado em ".date_format($signature_date_TC, "d/m/Y").", venho denunciar o contrato, de forma unilateral e por minha livre e espontânea vontade, avisando a empresa ".strtoupper($return_company['name_company'])."  e a Instituição de ensino - Faculdade de Engenharia, da UNESP - campus de Bauru, que a partir de 05 dias da data abaixo, darei por encerrado meu estágio profissional."), 0, "J", false);

$pdf->ln(mm2pt(5));

$pdf->Cell(450,20,utf8_decode("Bauru, ".$current_day." de ".$mes." de ".$current_year.". "),0,0,"C",false);

$pdf->ln(mm2pt(30));

$pdf->Cell(0,15,$return_intern["name_person"], 0, "R", false);

$pdf->Cell(0,15,$return_intern["rg_person"], 0, "R", false);

$pdf->ln(mm2pt(20));

$pdf->SetFont('arial','B',11.5);

$pdf->Cell(0,15,"Ciente:", 0, "R", false);

$pdf->SetFont('arial','',11.5);

$pdf->ln(mm2pt(20));

$pdf->Cell(0,15,utf8_decode($return_company['name_company']), 0, 0, "L", false);

$pdf->ln(mm2pt(20));

//$pdf->SetFont('arial','',7);
$pdf->Cell(0,15,utf8_decode("FEB -  UNESP - CAMPUS DE BAURU"), 0, 0, "L", false);


$pdf->Output("TermoDesligamentoEstagio.pdf","I");

?>