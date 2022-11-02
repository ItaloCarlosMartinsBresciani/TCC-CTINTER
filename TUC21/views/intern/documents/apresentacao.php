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

$pdf->SetFont('arial','B',15);

//$pdf->Image("imagens/unesp.png",mm2pt(30),mm2pt(10),mm2pt(125));

//$pdf->Image("imagens/logoFEB.png",mm2pt(170),mm2pt(10),mm2pt(22));

$pdf->Ln(mm2pt(15));

$pdf->Cell(450,60,utf8_decode("1. Apresentação"),0,0,"L",false);

$pdf->SetFont('arial','B',12);

$pdf->Ln(mm2pt(25));

$pdf->MultiCell(450,15,utf8_decode("RELATÓRIO TÉCNICO DE ESTÁGIO"),0,"C",false);

$pdf->SetFont('arial','',11);

$pdf->Ln(mm2pt(8));

$pdf->MultiCell(450,15,utf8_decode("Estagiário: ".$return_intern["name_person"].""),0,"J",false);

$pdf->MultiCell(450,15,utf8_decode("Habilitação: ".$return["course_internship_data"]."                         Série: ".$return_intern["course_code_student"].""),0,"J",false);

$pdf->Ln(mm2pt(8));

$pdf->MultiCell(450,15,utf8_decode("Empresa: ".$return_company["name_company"].""),0,"J",false);

$pdf->MultiCell(450,15,utf8_decode("Setor: ".$return_company["activity_branch_company"].""),0,"J",false);

$pdf->MultiCell(450,15,utf8_decode("Endereço: ".$return_company["address_company"].""),0,"J",false);

$pdf->MultiCell(450,15,utf8_decode("Cidade: ".$return_company["city_company"]."                                       UF: ".$return_company["state_company"].""),0,"J",false);

$pdf->MultiCell(450,15,utf8_decode("Telefone: ".$return_company["telephone_company"].""),0,"J",false);

$pdf->Ln(mm2pt(8));

$pdf->MultiCell(450,15,utf8_decode("RESPONSÁVEL PELO ESTÁGIO: ".$return_supervisor["name_person"]." "),0,"J",false);

$pdf->Ln(mm2pt(8));

$pdf->MultiCell(450,15,utf8_decode("COORDENADOR DE ESTÁGIO: ".$return_advisor["name_person"].""),0,"J",false);

$pdf->Ln(mm2pt(8));

$start_date = date_create($return["start_date_internship_data"]);

$end_date = date_create($return["end_date_internship_data"]);

$pdf->MultiCell(450,15,utf8_decode("INÍCIO: ".date_format($start_date,"d/m/Y")."          TÉRMINO: ".date_format($end_date,"d/m/Y").""),0,"J",false);

$pdf->Ln(mm2pt(8));

$pdf->MultiCell(450,15,utf8_decode("TOTAL DE HORAS DE ESTÁGIO: ".$return["total_hours_internship_data"]." HORAS"),0,"J",false);

$pdf->Ln(mm2pt(10));

$pdf->MultiCell(450,15,utf8_decode("_______________________________                     _______________________________"),0,"L",false);

$pdf->SetFont('arial','I',8);

$pdf->MultiCell(450,15,utf8_decode("              ".$return_supervisor["name_person"]." - ".$return_supervisor["rg_person"]."                                                                      ".$return_advisor["name_person"].""),0,"J",false);

$pdf->Output("apresentacao.pdf","I");

?>