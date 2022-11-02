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
$query = "SELECT * FROM internship_data WHERE fk_student = ".$_SESSION["idUser"].""; //AND id_internship_data = $_POST["id_internship"]

$stmt = $conn->prepare($query);

$stmt->execute();

$return = $stmt->fetch(PDO::FETCH_ASSOC);

$id_intern = $_SESSION["idUser"];
$id_advisor = $return["fk_advisor"];
$id_supervisor = $return["fk_supervisor"];
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


//selecionando o rg do supervisor
$query = "SELECT p.rg_person, u.cic_university_employee FROM person p, university_employee u WHERE p.id_person = ".$id_advisor."";

$stmt = $conn->prepare($query);

$stmt->execute();

$return_advisor = $stmt->fetch(PDO::FETCH_ASSOC);


//selecionando o rg do supervisor
$query = "SELECT p.rg_person, c.cic_company_employee FROM person p, company_employee c WHERE p.id_person = c.fk_id AND p.id_person = ".$id_supervisor."";

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

$pdf->SetFont('arial','B',14);

//$pdf->Image("imagens/unesp.png",mm2pt(30),mm2pt(10),mm2pt(125));

//$pdf->Image("imagens/logoFEB.png",mm2pt(170),mm2pt(10),mm2pt(22));

$pdf->Ln(mm2pt(20));

$pdf->Cell(450,60, utf8_decode("TERMO ADITIVO DE PRORROGAÇÃO DE ESTÁGIO"),0,0,"C",false);

$pdf->ln(mm2pt(20));

$pdf->SetFont('arial','',11);

$pdf->ln(mm2pt(10));

$name_company = strtoupper($return_company['name_company']);

$pdf->MultiCell(450,20,utf8_decode($name_company.", estabelecida à ".$return_company['address_company'].", Estado de ".$return_company['state_company'].", doravante denominada EMPRESA, pelo seu Representante infra assinado, e o estudante ".strtoupper($return_intern['name_person']).", doravante denominado ESTAGIÁRIO, aluno regularmente matriculado sob o nº ".$return_intern['ra_student'].", R.G. ".$return_intern['rg_person'].", da Faculdade de Engenharia UNESP, Câmpus de Bauru-SP, que também assina este TERMO, na condição de Interveniente, nos termos da Lei nº 11.788/08, mediante a cláusula e condições abaixo: Cláusula Primeira: Prorroga a partir de ".$current_day."/".$current_month."/".$current_year." a ".$current_day."/".$current_month."/".$current_year." o contrato de estágio."), 0,"J",false);

$pdf->ln(mm2pt(1));

$pdf->MultiCell(450,20,utf8_decode("E, por estarem em comum acordo com à Cláusula e condição deste Termo Aditivo de Estágio, as partes assinam em 3 (três) vias de igual teor."), 0,"J",false);

//$pdf->Cell(450,20,utf8_decode("E, por estarem em comum acordo com à Cláusula e condição deste Termo Aditivo de Estágio, as partes assinam em 3 (três) vias de igual teor."),0,0,"J",false);

$pdf->ln(mm2pt(10));

$pdf->Cell(450,20,utf8_decode($return_company['city_company'].", ".$current_day." de ".$mes." de ".$current_year."."),0,0,"J",false);

$pdf->ln(mm2pt(20));

$pdf->Cell(450,10,utf8_decode("____________________________________________________"),0,0,"L",false);

$pdf->ln(mm2pt(5));

$pdf->Cell(450,10,utf8_decode("Carimbo e assinatura da empresa"),0,0,"L",false);

$pdf->ln(mm2pt(16));

$pdf->Cell(450,10,utf8_decode("____________________________________________________"),0,0,"L",false);

$pdf->ln(mm2pt(5));

$pdf->Cell(450,10,utf8_decode("Carimbo e assinatura da Faculdade"),0,0,"L",false);

$pdf->ln(mm2pt(16));

$pdf->Cell(450,10,utf8_decode("____________________________________________________"),0,0,"L",false);

$pdf->ln(mm2pt(5));

$pdf->Cell(450,10,utf8_decode("Assinatura do Estagiário"),0,0,"L",false);

$pdf->ln(mm2pt(16));


$pdf->Cell(450,10,utf8_decode("____________________________________________________"),0,0,"L",false);

$pdf->ln(mm2pt(5));

$pdf->Cell(450,10,utf8_decode("Assinatura do Orientador do estágio da Instituição de Ensino "),0,0,"J",false);

$pdf->ln(mm2pt(5));

$pdf->Cell(450,10,utf8_decode("RG: ".$return_advisor["rg_person"]),0,0,"J",false);

$pdf->ln(mm2pt(5));

$pdf->Cell(450,10,utf8_decode("CIC: ".$return_advisor["cic_university_employee"]),0,0,"J",false);

$pdf->ln(mm2pt(16));

$pdf->Cell(450,10,utf8_decode("____________________________________________________"),0,0,"L",false);

$pdf->ln(mm2pt(5));

$pdf->Cell(450,10,utf8_decode("Assinatura do Supervisor do estágio da Instituição de Ensino "),0,0,"J",false);

$pdf->ln(mm2pt(5));

$pdf->Cell(450,10,utf8_decode("RG: ".$return_supervisor["rg_person"]),0,0,"J",false);

$pdf->ln(mm2pt(5));

$pdf->Cell(450,10,utf8_decode("CIC: ".$return_supervisor["cic_company_employee"]),0,0,"J",false);


$pdf->ln(mm2pt(5));


$pdf->Output("RelatorioAcompanhamento.pdf","I");



?>