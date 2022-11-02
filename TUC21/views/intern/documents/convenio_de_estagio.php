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

$pdf->SetFont('arial','B',12);

//$pdf->Image("imagens/unesp.png",mm2pt(30),mm2pt(10),mm2pt(125));

//$pdf->Image("imagens/logoFEB.png",mm2pt(170),mm2pt(10),mm2pt(22));

$pdf->Ln(mm2pt(15));

$pdf->Cell(450,60, utf8_decode("CONVÊNIO PARA REALIZAÇÃO DE ESTÁGIO"),0,0,"C",false);

$pdf->ln(mm2pt(8));

$pdf->SetFont('arial','',10);

$pdf->ln(mm2pt(10));

$pdf->MultiCell(450,15,utf8_decode('UNIVERSIDADE ESTADUAL PAULISTA "JÚLIO DE MESQUITA FILHO" - UNESP, autarquia estadual de regime especial, criada pela Lei nº 952/76, CNPJ nº 48.031.918/0001-24, com sede a Rua Quirino de Andrade, 215 Centro, CEP 01049-905, São Paulo, Capital, através do COLÉGIO TÉCNICO INDUSTRIAL "PROF. ISAAC PORTAL ROLDÁN" do  Câmpus de Bauru/SP, sito à Av. Nações Unidas, 58-50, CEP 17033-260, neste ato representada, de acordo com o que dispõe a Portaria UNESP nº 520/2006, de 10/11/2006, por seu Diretor, Prof. Dr. Marcelo Rodrigues da Silva Pelissari, doravante denominada CONVENENTE, e de outro lado, doravante denominada simplesmente CONVENIADA, abaixo qualificada, através de seu representante legal,'),0,"J",false);

