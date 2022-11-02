<?php

session_start();

require_once("../../../app/php/fpdf/fpdf.php");
require_once('../../../app/db/connect.php');
require_once('../../../app/php/functions.php');

if(isset($_GET["id"]))
{
    $idPlan = decodeId($_GET["id"]);
}

$pdf= new FPDF("P","pt","A4");


function pt2mm($pt){

    return($pt/2.83465);

}

function mm2pt($mm){

    return($mm*2.83465);

}

//recuperando o ID da empresa
$query = "SELECT i.*, ip.* FROM internship_data i, internship_plan ip WHERE ip.id_internship_plan = ".$idPlan." AND ip.fk_internship_data = i.id_internship_data";

$stmt = $conn->prepare($query);

$stmt->execute();

$return = $stmt->fetch(PDO::FETCH_ASSOC);

$id_supervisor = $return["fk_supervisor"];
$id_intern = $return["fk_student"];
$id_company = $return["fk_company"];
$id_advisor = $return["fk_advisor"];
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

//pegando as informações do orientador
$query = "SELECT u.*, p.*, a.* FROM university_employee u, person p, advisor a WHERE p.id_person = u.fk_id AND p.id_person = a.fk_id AND p.id_person =".$id_advisor."";

$stmt = $conn->prepare($query);

$stmt->execute();

$return_advisor = $stmt->fetch(PDO::FETCH_ASSOC);

//pegando as informações do estágio
$query = "SELECT * FROM internship_data WHERE fk_student = ".$id_intern;

$stmt = $conn->prepare($query);

$stmt->execute();

$return_internship = $stmt->fetch(PDO::FETCH_ASSOC);

$id_intern = $return_internship['fk_student'];
$id_internship = $return_internship['id_internship_data'];

//pegando as informações do supervisor
$query = "SELECT c.*, p.* FROM company_employee c, person p WHERE p.id_person = c.fk_id AND c.fk_id = ".$id_supervisor;

$stmt = $conn->prepare($query);

$stmt->execute();

$return_supervisor = $stmt->fetch(PDO::FETCH_ASSOC);

//pegando as informações do plano de estágio
$query = "SELECT * FROM internship_plan WHERE fk_internship_data = ".$id_internship;

$stmt = $conn->prepare($query);

$stmt->execute();

$return_plan = $stmt->fetch(PDO::FETCH_ASSOC);

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

$pdf->SetMargins(mm2pt(30),mm2pt(20),mm2pt(20));


$pdf->Ln(mm2pt(10));

$pdf->SetFont('arial','',15);

$pdf->Cell(450,100,utf8_decode("PLANO DE ESTÁGIO"), 1, 0, "C",false);
$pdf->SetFont('arial','B',12);
$pdf->SetXY(240, 115); $pdf->Cell(150,20,utf8_decode($return_intern['ra_student']), 0, 0,"C",false);
$pdf->SetXY(440, 57); $pdf->Cell(1,100,utf8_decode(""), 1, 0,"C",false);
$pdf->SetFont('arial','',10);
$pdf->SetXY(486, 142); $pdf->Cell(1,10,utf8_decode("Cole sua foto aqui"), 0, 0,"C",false);
$pdf->Ln(mm2pt(10));

$pdf->SetFont('arial','B',13);

$pdf->Cell(50,10,utf8_decode("ALUNO"), 0, 0, "C",false);

$pdf->ln(mm2pt(2));

$pdf->SetFont('arial','B',11);

//tabela informações estagiário


