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

function valor_por_extenso( $v ){
		
        $sin = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plu = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões","quatrilhões");

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");

        $z = 0;
 
        $v = number_format( $v, 2, ".", "." );
        $int = explode( ".", $v );
 
        for ( $i = 0; $i < count( $int ); $i++ ) 
        {
            for ( $ii = mb_strlen( $int[$i] ); $ii < 3; $ii++ ) 
            {
                $int[$i] = "0" . $int[$i];
            }
        }

        $rt = null;
        $fim = count( $int ) - ($int[count( $int ) - 1] > 0 ? 1 : 2);
        for ( $i = 0; $i < count( $int ); $i++ )
        {
            $v = $int[$i];
            $rc = (($v > 100) && ($v < 200)) ? "cento" : $c[$v[0]];
            $rd = ($v[1] < 2) ? "" : $d[$v[1]];
            $ru = ($v > 0) ? (($v[1] == 1) ? $d10[$v[2]] : $u[$v[2]]) : "";
 
            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count( $int ) - 1 - $i;
            $r .= $r ? " " . ($v > 1 ? $plu[$t] : $sin[$t]) : "";
            if ( $v == "000")
                $z++;
            elseif ( $z > 0 )
                $z--;
                
            if ( ($t == 1) && ($z > 0) && ($int[0] > 0) )
                $r .= ( ($z > 1) ? " de " : "") . $plu[$t];
                
            if ( $r )
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($int[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
        }
 
        $rt = mb_substr( $rt, 1 );
 
        return($rt ? trim( $rt ) : "zero");
 
}


function valor( $v )
{
		
    $sin = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");

    $c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
    $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
    $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
    $u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");

    $z = 0;

    $v = number_format( $v, 2, ".", "." );
    $int = explode( ".", $v );

    for ( $i = 0; $i < count( $int ); $i++ ) 
    {
        for ( $ii = mb_strlen( $int[$i] ); $ii < 3; $ii++ ) 
        {
            $int[$i] = "0" . $int[$i];
        }
    }

    $rt = null;
    $fim = count( $int ) - ($int[count( $int ) - 1] > 0 ? 1 : 2);
    for ( $i = 0; $i < count( $int ); $i++ )
    {
        $v = $int[$i];
        $rc = (($v > 100) && ($v < 200)) ? "cento" : $c[$v[0]];
        $rd = ($v[1] < 2) ? "" : $d[$v[1]];
        $ru = ($v > 0) ? (($v[1] == 1) ? $d10[$v[2]] : $u[$v[2]]) : "";

        $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
        $t = count( $int ) - 1 - $i;
      
        if ( $v == "000")
            $z++;
        elseif ( $z > 0 )
            $z--;
            
        if ( ($t == 1) && ($z > 0) && ($int[0] > 0) )
            $r .= ( ($z > 1) ? " de " : "");
            
        if ( $r )
            $rt = $rt . ((($i > 0) && ($i <= $fim) && ($int[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
    }

    $rt = mb_substr( $rt, 1 );

    return($rt ? trim( $rt ) : "zero");

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

$pdf->Cell(450,60, utf8_decode("TERMO DE COMPROMISSO DE ESTÁGIO"),0,0,"C",false);

$pdf->SetFont('arial','',11);

$pdf->ln(mm2pt(20));

$pdf->MultiCell(450,18,utf8_decode(''.$return_company["name_company"].', estabelecida à '.$return_company["address_company"].' na cidade de '.$return_company["city_company"].', Estado de(o) '.$return_company["state_company"].' , CNPJ '.$return_company["cnpj_company"].', doravante denominada EMPRESA, pelo seu representante infra-assinado, e o estudante '.$return_intern["name_person"].' doravante denominado ESTAGIÁRIO, aluno regularmente matriculado sob o nº '.$return_intern["ra_student"].', da '.$return_intern["course_code_student"].' série, da Habilitação Profissional em '.$return["course_internship_data"].' do Colégio Técnico Industrial "Prof. Isaac Portal Roldán" - UNESP - Câmpus de Bauru, que também assina este Termo, na condição de Interveniente, nos termos da Lei no 11.788 de 25 de setembro de 2008 e mediante as cláusulas e condições abaixo:'),0,"J",false);

$pdf->ln(mm2pt(8));

//$pdf->SetFont('arial','B',11);

//$pdf->Cell(450,21,utf8_decode('1ª. - '),0,"J",false);

$pdf->SetFont('arial','',11);

$start_date = date_create($return["start_date_internship_data"]);

$end_date = date_create($return["end_date_internship_data"]);

$entrada = new DateTime($return["start_date_internship_data"]);
$saida = new DateTime($return["end_date_internship_data"]);
$intervalo = $entrada->diff($saida);
$mostra = (($intervalo->y) * 12) + $intervalo->m;

$pdf->MultiCell(450,18,utf8_decode('1ª. - O Estágio terá a duração de '.$mostra.' meses, iniciando-se em '.date_format($start_date,"d/m/Y").' e encerrando-se em '.date_format($end_date,"d/m/Y").'.'),0,"J",false);

$pdf->ln(mm2pt(2));

$pdf->MultiCell(450,18,utf8_decode('2ª.  - É assegurado ao estagiário, conforme consta no Artigo 13 da Lei no 11.788 de 25 de setembro de 2008, sempre que o estágio supervisionado tenha duração igual ou superior a 01 (hum) ano, período de recesso de 30 (trinta) dias a ser gozado, preferencialmente, durante as férias escolares.'),0,"J",false);

$pdf->ln(mm2pt(2));

$pdf->MultiCell(450,18,utf8_decode('3ª. - A EMPRESA, designará o '.$return_supervisor["name_person"].' que exerce o cargo de '.$return_supervisor["function_company_employee"].', para ser o Responsável pelo Estágio na Empresa.'),0,"J",false);

$pdf->ln(mm2pt(2));

$pdf->MultiCell(450,18,utf8_decode('4ª. - A EMPRESA se obriga a fazer Seguro Contra Acidentes Pessoais, a favor do ESTAGIÁRIO, durante a vigência do Estágio, de acordo com o inciso IV do artigo 9o da Lei no 11.788 de 25 de setembro de 2008.'),0,"J",false);

$pdf->ln(mm2pt(2));

$valor1 = $return["week_hours_internship_data"]*5;
$valor2 = $return["scholarship_value_internship_data"]/$valor1;
$valor3 = number_format($valor2, 2, ".", ".");

$pdf->MultiCell(450,18,utf8_decode('5ª. - A título de Bolsa de Complementação Educacional, o ESTAGIÁRIO receberá R$ '.$valor3.' ('.valor_por_extenso($valor3).') por hora, como pagamento mensal, calculado sobre as horas efetivamente estagiadas.'),0,"J",false);

$pdf->ln(mm2pt(2));

$hora = $return["daily_hours"];

$pdf->MultiCell(450,18,utf8_decode('6ª. - O ESTAGIÁRIO deve cumprir jornada de '.$hora.' ('.valor($hora).') horas diárias, compatível obrigatoriamente com o seu horário escolar, não podendo exceder os limites estipulados pela lei federal de estágio nº 11.788 de 25 de setembro de 2008, ou seja, 6 (seis) horas diárias e 30 (trinta) horas semanais;'),0,"J",false);

$pdf->ln(mm2pt(2));

$pdf->MultiCell(450,18,utf8_decode('7ª. - O ESTAGIÁRIO se obriga a cumprir, fielmente, a programação do Estágio, comunicando, em tempo hábil, a impossibilidade de fazê-lo, bem como justificar ao seu Supervisor, eventuais ausências.'),0,"J",false);

$pdf->ln(mm2pt(2));

$pdf->MultiCell(450,18,utf8_decode('8ª. - O ESTAGIÁRIO, compromete-se, a respeitar e cumprir toda e qualquer norma ou determinação formal de caráter interno da EMPRESA, existente ou que venha a existir.'),0,"J",false);

$pdf->ln(mm2pt(2));

$pdf->MultiCell(450,18,utf8_decode('9ª. - O ESTAGIÁRIO, responderá pelas perdas e danos consequentes da inobservância das normas e de terminações internas, ou das constantes do presente Termo.'),0,"J",false);

$pdf->ln(mm2pt(2));

$pdf->MultiCell(450,18,utf8_decode('10ª. - O ESTAGIÁRIO se obriga a elaborar e entregar relatórios sobre o seu Estágio, na forma e padrões estabelecidos no regulamento da EMPRESA e/ou ESCOLA.'),0,"J",false);

$pdf->ln(mm2pt(2));

$pdf->MultiCell(450,18,utf8_decode('11ª. - O presente Estágio, não acarretará vínculo empregatício de qualquer natureza, entre a EMPRESA e o ESTAGIÁRIO, nos termos do que dispõe o artigo 3o da Lei no 11.788 de 25 de setembro de 2008.'),0,"J",false);

$pdf->ln(mm2pt(2));

$pdf->MultiCell(450,18,utf8_decode('12ª. - Este Termo de Compromisso de Estágio, poderá ser denunciado a qualquer tempo, unilateralmente, mediante comunicação escrita, com antecedência de 05 (cinco) dias, endereçadas aos demais signatários deste Termo.'),0,"J",false);

$pdf->ln(mm2pt(2));

$pdf->MultiCell(450,18,utf8_decode('13ª. - No caso de o ESTAGIÁRIO desistir ou cancelar matrícula, o presente Termo ficará automaticamente rescindido.'),0,"J",false);

$pdf->ln(mm2pt(2));

$pdf->MultiCell(450,18,utf8_decode('        E, por estarem de inteiro e comum acordo com as cláusulas e condições deste Termo, as partes assinam em 03 (três) vias de igual teor, com as testemunhas abaixo, cabendo a 1ª via à EMPRESA, a 2ª ao ESTAGIÁRIO, e a 3ª à ESCOLA.'),0,"J",false);

$pdf->ln(mm2pt(18));

$pdf->Cell(450,20,utf8_decode("".$return_company["city_company"].", ".$current_day." de ".$mes." de ".$current_year.""),0,0,"R",false);

$pdf->ln(mm2pt(35));

$pdf->MultiCell(450,20,utf8_decode("______________________      ______________________      ______________________"),0,"C",false);

$pdf->SetFont('arial','',8);

$pdf->MultiCell(450,20,utf8_decode("   ".$return_company["legal_representative_company"]."                            ".$return_intern["name_person"]."                                           ".$return_advisor["name_person"].""),0,"J",false);

$pdf->Output("termo_de_compromisso.pdf","I");

?>