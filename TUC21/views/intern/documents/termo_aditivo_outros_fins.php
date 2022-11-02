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

$pdf->SetFont('arial','B',13);

//$pdf->Image("imagens/unesp.png",mm2pt(30),mm2pt(10),mm2pt(125));

//$pdf->Image("imagens/logoFEB.png",mm2pt(170),mm2pt(10),mm2pt(22));

$pdf->Ln(mm2pt(15));

$pdf->Cell(450,60,utf8_decode("TERMO ADITIVO DE ESTÁGIO"),0,0,"C",false);

$pdf->Ln(mm2pt(22));

$pdf->SetFont('arial','',11);

$pdf->MultiCell(450,17,utf8_decode('ADITAMENTO AO TERMO DE COMPROMISSO DE ESTÁGIO, firmado entre a UNIDADE CONCEDENTE: '.$return_company["name_company"].', e o ESTAGIÁRIO: '.$return_intern["name_person"].', aluno regularmente matriculado sob o nº '.$return_intern["ra_student"].', da '.$return_intern["course_code_student"].' série, da Habilitação Profissional em '.$return["course_internship_data"].' do Colégio Técnico Industrial "Prof. Isaac Portal Roldán" - UNESP -  Bauru, já qualificados, respectivamente no Acordo de cooperação e no decorrente Termo de Compromisso de Estágio - TCE.'),0,"J",false);

$pdf->Ln(mm2pt(6));

$reason = $_POST['reason'];

$pdf->MultiCell(450,17,utf8_decode("1ª - Este termo Aditivo $reason"),0,"J",false);

$pdf->Ln(mm2pt(6));

$pdf->MultiCell(450,17,utf8_decode("2ª - Permanecem inalteradas todas as demais disposições do TCE, do qual este Termo Aditivo passa a fazer parte integrante."),0,"J",false);

$pdf->Ln(mm2pt(6));

$pdf->MultiCell(450,17,utf8_decode("3ª - No desenvolvimento deste novo período de estágio caberá ao Estagiário continuar seguindo as normas do TCE."),0,"J",false);

$pdf->Ln(mm2pt(6));

$pdf->MultiCell(450,17,utf8_decode("            E por estarem de inteiro e comum acordo com as condições e dizeres deste Termo Aditivo, as partes assinam em três vias de igual teor."),0,"J",false);

$pdf->Ln(mm2pt(10));

$pdf->MultiCell(450,15,utf8_decode($return_company['city_company'].", ".$current_day." de ".$mes." de ".$current_year."."),0,"R",false);

$pdf->SetFont('arial','',8);

$pdf->Ln(mm2pt(10));

$pdf->Cell(0,15,"_____________________________________________", 0, 0, "R", false);

$pdf->ln(mm2pt(5));

$pdf->MultiCell(450,15,utf8_decode($return_company["legal_representative_company"]),0,"R",false);

$pdf->ln(mm2pt(15));

$pdf->Cell(0,15,"_______________________________________________________", 0, 0,"R", false);

$pdf->ln(mm2pt(5));

$pdf->MultiCell(450,15,utf8_decode($return_intern["name_person"].'/'.$return_intern["rg_person"]),0,"R",false);

$pdf->ln(mm2pt(15));

$pdf->Cell(0,15,"_____________________________________________", 0, 0, "R", false);

$pdf->ln(mm2pt(5));

$pdf->MultiCell(450,15,utf8_decode($return_advisor["name_person"]),0,"R",false);

$pdf->Output("termo_aditivo_outros_fins.pdf","I");

?>