<?php

session_start();

require_once("../../../app/php/fpdf/fpdf.php");
require_once('../../../app/db/connect.php');
require_once('../../../app/php/functions.php');

if(isset($_POST['reason']) && isset($_POST['internship']) && isset($_POST['current_year']) && isset($_POST['current_semester']))
{
    $internship = cleanString($_POST['internship']);
    $change_reason = cleanString($_POST['reason']);
    $current_school_year = cleanString($_POST['current_year']);
    $current_semester = cleanString($_POST['current_semester']);
}
{
    $_SESSION['feedback'] = "errorModalInfo";
    $_SESSION['btn'] = 1;
    header('Location: ../internPage.php');
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
$query = "SELECT u.*, p.* FROM university_employee u, person p WHERE p.id_person = u.fk_id AND p.id_person =".$id_advisor."";

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

$pdf->Ln(mm2pt(20));

$pdf->Cell(450,60,utf8_decode("À DTA-ÁREA DE ESTÁGIOS DA FACULDADE DE ENGENHARIA"),0,0,"L",false);

$pdf->ln(mm2pt(30));

$pdf->SetFont('arial','',12);

$pdf->MultiCell(450,20,utf8_decode("       Eu ".$return_intern["name_person"].", RA nº ".$return_intern["ra_student"].", aluno do curso de Engenharia ".$return["course_internship_data"].", solicito encaminhamento deste documento à COMISSÃO DE ESTÁGIO do curso de Engenharia ".$return["course_internship_data"]." para decisão sobre o que segue."), 0, "J", false);

$pdf->ln(mm2pt(5));

//data de início
$start_date = date_create($return['start_date_internship_data']);

$pdf->MultiCell(450,20, utf8_decode("          Atualmente sou funcionário da empresa ".$return_company["name_company"].", desde ".date_format($start_date, "d/m/Y").", e venho através desta, solicitar a convalidação da experiência profissional para a disciplina estágio, pelo motivo de ".$change_reason.". Anexo segue comprovante do meu vínculo empregatício com a Empresa e ".$return["description_internship_data"]."."), 0, "J", false);

$pdf->ln(mm2pt(5));

$pdf->MultiCell(450,20, utf8_decode("          Informo que já possuo mais de 60% de integralização de meu curso e estou regularmente matriculado (a) na disciplina em questão."), 0, "J", false);

$pdf->ln(mm2pt(5));

$pdf->MultiCell(450,20, utf8_decode("           Solicito que a nota do estágio ".$internship." seja atribuída para o ".$current_semester."/".$current_school_year."."), 0, "J", false);

$pdf->ln(mm2pt(5));

$pdf->MultiCell(450,20, utf8_decode("          Estou ciente que deverei entrar em contato com a Seção Técnica de Gradu- ação - STG, no prazo máximo de 10 dias a partir da data do protocolo desta solicitação, a fim de tomar ciência da manifestação da Comissão de Estágio e regularizar a situação. "), 0, "J", false);

$pdf->ln(mm2pt(5));

$pdf->MultiCell(450,20,utf8_decode( "           Atenciosamente, ".$return_intern["name_person"].""), 0, "J", false);

$pdf->ln(mm2pt(5));

$pdf->Cell(450,20,utf8_decode("           Bauru, ".$current_day." de ".$mes." de ".$current_year.". "),0,0,"L",false);

$pdf->ln(mm2pt(30));

$pdf->Cell(0,15,"______________________________", 0, 0, "L", false);

//$pdf->Cell(0,15,"______________________________ ", 0, 0, "R", false);

$pdf->ln(mm2pt(6));

$pdf->SetFont('arial','',10);

$pdf->Cell(0,15,utf8_decode($return_intern["name_person"]), 0, 0, "L", false);

//$pdf->Cell(0,15,utf8_decode("Ciente e de acordo                                          "), 0, 0, "R", false);

$pdf->ln(mm2pt(6));

$pdf->Cell(0,15,utf8_decode($return_intern["rg_person"]), 0, 0, "L", false);

//$pdf->Cell(0,15,utf8_decode("Comissão de Estágio                                      "), 0, 0, "R", false);

$pdf->ln(mm2pt(6));

$pdf->Cell(0,15,utf8_decode($return_intern["email_person"].""), 0, 0, "L", false);

$pdf->ln(mm2pt(6));

$pdf->Cell(0,15,utf8_decode($return_intern["telephone_person"].""), 0, 0, "L", false);

$pdf->ln(mm2pt(6));

$pdf->Cell(0,15,utf8_decode($return_company["telephone_company"].""), 0, 0, "L", false);



$pdf->Output("Solicitacao_Convalidacao_Estagio.pdf","I");



?>