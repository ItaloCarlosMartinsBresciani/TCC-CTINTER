<?php

use Google\Service\Script;

session_start();

    require_once('../app/php/functions.php');
    require_once('../app/db/connect.php');
    
    if (isset($_GET['type']) && isset($_GET['id'])) { //o tipo de usuário que quer ver informação e seu id
        $type = cleanString($_GET['type']);
        $id = decodeId($_GET['id']);
        
        try {
          $idHex = cleanString($_GET['id']);

          $idDec = decodeId($idHex);
        }
        catch (TypeError) {
          header('Location: supervisorPage.php');
        }   
      } else {
        header('Location: supervisorPage.php');
      }

    $query = "SELECT fk_student FROM internship_reports r, internship_data i WHERE id_internship_reports = ".$id." AND id_internship_data = fk_internship_data";
    
    $stmt = $conn->prepare($query);

    $stmt->execute();

    $return = $stmt->fetch(PDO::FETCH_ASSOC); 
    
    $id_intern = $return["fk_student"];
    
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualização - Relatório Final</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../public/bootstrap/bootstrap.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../public/css/style.css">

    <!-- JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js%22%3E"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js" 
        integrity="sha256-yE5LLp5HSQ/z+hJeCqkz9hdjNkk1jaiGG0tDCraumnA=" 
        crossorigin="anonymous"
    ></script>
    <script type="text/javascript">

        function sub01(){
            $('[name=report-name]').removeAttr('required');  
            $('[name=file-report]').removeAttr('disabled');
            
            $('#f2').append('<input type="hidden" name="sub0"/>');
            $('#f2').submit();            
        };


        function submitForm(){
            $('[name=role_internship_data]').removeAttr('disabled');
            
            $('[name=role_internship_data]').attr('required');
            
            $('form').append('<input type="hidden" name="sub5" />');
            $('form').submit();
        };
        
    </script>
</head>
<body>

    <div class="container min-vh-100">
        <header class="row bg-white">
            <div class="col-8 d-flex align-items-center">
                <img class="w-100 unesp" src="../public/images/logo_cti.png" alt="Logo UNESP">
            </div>
            
            <div class="col-4 pt-3 pb-3 d-flex justify-content-end">
                <img class="w-100 feb" src="../public/images/logo_ctinter.jpg" alt="Logo FEB">
            </div>
        </header>

        <?php

            if(isset($_SESSION['done'])){
                if($_SESSION['done']){ //se a sessão estiver setada com 1
                    $_POST['sub0'] = 1;
                    $_SESSION['done'] = 0;
                }
            }

            if($_SESSION["section"] == 1)
            {
                if(isset($_POST["sub1"]))
                {
                    $_SESSION["section"] = 2;
                    echo "<script>location='../app/php/supervisor/logic/allowReportLogic.php?type=allow&id=".$idHex."';</script>";
                }
                else if (isset($_POST["sub0"]))
                {
                    $_SESSION["section"] = 0;
                }
            }
            else if ($_SESSION["section"] == 2)
            {
                echo "<script>location='supervisor/supervisorPage.php';</script>";
            }
            else 
            {
                $_SESSION["section"] = 0;
            }
            

            $inf[] = "report-name";
            $inf[] = "file-report";
            $inf[] = "id-internship";

            for ($i=0; $i<=2; $i++)
            {
                if(!isset($_POST[$inf[$i]])){
                    if(!isset($_SESSION[$inf[$i]])){
                        $_SESSION[$inf[$i]] = '';
                    }
                }else{
                    $_SESSION[$inf[$i]] = $_POST[$inf[$i]];  
                }
            }            

            if($_SESSION['section'] == 1){       

                $form1 = "
                            <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                                <form action='?type=".$type."&id=".$idHex."' method='POST' id='f2'>
                                    <h2 class='text-white'>Estagiário</h2>
                                    
                                    <label for='report-name' class='col-form-label'>Tipo de relatório:</label>
                                    <select class='form-control' id='report-name' name='report_name'>
                                        <option value='Relatório Final (Obrigatório)'>Relatório Final (estágio obrigatório I e II)</option>
                                    </select>                                           
                                
                                    <label for='file-report' class='col-form-label'>Visualizar o arquivo selecionado:</label><br>
                                    <a href='../app/php/email/reports_upload/".$_SESSION["link"]."' data-toggle='tooltip' data-placement='top' title='CLique para visualizar o relatório.'> 
                                        <input type='text' id='file-report' class='form-control' name='file-report' value='".$_SESSION["link"]."' disabled>
                                    </a><br>
                                
                                    <input type='hidden' class='form-control' id='id-internship' name='id_internship' value='".$id."'>                               
                                    
                                    <input type='button' name='sub0' onclick='sub01()' class='btn btn-dark fw-bold' value='Voltar'>
                                    <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' name='sub1' value='Confirmar'>
                                </form> 
                            </div>
                        ";
                echo $form1;
            }

            if($_SESSION['section'] == 0){
                $form0 = "
                            <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                                <form action='../app/php/insertReport.php' method='POST' id='f1'  enctype='multipart/form-data'>
                                    <h2 class='text-white'>Estagiário</h2>
                                    
                                    <label for='report-name' class='col-form-label'>Tipo de relatório:</label>
                                    <select class='form-control' id='report-name' name='report-name'>
                                        <option value='Relatório Final (Obrigatório)'>Relatório Final (estágio obrigatório I e II)</option>
                                    </select>                                           
                                
                                    <label for='file-report' class='col-form-label'>Selecione o arquivo de seu relatório:</label>
                                    <input type='file' class='form-control' id='file-report' name='file-report'/>
                    
                                    <input type='hidden' class='form-control' id='id-internship' name='id-internship' value='".$idHex."'>
                                    <p>Atenção, os arquivos só podem conter no máxino 2MB!</p>
                                   
                                    <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' value='Enviar'>
                                </form> 
                            </div>
                        ";
                echo $form0;
            }

        ?>

        <footer class="text-center pt-5 pb-3">
             &copy; 2022 CTI - Colégio Técnico Industrial "Prof. Isaac Portal Roldán"
        </footer>
    
    </div>
        
</body>
</html>