$pdf->MultiCell(450,15,utf8_decode("Razão Social: ".$return_company["corporate_name_company"]."
Representante Legal: ".$return_company["legal_representative_company"]."
Cargo/Função: ".$return_company["function_company"]."
Ramo de Atividade: ".$return_company["activity_branch_company"]."
CNPJ: ".$return_company["cnpj_company"]."
Insc. Estadual nº: ".$return_company["state_registration_company"]."
Endereço: ".$return_company["address_company"]."
Bairro: ".$return_company["district_company"]."
CEP: ".$return_company["cep_company"]."
Cx.Postal: ".$return_company["mailbox_company"]."
Cidade: ".$return_company["city_company"]."
Estado: ".$return_company["state_company"]."
Telefone: ".$return_company["telephone_company"]."
Telefone 2: ".$return_company["telephone2_company"]."
E-mail: ".$return_company["email_company"]."
"),1, "J", false);

$pdf->Cell(450,20,utf8_decode("tem justo e acordado o presente convênio, que regerá pelas cláusulas e condições a seguir"),0,0,"J",false);

$pdf->ln(mm2pt(10)); 

$pdf->MultiCell(450,15,utf8_decode("CLÁUSULA 1ª - Através deste instrumento fica facultado à CONVENIADA a receber alunos(as) das Habilitações Profissionais ministradas pela CONVENENTE, de acordo com as disposições previstas na Lei nº 11788, de 25 de setembro de 2008."),0,"J",false);

$pdf->ln(mm2pt(3)); 

$pdf->MultiCell(450,15,utf8_decode("CLÁUSULA 2ª - O Estágio deve propiciar a complementação do ensino e da aprendizagem a ser planejado, executado, acompanhado e avaliado em conformidade com os currículos, programas e calendários escolares da CONVENENTE, a fim de se constituir em instrumento de integração, em termos de treinamento prático, de aperfeiçoamento técnico-cultural, científico e de relacionamento humano;"),0,"J",false);

$pdf->ln(mm2pt(3)); 

$pdf->MultiCell(450,15,utf8_decode("CLÁUSULA 3ª - O(a) estudante a ser admitido(a) para Estágio, selecionado(a) conforme normas internas da CONVENIADA, apresentará no início de cada semestre letivo, documento comprovando a série/ano em que está matriculado(a), a modalidade da Habilitação e outros dados de interesse dos partícipes;"),0,"J",false);

$pdf->ln(mm2pt(3)); 

$pdf->MultiCell(450,15,utf8_decode("CLÁUSULA 4ª - As condições para a realização de Estágio serão sempre pré-estabelecidas, individualmente, no TERMO DE COMPROMISSO DE ESTÁGIO a ser firmado à parte;"),0,"J",false);

$pdf->ln(mm2pt(3)); 

$pdf->MultiCell(450,15,utf8_decode("CLÁUSULA 5ª - O(a) estudante-estagiário(a) assinará, em conjunto com a CONVENIADA e com a interveniência obrigatória da CONVENENTE, o Termo de Compromisso de Estágio, SEM VÍNCULO EMPREGATÍCIO;"),0,"J",false);

$pdf->ln(mm2pt(3)); 

$pdf->MultiCell(450,15,utf8_decode("CLÁUSULA 6ª - Fica facultado à CONVENIADA pactuar no TERMO DE COMPROMISSO DE ESTÁGIO o pagamento de uma Bolsa de Complementação Educacional, mensal, diretamente ao(à) estagiário(a), com base no total de horas estagiadas durante o mês;"),0,"J",false);

$pdf->ln(mm2pt(3)); 

$pdf->MultiCell(450,15,utf8_decode("CLÁUSULA 7ª - A CONVENIADA se responsabilizará pelo SEGURO CONTRA ACIDENTES PESSOAIS, a favor do(a) Estagiário(a), de acordo com o inciso IV do Artigo 9º da Lei nº 11.788 de 25 de setembro de 2008."),0,"J",false);

$pdf->ln(mm2pt(3)); 

$pdf->MultiCell(450,15,utf8_decode("CLÁUSULA 8ª - O estudante-estagiário obriga-se a elaborar, à CONVENIADA, um relatório pormenorizado sobre o Estágio realizado, quando solicitado e, é permitido à CONVENENTE, supervisionar as atividades do estudante durante a realização do Estágio;"),0,"J",false);

$pdf->ln(mm2pt(3)); 

$pdf->MultiCell(450,15,utf8_decode("CLÁUSULA 9ª - O tempo de duração do Estágio ficará a critério da CONVENIADA, podendo tanto o estudante-estagiário como a CONVENIADA, desistir do mesmo, a qualquer tempo, desde que haja comunicação por escrito, feito com, no mínimo 05(cinco) dias de antecedência. O Estágio sob a interveniência da CONVENENTE extinguir-se-á de pleno direito, se o estudante-estagiário desistir, cancelar, graduar-se ou perder o vínculo com a CONVENENTE por qualquer outra forma;"),0,"J",false);

$pdf->ln(mm2pt(3)); 

$pdf->MultiCell(450,15,utf8_decode("CLÁUSULA 10ª - O presente Convênio vigorará por um período de 5 (cinco) anos, contados a partir da data de sua assinatura, podendo ser denunciado pelas partes, a qualquer tempo, mediante comunicação por escrito, com antecedência mínima de 30 (trinta) dias. O presente convênio implica no cancelamento de qualquer outro convênio anterior."),0,"J",false);

$pdf->ln(mm2pt(3)); 

$pdf->MultiCell(450,15,utf8_decode("CLÁUSULA 11ª - Fica eleito o Foro da Comarca de Bauru, Estado de São Paulo, com renúncia de qualquer outro, por mais privilegiado que seja, para dirimir questões decorrentes da execução desse convênio, que não possa ser resolvido amigavelmente;"),0,"J",false);

$pdf->ln(mm2pt(3)); 

$pdf->MultiCell(450,15,utf8_decode("E por estarem de acordo, assinam o presente em duas vias, de igual teor, na presença das testemunhas abaixo, para que o mesmo produza seus efeitos legais."),0,"J",false);

$pdf->ln(mm2pt(10)); 

$pdf->MultiCell(450,15,utf8_decode($return_company['city_company'].", ".$current_day." de ".$mes." de ".$current_year."."),0,"R",false);

$pdf->ln(mm2pt(30)); 

$pdf->MultiCell(450,15,utf8_decode("   _______________________________                               _______________________________"),0,"L",false);

$pdf->ln(mm2pt(2));

$pdf->MultiCell(450,15,utf8_decode("                     CONVENENTE                                                                     CONVENIADA"),0,"J",false);



$pdf->MultiCell(450,15,utf8_decode("Prof Dr. Marcelo Rodrigues da Silva Pelissari		                         ".$return_company["legal_representative_company"].""),0,"J",false);

$pdf->MultiCell(450,15,utf8_decode('              Diretor do CTI - Unesp/Bauru'),0,"J",false);

$pdf->Output("convenio_de_estagio.pdf","I");

?>