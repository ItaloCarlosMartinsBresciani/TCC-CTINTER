<?php
    session_start();

    if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 7){
        echo "<script>alert('Usuário não reconhecido.');</script>";
        header("Location: ../../index.php ");
        exit();
    }  

    require_once('../../app/db/connect.php');
    require_once('../../app/php/functions.php');

    $id_person = $_SESSION['idUser'];
    
    $query = "SELECT * FROM change_data WHERE fk_id = ".$id_person." AND allowed = FALSE";
   
    $stmt = $conn->prepare($query);

    $return = $stmt->execute();

    $return = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($return) //se o pedido ainda não foi permitido
    {
        $query = "SELECT * FROM change_data WHERE fk_id = ".$id_person." AND allowed = FALSE AND pending_allowance = TRUE";//solicitações pendentes
   
        $stmt = $conn->prepare($query);

        $return = $stmt->execute();

        $return = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $query = "SELECT * FROM change_data WHERE fk_id = ".$id_person." AND edited = TRUE"; //dados já editados
   
        $stmt = $conn->prepare($query);

        $return_2 = $stmt->execute();

        $return_2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $query = "SELECT * FROM change_data WHERE fk_id = ".$id_person." AND blocked_edition = TRUE"; //pessoas com a edição bloqueada
   
        $stmt = $conn->prepare($query);

        $return_3 = $stmt->execute();

        $return_3 = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($return_3)
        {
            echo "<script>alert('Seu poder de requisição de edição foi bloqueado. Caso achar que isso é um erro, entre em contato com seus superiores.');</script>";
            echo "<script>window.location.replace('supervisorPage.php')</script>";
        }
        else if ($return)
        {
            echo "<script>alert('Seu pedido de edição ainda não foi respondido.');</script>";           
            echo "<script>window.location.replace('supervisorPage.php')</script>";
        }
        else if ($return_2)
        {
            echo "<script>alert('Você já editou seus dados, peça permissão novamente caso queira editar outras informações.');</script>";
            echo "<script>window.location.replace('supervisorPage.php')</script>";
        }
        else
        {
            echo "<script>alert('Você não possui acesso à edição destes dados.');</script>";
            echo "<script>window.location.replace('supervisorPage.php')</script>";
        }
    }  
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edição - Supervisor</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../public/bootstrap/bootstrap.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../../public/css/sidebar.css">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a39639353a.js" crossorigin="anonymous"></script>

    <!-- JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js%22%3E"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js" 
        integrity="sha256-yE5LLp5HSQ/z+hJeCqkz9hdjNkk1jaiGG0tDCraumnA=" 
        crossorigin="anonymous">
    </script>

    <script>
    /*Máscaras - inputs */
    jQuery(function($){
        $("#telephone-person").mask("(00) 00000-0000");
        $("#rg-person").mask("00.000.000-0");
        $("#cpf-person").mask("000.000.000-00");
        
    });
    </script>