$pdf->SetFont('arial','',8);
$pdf->ln(mm2pt(5));
$pdf->SetFillColor(225,225,225);$pdf->Cell(300,15,utf8_decode("NOME"), 0, 0,"L",true);
$pdf->SetFillColor(225,225,225);$pdf->Cell(150,15,utf8_decode("DATA DE NASCIMENTO"), 0, 0,"L",true);
$pdf->Ln(mm2pt(7));
$pdf->Cell(300,12,utf8_decode($return_intern['name_person']), 0, 0,"L",false);
$pdf->Cell(150,12,utf8_decode($return_intern['birthday_person']), 0, 0,"L",false);
$pdf->Ln(mm2pt(6));
$pdf->SetFillColor(225,225,225);$pdf->Cell(300,15,utf8_decode("ENDEREÇO"), 0, 0,"L",true);
$pdf->SetFillColor(225,225,225);$pdf->Cell(150,15,utf8_decode("TELEFONE"), 0, 0,"L",true);
$pdf->Ln(mm2pt(7)); 
$pdf->Cell(300,12,utf8_decode($return_intern['address_student']), 0, 0,"L",false);
$pdf->Cell(150,12,utf8_decode($return_intern['telephone_person']), 0, 0,"L",false);
$pdf->Ln(mm2pt(6));
$pdf->SetFillColor(225,225,225);$pdf->Cell(100,15,utf8_decode("CEP"), 0, 0,"L",true);
$pdf->SetFillColor(225,225,225);$pdf->Cell(150,15,utf8_decode("BAIRRO"), 0, 0,"L",true);
$pdf->SetFillColor(225,225,225);$pdf->Cell(200,15,utf8_decode("CIDADE/ESTADO"), 0, 0,"L",true);
$pdf->Ln(mm2pt(7)); 
$pdf->Cell(100,12,utf8_decode($return_intern['cep_student']), 0, 0,"L",false);
$pdf->Cell(150,12,utf8_decode($return_intern['district_student']), 0, 0,"L",false);
$pdf->Cell(200,12,utf8_decode($return_intern['city_student']), 0, 0,"L",false);
$pdf->Ln(mm2pt(6));
$pdf->SetFillColor(225,225,225);$pdf->Cell(300,15,utf8_decode("HABILITAÇÃO"), 0, 0,"L",true);
$pdf->SetFillColor(225,225,225);$pdf->Cell(150,15,utf8_decode("TURMA/ANO"), 0, 0,"L",true);
$pdf->Ln(mm2pt(7)); 
$pdf->Cell(300,12,utf8_decode($return_intern['business_sector_student']), 0, 0,"L",false);
$pdf->Cell(150,12,utf8_decode($return_intern['course_code_student']), 0, 0,"L",false);

$pdf->SetXY(85, 190);$pdf->Cell(450,145,utf8_decode(""), 1, 0,"L",false);
$pdf->Ln(mm2pt(56));


$pdf->SetFont('arial','B',13);

$pdf->Cell(50,10,utf8_decode("     EMPRESA"), 0, 0, "C",false);

$pdf->ln(mm2pt(2));

$pdf->SetFont('arial','B',11);

//tabela informações estagiário


$pdf->SetFont('arial','',8);
$pdf->ln(mm2pt(5));
$pdf->SetFillColor(225,225,225);$pdf->Cell(300,15,utf8_decode("NOME"), 0, 0,"L",true);
$pdf->SetFillColor(225,225,225);$pdf->Cell(150,15,utf8_decode("CPF/CNPJ"), 0, 0,"L",true);
$pdf->Ln(mm2pt(7));
$pdf->Cell(300,12,utf8_decode($return_company['name_company']), 0, 0,"L",false);
$pdf->Cell(150,12,utf8_decode($return_company['cnpj_company']), 0, 0,"L",false);
$pdf->Ln(mm2pt(6));
$pdf->SetFillColor(225,225,225);$pdf->Cell(300,15,utf8_decode("ENDEREÇO"), 0, 0,"L",true);
$pdf->SetFillColor(225,225,225);$pdf->Cell(150,15,utf8_decode("TELEFONE"), 0, 0,"L",true);
$pdf->Ln(mm2pt(7)); 
$pdf->Cell(300,12,utf8_decode($return_company['address_company']), 0, 0,"L",false);
$pdf->Cell(150,12,utf8_decode($return_company['telephone_company']), 0, 0,"L",false);
$pdf->Ln(mm2pt(6));
$pdf->SetFillColor(225,225,225);$pdf->Cell(100,15,utf8_decode("CEP"), 0, 0,"L",true);
$pdf->SetFillColor(225,225,225);$pdf->Cell(150,15,utf8_decode("BAIRRO"), 0, 0,"L",true);
$pdf->SetFillColor(225,225,225);$pdf->Cell(200,15,utf8_decode("CIDADE/ESTADO"), 0, 0,"L",true);
$pdf->Ln(mm2pt(7));
$pdf->Cell(100,12,utf8_decode($return_company['cep_company']), 0, 0,"L",false);
$pdf->Cell(150,12,utf8_decode($return_company['district_company']), 0, 0,"L",false);
$pdf->Cell(200,12,utf8_decode($return_company['city_company']), 0, 0,"L",false);
$pdf->Ln(mm2pt(6));
$pdf->SetFillColor(225,225,225);$pdf->Cell(450,15,utf8_decode("SETOR"), 0, 0,"L",true);
$pdf->Ln(mm2pt(6)); 
$pdf->Cell(300,12,utf8_decode($return_company['section_company']), 0, 0,"L",false);
$pdf->Ln(mm2pt(6));
$pdf->SetFillColor(225,225,225);$pdf->Cell(300,15,utf8_decode("RESPONSÁVEL PELO ESTÁGIO NA EMPRESA(NOME)"), 0, 0,"L",true);
$pdf->SetFillColor(225,225,225);$pdf->Cell(150,15,utf8_decode("E-MAIL DO RESPONSÁVEL"), 0, 0,"L",true);
$pdf->Ln(mm2pt(6)); 
$pdf->Cell(300,12,utf8_decode($return_supervisor['name_person']), 0, 0,"L",false);
$pdf->Cell(150,12,utf8_decode($return_supervisor['email_person']), 0, 0,"L",false);

