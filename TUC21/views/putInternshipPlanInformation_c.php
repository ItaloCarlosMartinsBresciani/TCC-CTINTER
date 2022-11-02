<?php

    use Google\Service\Script;

    session_start();
    if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 9){
        echo "<script>alert('Usuário não reconhecido.');</script>";
        header("Location: ../../index.php ");
        exit();
    }

    if ($_SESSION["email"] == 1)
    {
        header("Location: ../index.php ");
        exit();
    }

    if(isset($_GET["id"]))
    {
        $idHex = $_GET["id"];
    }

    require_once('../app/php/functions.php');
    require_once('../app/db/connect.php');

    $query = 'SELECT ip.* FROM internship_plan ip, internship_data i WHERE i.id_internship_data = ip.fk_internship_data AND i.fk_student = :idUser';
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':idUser', $_SESSION["idUser"]);
    $stmt->execute();        
    $return_internship = $stmt->fetch(PDO::FETCH_ASSOC); 

    $query = 'SELECT * FROM student WHERE fk_id = :idUser';
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':idUser', $_SESSION["idUser"]);
    $stmt->execute();        
    $return = $stmt->fetch(PDO::FETCH_ASSOC); 

    if($return_internship){
        if ($return["total_hours_student"] != NULL)
        {
            $_SESSION["feedback"] = 'planAlreadyExists';
            $_SESSION['btn'] = 1;
            echo "<script>location= 'intern/internPage.php';</script>";
            exit();
        }
    }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Informações Adicionais Plano de Estágio</title>

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

        function edit1(){
            $('[name=opinion_coordinator]').removeAttr('disabled');
        };

        function submitForm(){
            $('[name=opinion_coordinator]').removeAttr('disabled');             
            $('[name=opinion_coordinator]').removeAttr('required'); 
            
            $('form').append('<input type="hidden" name="sub3" />');
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

            $inf[] = "opinion_coordinator";

            if(!isset($_POST["opinion_coordinator"])){
                    if(!isset($_SESSION["opinion_coordinator"])){
                        $_SESSION["opinion_coordinator"] = '';
                    }
                }else{
                    $_SESSION["opinion_coordinator"] = $_POST["opinion_coordinator"];  
                }
            
            if(isset($_POST['sub2'])){
                $_SESSION['section'] = 1;                
            }
            else if(isset($_POST['sub3']))
            {
                $_SESSION['c_approve'] = 1;
                echo "<script>location= '../app/php/email/sendEmailPlanCoordinator.php?type=approve&id=".$idHex."';</script>";
                
            }
            else {
                $_SESSION['section'] = 0;
            }       

            if($_SESSION['section'] == 1){
                $form1 = "
                            <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                                <form action='?id=".$idHex."' method='POST' id='form'>
                                    <h2 class='text-white'>Visualização do Plano de Estágio
                                        <button type='button' class='btn btn-light' data-toggle='tooltip' data-placement='right' title='Editar' onclick='edit1()'>
                                            <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-pencil-fill' viewBox='0 0 16 16'>
                                                <path d='M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z'/>
                                            </svg>
                                        </button>
                                    </h2>
                                    <p for='file-coordinator' class='col-form-label' style='color:white;'>Visualização do Plano de Estágio:</p>
                                    <a href='coordinator/documents/proposta_de_plano_de_estagio.php' data-toggle='tooltip' data-placement='top' title='Clique para visualizar o relatório.'> 
                                        <input type='text' id='file-coordinator' class='form-control' name='file-coordinator' value='proposta_plano_de_estagio.php' disabled>
                                    </a><br>

                                   <p for='opinion-coordinator' class='col-form-label' style='color:white;'>Opinião do Coordenador sobre o plano de estágio:</p>
                                    <textarea id='opinion-coordinator' class='form-control' name='opinion_coordinator' rows='10' disabled>".$_SESSION['opinion_coordinator']."</textarea><br>                                    
                                   
                                    <input type='submit' class='btn btn-success col-lg-3 col-12 fw-bold' style='border:solid 1px black;' name='sub3' value='Confirmar'>
                                </form> 
                            </div>
                        ";
                echo $form1;
            }

            if($_SESSION['section'] == 0){
                $form0 = "
                            <div class='w-100 rounded bg-primary' style='padding: 1em;'>
                                <form action='?id=".$idHex."' method='POST' id='f1'>
                                    <h2 class='text-white'>Informações Adicionais - Plano de Estágio</h2>
                                            
                                    <p for='file-coordinator' class='col-form-label' style='color:white;'>Visualização do Plano de Estágio:</p>
                                    <a href='coordinator/documents/proposta_de_plano_de_estagio.php?id=".$idHex."' data-toggle='tooltip' data-placement='top' title='Clique para visualizar o relatório.'> 
                                        <input type='text' id='file-coordinator' class='form-control' name='file-coordinator' value='proposta_plano_de_estagio.php' disabled>
                                    </a><br>

                                   <p for='opinion-coordinator' class='col-form-label' style='color:white;'>Opinião do Coordenador sobre o plano de estágio:</p>
                                    <textarea id='opinion-coordinator' class='form-control' name='opinion_coordinator' rows='10' required>".$_SESSION['opinion_coordinator']."</textarea><br>
                                  

                                   <a href='../app/php/email/sendEmailPlanCoordinator.php?type=disapprove&id=".$idHex."'><input type='button' class='btn btn-dark col-lg-3 col-12 fw-bold' value='Rejeitar'></a>&nbsp;                                 
                                    <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' name='sub2' value='Enviar'>
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