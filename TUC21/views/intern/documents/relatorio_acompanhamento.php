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

$query = "SELECT s.*, p.* FROM student s, person p WHERE p.id_person = s.fk_id AND p.id_person =".$id_intern."";

$stmt = $conn->prepare($query);

$stmt->execute();

$return_intern = $stmt->fetch(PDO::FETCH_ASSOC);

if($return["nature_internship_data"] == "True")
{
    $nature = "(  ) Não Obrigatório     (X) Obrigatório";
}
else
{
    $nature = "(X) Não Obrigatório     (  ) Obrigatório";
}

$query = "SELECT name_person FROM person WHERE id_person = ".$return["fk_advisor"]."";

$stmt = $conn->prepare($query);

$stmt->execute();

$return_advisor = $stmt->fetch(PDO::FETCH_ASSOC);

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

$pdf->SetFont('arial','B',12);

//$pdf->Image("imagens/unesp.png",mm2pt(30),mm2pt(10),mm2pt(125));

//$pdf->Image("imagens/logoFEB.png",mm2pt(170),mm2pt(10),mm2pt(22));

$pdf->Ln(mm2pt(20));

$pdf->Cell(450,60, utf8_decode("RELATÓRIO LEGAL PARA ACOMPANHAMENTO DE ESTÁGIO"),0,0,"C",false);

$pdf->ln(mm2pt(20));

$pdf->SetFont('arial','',11
);

$pdf->Cell(450,20,utf8_decode("Nome do Estagiário(a): ".$return_intern['name_person']."                                  RA: ".$return_intern['ra_student'].""),0,0,"J",false);

$pdf->ln(mm2pt(10));

$pdf->Cell(450,20,utf8_decode("Natureza do Estágio:  ".$nature.""),0,0,"J",false);

$pdf->ln(mm2pt(10));

$start_date = date_create($return['start_date_internship_data']);
$end_date = date_create($return['end_date_internship_data']);

$pdf->Cell(450,20,utf8_decode("Início do Estágio: ".date_format($start_date,"d/m/Y")."                Término do Estágio: ".date_format($end_date,"d/m/Y").""),0,0,"J",false);

$pdf->ln(mm2pt(10));

$pdf->Cell(450,20,utf8_decode("Nº de Horas Efetivamente Trabalhadas: "),0,0,"J",false); //colocar o $_POST["horas_trabalhadas"]
//                              o problema de calcular é do cara, não nosso

$pdf->ln(mm2pt(10));

$pdf->Cell(450,20,utf8_decode("Nome da Empresa: ".$return_company['name_company'].""),0,0,"J",false);

$pdf->ln(mm2pt(10));

$pdf->Cell(450,20,utf8_decode("Setor de Atuação do Estagiário(a): ".$return['area_internship_data'].""),0,0,"J",false);

$pdf->ln(mm2pt(10));

$pdf->Cell(450,20,utf8_decode("Supervisor(a) de estágio na Empresa: ".$return_company['name_company'].""),0,0,"J",false);

$pdf->ln(mm2pt(10));

$pdf->Cell(450,20,utf8_decode("Orientador(a) de estágio na UNESP: ".$return_advisor['name_person'].""),0,0,"J",false);

$pdf->ln(mm2pt(20));

$pdf->Cell(450,20,utf8_decode("1 - Relato das Atividades Realizadas no Estágio no Período de Análise:"),0,0,"J",false);

$pdf->ln(mm2pt(15));

$pdf->MultiCell(450,200,utf8_decode(""), 1, "J", false);//colocar o $_POST["descricao"]

$pdf->ln(mm2pt(5));

$pdf->Cell(450,20,"Bauru, ".$current_day." de ".$mes." de ".$current_year.". ",0,0,"L",false);

$pdf->ln(mm2pt(30));

$pdf->Cell(450,0,"____________________", 0,1,"L", false);
$pdf->Cell(450,0,"____________________", 0,1,"C", false);
$pdf->Cell(450,0,"____________________", 0,1,"R", false);

$pdf->ln(mm2pt(5));

$pdf->Cell(450,0,utf8_decode("Nome do supervisor(a)"), 0,1,"L", false);
$pdf->Cell(450,0,utf8_decode($return_advisor['name_person']),0,1,"C", false);
$pdf->Cell(450,0,utf8_decode($return_intern['name_person']), 0,1,"R", false);


$pdf->Output("RelatorioAcompanhamento.pdf","I");



?>