</head>
<body>
  <div id="wrapper" class="p-0">
      <div id="navbar-wrapper">
          <nav class="navbar navbar-inverse">
              <div class="container-fluid">
                  <div class="navbar-header">
                      <a href="supervisorPage.php" class="navbar-brand" style="font-size: 24px;"><i class="fas fa-arrow-circle-left"></i></a>
                  </div>
              </div>
          </nav>
    </div>

    <?php
      if (isset($_GET['type']) && isset($_GET['id'])) {
        $type = cleanString($_GET['type']);

        try {
          $id = cleanString($_GET['id']);

          $idDec = decodeId($id);
        }
        catch (TypeError) {
          header('Location: supervisorPage.php');
        }   
      } else {
      header('Location: supervisorPage.php');
      }
    ?>

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 py-2" id="content">
                    <form action="../../app/php/supervisor/logic/supervisorEditLogic.php?<?php echo "type=$type&id=$id"?>" method="POST">
                        
                        <?php
                            if ($type == 'person') 
                            {
                              // Edição de Pessoas
                              
                              $query = 'SELECT * FROM person WHERE id_person = :id';

                              $stmt = $conn->prepare($query);

                              $stmt->bindValue(':id', $idDec);

                              $stmt->execute();

                              $return = $stmt->fetch(PDO::FETCH_ASSOC);
                              
                              if($return['valid']) 
                              {
                                    echo '<h2 class="py-2">Edição - '.$return['name_person'].'</h2>';
                                
                                    $form =
                                    "
                                    <div class='row'>
                                        <div class='col-lg-6 col-sm-12'>
                                            <label for='name-person' class='lead fw-normal'>Nome:</label>
                                            <input type='text' id='name-person' class='form-control' name='name-person' value='".$return['name_person']."' required><br>
                                            <label for='email-person' class='lead fw-normal'>Email:</label>
                                            <input type='email' id='email-person' class='form-control' name='email-person' value='".$return['email_person']."' required><br>
                                            <label for='telephone-person' class='lead fw-normal'>Telefone:</label>
                                            <input type='tel' id='telephone-person' class='form-control' name='telephone-person' value='".$return['telephone_person']."' required><br>
                                        </div>
                                        <div class='col-lg-6 col-sm-12'>
                                            <label for='cpf-person' class='lead fw-normal'>CPF:</label>
                                            <input type='text' id='cpf-person' class='form-control' name='cpf-person' value='".$return['cpf_person']."' required><br>
                                            <label for='rg-person' class='lead fw-normal'>RG:</label>
                                            <input type='text' id='rg-person' class='form-control' name='rg-person' value='".$return['rg_person']."' required><br>
                                            <label for='treatment-person' class='lead fw-normal'>Tratamento:</label>
                                            <input type='text' id='treatment-person' class='form-control' placeholder='Ex: Doutor' name='treatment-person' value='".$return['treatment_person']."' required><br>
                                        </div> 
                                    </div> 

                                    <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' value='Salvar'>
                                    ";

                                    echo $form;

                              }
                              else {
                                header('Location: supervisorPage.php');
                              }
                            }
                            else if ($type == 'profile') 
                            {
                                // Edição de Dados Pessoais
                              
                                $query = 'SELECT * FROM person WHERE id_person = :id';

                                $stmt = $conn->prepare($query);

                                $stmt->bindValue(':id', $idDec);

                                $stmt->execute();

                                $return = $stmt->fetch(PDO::FETCH_ASSOC);
                                
                                if($return['valid']) 
                                {
                                        echo '<h2 class="py-2">Edição - '.$return['name_person'].'</h2>';
                                    
                                        $form =
                                        "
                                        <div class='row'>
                                            <div class='col-lg-6 col-sm-12'>
                                                <label for='name-person' class='lead fw-normal'>Nome:</label>
                                                <input type='text' id='name-person' class='form-control' name='name-person' value='".$return['name_person']."' required><br>
                                                <label for='email-person' class='lead fw-normal'>Email:</label>
                                                <input type='email' id='email-person' class='form-control' name='email-person' value='".$return['email_person']."' required><br>
                                                <label for='telephone-person' class='lead fw-normal'>Telefone:</label>
                                                <input type='tel' id='telephone-person' class='form-control' name='telephone-person' value='".$return['telephone_person']."' required><br>
                                            </div>
                                            <div class='col-lg-6 col-sm-12'>
                                                <label for='cpf-person' class='lead fw-normal'>CPF:</label>
                                                <input type='text' id='cpf-person' class='form-control' name='cpf-person' value='".$return['cpf_person']."' required><br>
                                                <label for='rg-person' class='lead fw-normal'>RG:</label>
                                                <input type='text' id='rg-person' class='form-control' name='rg-person' value='".$return['rg_person']."' required><br>
                                                <label for='treatment-person' class='lead fw-normal'>Tratamento:</label>
                                                <input type='text' id='treatment-person' class='form-control' placeholder='Ex: Doutor' name='treatment-person' value='".$return['treatment_person']."' required><br>
                                            </div> 
                                        </div> 

                                        <input type='submit' class='btn btn-dark col-lg-3 col-12 fw-bold' value='Salvar'>
                                        ";

                                        echo $form;

                                }
                                else {
                                    header('Location: supervisorPage.php');
                                }
                            }
                        ?>

                    </form>
                </div>
            </div>
        </div> 
    </div>
    <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Bootstrap -->
    <script src="../../public/bootstrap/bootstrap.min.js"></script>

    <script src="../../js/supervisor.js"></script>
</body>
</html>