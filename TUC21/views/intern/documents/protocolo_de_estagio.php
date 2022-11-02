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
$id = $_SESSION['idUser'];
 //pegando o id do coordenador
 $query = "SELECT u.fk_id FROM university_employee u, student s WHERE s.fk_id = $id AND s.fk_university = u.fk_university AND role_university_employee = 'Coordenador'";
        
 $stmt = $conn->prepare($query);

 $stmt->execute();

 $return_coor = $stmt->fetch(PDO::FETCH_ASSOC);

 $id_coordinator = $return_coor['fk_id'];

 //pegando as informações do coordenador
 $query = "SELECT * FROM person p, university_employee u WHERE p.id_person = $id_coordinator AND fk_id = $id_coordinator";

 $stmt = $conn->prepare($query);

 $stmt->execute();

 $return_coor = $stmt->fetch(PDO::FETCH_ASSOC);


 $id = $_SESSION['idUser'];

 $query = "SELECT * FROM person WHERE id_person = $id";

 $stmt = $conn->prepare($query);

 $stmt->execute();

 $return = $stmt->fetch(PDO::FETCH_ASSOC);
// recuperando o ID da empresa
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


$query = "SELECT * FROM student WHERE fk_id = $id_intern";

$stmt = $conn->prepare($query);

$stmt->execute();

$return_student= $stmt->fetch(PDO::FETCH_ASSOC);


$query = "SELECT * FROM person WHERE id_person = $id_intern";

$stmt = $conn->prepare($query);

$stmt->execute();

$return_intern= $stmt->fetch(PDO::FETCH_ASSOC);

$query = "SELECT s.*, p.* FROM student s, person p WHERE p.id_person = s.fk_id AND p.id_person =".$id_intern."";

$stmt = $conn->prepare($query);

$stmt->execute();

$return_intern2 = $stmt->fetch(PDO::FETCH_ASSOC);

$query = "SELECT fk_supervisor, fk_advisor FROM internship_data WHERE fk_student =".$id_intern."";

$stmt = $conn->prepare($query);

$stmt->execute();

$return_internship_data = $stmt->fetch(PDO::FETCH_ASSOC);
$id_advisor = $return_internship_data['fk_advisor'];
$id_supervisor  =  $return_internship_data['fk_supervisor'];

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

$pdf->SetFont('arial','',12); // vai setar a fonte, tamanho, e tipo (se é negrito, italico, sublinhado ou normal)

//$pdf->Image("public/images/logo_ctinter.jpg",mm2pt(30),mm2pt(10),mm2pt(125));

//$pdf->Image("imagens/logoFEB.png",mm2pt(170),mm2pt(10),mm2pt(22));

$pdf->Ln(mm2pt(10));

$pdf->Cell(150,16,utf8_decode("PROTOCOLO  CTI"),1,0,"C",false); $pdf->Cell(50,16,utf8_decode("POR"),1,0,"",false);  //cria um célula (linha) e escreve oq vai ter dentro e define sua largura e comprimento nos númeos dps do "Cell"
$pdf->SetFont('arial','B',8);
$pdf->Cell(20,16,utf8_decode(""),0,0,"C",false);$pdf->Cell(245,16,utf8_decode("DOCUMENTO DESTINADO À ANÁLISE E PARECER DO(A):"),1,0,"C",false);
$pdf->SetFont('arial','',12);

$pdf->ln(mm2pt(5.8));

$pdf->Cell(150,16,utf8_decode("N°"),1,0,"L",false); $pdf->Cell(50,38,utf8_decode(""),1,0,"L",false); $pdf->Cell(20,16,utf8_decode(""),0,0,"C",false);  
$pdf->SetFont('arial','',9);
$pdf->Cell(245,38,utf8_decode("        "),1,0,"L",false); 
$pdf->ln(mm2pt(5.8));