$pdf->SetXY(85, 369);$pdf->Cell(450,176,utf8_decode(""), 1, 0,"L",false);

$pdf->Ln(mm2pt(66));
$pdf->SetFont('arial','B',13);

$pdf->Cell(50,10,utf8_decode("     ESTÁGIO"), 0, 0, "C",false);


$pdf->SetFont('arial','',8);
$pdf->ln(mm2pt(5));
$pdf->SetFillColor(225,225,225);$pdf->Cell(75,15,utf8_decode("INÍCIO"), 0, 0,"L",true);
$pdf->SetFillColor(225,225,225);$pdf->Cell(200,15,utf8_decode("TÉRMINO"), 0, 0,"L",true);
$pdf->SetFillColor(225,225,225);$pdf->Cell(175,15,utf8_decode("ATIVIDADES PROPOSTAS"), 0, 0,"L",true);
$pdf->Ln(mm2pt(6));
$pdf->Cell(75,12,utf8_decode($return_internship['start_date_internship_data']), 0, 0,"L",false);
$pdf->Cell(150,12,utf8_decode($return_internship['end_date_internship_data']), 0, 0,"L",false);
$pdf->Ln(mm2pt(6));
$pdf->SetFillColor(225,225,225);$pdf->Cell(225,15,utf8_decode("HORÁRIO DE ENTRADA E SAÍDA"), 0, 0,"C",true);
$pdf->Ln(mm2pt(6));
$pdf->Cell(75,12,utf8_decode("SEGUNDA: ".$return_intern['monday']." a ".$return_intern['end_monday']), 0, 0,"L",false);
$pdf->Ln(mm2pt(5));
$pdf->Cell(75,12,utf8_decode("TERÇA:       ".$return_intern['tuesday']." a ".$return_intern['end_tuesday']), 0, 0,"L",false);
$pdf->Ln(mm2pt(5));
$pdf->Cell(75,12,utf8_decode("QUARTA:    ".$return_intern['wednesday']." a ".$return_intern['end_wednesday']), 0, 0,"L",false);
$pdf->Ln(mm2pt(5));
$pdf->Cell(75,12,utf8_decode("QUINTA:     ".$return_intern['thursday']." a ".$return_intern['end_thursday']), 0, 0,"L",false);
$pdf->Ln(mm2pt(5)); 
$pdf->Cell(75,12,utf8_decode("SEXTA:       ".$return_intern['friday']." a ".$return_intern['end_friday']), 0, 0,"L",false);
$pdf->Ln(mm2pt(5));
$pdf->Cell(75,12,utf8_decode("SÁBADO:    ".$return_intern['saturday']." a ".$return_intern['end_saturday']), 0, 0,"L",false);
$pdf->SetXY(85, 570);$pdf->Cell(450,136,utf8_decode(""), 1, 0,"L",false);
$pdf->SetXY(310, 570); $pdf->Cell(1,135.4,utf8_decode(""), 1, 0,"C",false);
$pdf->Ln(mm2pt(48));
$pdf->Cell(450,15,utf8_decode("                                                                                                                                                             ".$current_day." de ".$mes." de ".$current_year),0,"R",false);
$pdf->Ln(mm2pt(7));
$pdf->SetFillColor(255,255,255);$pdf->SetXY(325, 590);$pdf->MultiCell(200,10,utf8_decode($return_internship['description_internship_data']), 0, 0,"C",false);
$pdf->SetXY(85, 720);$pdf->Cell(120,65,utf8_decode(""), 1, 0,"L",false);
$pdf->SetXY(215, 720);$pdf->Cell(320,65,utf8_decode(""), 1, 0,"L",false);
$pdf->SetXY(85, 775);$pdf->Cell(120,10,utf8_decode("CARIMBO DA EMPRESA"), 0, 0,"C",false);
$pdf->SetXY(210, 760);$pdf->Cell(320,10,utf8_decode("__________________________________       _____________________________"), 0, 0,"C",false);
$pdf->SetXY(180, 770);$pdf->Cell(320,10,utf8_decode("                Assinatura do Responsável pelo Estágio                       Assinatura do Aluno"), 0, 0,"C",false);









$pdf->Output("PropostadePlanodeEstagio_".$return_intern['name_person'].".odt","I");
//$pdf->Output("PropostadePlanodeEstagio_".$return_intern['name_person'].".pdf","I");


?>