$pdf->Cell(150,21,utf8_decode("DATA        /       /"),1,0,"L",false);
$pdf->Cell(69,16,utf8_decode(""),0,0,"C",false);
$pdf->SetXY(320, 80); $pdf->Cell(1, 5, utf8_decode("[   ] COORD. ESTÁGIO INFORMÁTICA"));
$pdf->SetXY(320, 90); $pdf->Cell(1, 5, utf8_decode("[   ] COORD. ESTÁGIO ELETRÔNICA"));
$pdf->SetXY(320, 100); $pdf->Cell(1, 5, utf8_decode("[   ] COORD. ESTÁGIO MECÂNICA"));

$pdf->ln(mm2pt(8)); // comando para pular linha

$pdf->SetFont('arial','',10);

$pdf->Cell(465.5,30,utf8_decode("  Exmo. Sr. Prof. Coordenador de estágio ".$return_coor['name_person']."  "),1,0,"L",false);

$pdf->ln(mm2pt(14)); 

//$pdf->MultiCell(465.5,30,utf8_decode("Clique ou toque aqui para inserir o texto., aluno(a) do Curso Técnico em Escolher um item. turma Clique ou toque aqui para inserir o texto. Período Escolher um item. matriculado (a) sob nº Clique ou toque aqui para inserir o texto., vem mui respeitosamente solicitar de V.Sa., a apreciação do seu:"),0,0,"J",false);
$pdf->MultiCell(465.5,20,utf8_decode("           ".$return_intern['name_person'].", aluno(a) do Curso Técnico em ".$return['course_internship_data'].", turma ".$return_intern2["course_code_student"].", Período ".$return_intern2["period_student"].", matriculado (a) sob nº ".$return_intern2["ra_student"].", vem muito respeitosamente solicitar de V.Sa., a apreciação do seu:                                                                                                                                                                                                          [X] PLANO DE ESTÁGIO               [   ] RELATÓRIO FINAL DE ESTÁGIO"), 1, "J", false);

$pdf->ln(mm2pt(4)); 

$pdf->MultiCell(465.5,20,utf8_decode("                                                                       ESTOU CIENTE:                                                                   - QUE O ESTÁGIO SÓ TERÁ VALIDADE APÓS O DEFERIMENTO PELA COORDENAÇÃO                 - DOS PRAZOS PARA ENTREGA DO RELATÓRIO FINAL E POSSÍVEIS CORREÇÕES, E QUE O NÃO CUMPRIMENTO DESTES ACARRETARÁ NO INDEFERIMENTO DO ESTÁGIO.
NESTES TERMOS, PEÇO DEFERIMENTO

                                                            ".$return_company['city_company'].", ".$current_day." de ".$mes." de ".$current_year."
                                                
                                                
                                                        ______________________________
                                                           Assinatura do Aluno/Responsável"), 1, "J", false);

 $pdf->ln(mm2pt(4));    
 
$pdf->MultiCell(465.5,20,utf8_decode("                                                     ENDEREÇO PARA CORRESPONDÊNCIA
Rua/Av. : ".$return_student['address_student'].", ".$return_student['number_student']."
Complemento: ".$return_student['complement_student']."                Cidade: ".$return_student['city_student']."
Cep. : ".$return_student['cep_student']."                                        
Tel. Residencial: (     )        -             Tel. Celular: ".$return_intern['telephone_person']."         
Tel. Comercial: (     )        -           Tel. p/ Recado: (     )        -          
E-mail: ".$return_intern['email_person'].""), 1, "J", false);

$pdf->MultiCell(465.5,20,utf8_decode("------------------------------------------------------------------------------------------------------------------------------------------"), 0, "J", false);

$pdf->MultiCell(465.5,20,utf8_decode("Comprovante do(a) aluno(a) interessado(a):
PROTOCOLO  Nº: _________________                   	DATA: ____/____/201___
Nome do(a) Aluno(a):___________________________________ TURMA ______________
Documento: [X] PLANO DE ESTÁGIO                 [   ] RELATÓRIO FINAL DE ESTÁGIO"), 1, "J", false);


$pdf->Output("protocolo_de_estagio.pdf","I");

?>