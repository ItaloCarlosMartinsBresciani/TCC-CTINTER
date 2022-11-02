<?php
    session_start();
    if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 1){
      echo "<script>alert('Usuário não reconhecido.');</script>";
      header("Location: ../../index.php ");
      exit();
    }  
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Home - Intern</title>


    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../public/bootstrap/bootstrap.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../../public/css/sidebar.css">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a39639353a.js" crossorigin="anonymous"></script>
</head>
<body>
    <div id="wrapper">

    <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="internPage.php">
        <span>Estagiário</span>
      </a>
    </div>
    <ul class="sidebar-nav">
      <li class="active">
        <div><i class="fa fa-home"></i> Home</div>
      </li>
      <li>
        <div><i class="fa fa-user-tie"></i> Orientador</div>
      </li>
      <li>
        <div><i class="fa fa-user-tie"></i> Supervisor</div>
      </li>
      <li>
        <div><i class="fa fa-graduation-cap"></i> Coordenador</div>
      </li>
     <li>
        <div><i class="fa fa-briefcase"></i> Empresa</div>
     </li>
    <li>
        <div><i class='fa fa-file-alt'></i> Documentos</div>
    </li>
    <li>
        <div><i class='fa fa-folder-open'></i> Relatórios</div> 
    </li>    
    <li>
        <div><i class="fa fa-user"></i> Perfil</div>
    </li>
    <li>
        <div><i class="fa fa-book"></i> Estágio</div>
    </li>
    <a href="../../app/php/logout.php" id="logout" style="margin-top: 115%">
    <i class="fa fa-power-off"></i>
    </a>
    </ul>
  </aside>

  <div id="navbar-wrapper">
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <a href="#" class="navbar-brand" id="sidebar-toggle"><i class="fa fa-bars"></i></a>
        </div>
      </div>
    </nav>
  </div>

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 py-2" id="content">
                    
                </div>
            </div>
        </div> 
    </div>
    <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- FeedBack Modal -->
    <div class="modal fade show" id="feedbackModal" role="dialog" tabindex="-1" aria-labelledby="feedbackModalTitle" aria-hidden="true" onload="teste()">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="feedbackModalTitle"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body"></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Exclude Modal -->
    <div class="modal fade show" id="excludeModal" role="dialog" tabindex="-1" aria-labelledby="excludeModalTitle" aria-hidden="true" onload="teste()">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="excludeModalTitle"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="../../app/php/professor/logic/professorExcludeLogic.php" method="GET">
            <div class="modal-body"></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
              <input type="submit" class="btn btn-danger" value="Confirmar Exclusão">
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Bootstrap -->
    <script src="../../public/bootstrap/bootstrap.min.js"></script>

    <script src="../../js/nav.js"></script>
    
    <script src='../../js/intern.js'></script>

    <?php
      if(isset($_SESSION['feedback'])) {
        switch($_SESSION['feedback']) {
          case 'successEmail':
              echo "
                  <script>
                    openFeedbackModal('feedbackModal', 'Sucesso', 'Seu e-mail foi enviado com sucesso.', 2);
                  </script>
                  ";
            break;
          case 'errorEmail':
            echo "
                <script>
                  openFeedbackModal('feedbackModal', 'Erro', 'Ocorreu um erro durante o envio do e-mail.', 2);
                </script>
                ";
          break;
          case 'successExclude':
              echo "
              <script>
                openFeedbackModal('feedbackModal', 'Sucesso', 'Exclusão bem sucedida.', ".$_SESSION['btn'].");
              </script>
              ";
              break;
          case 'errorExclude':
            echo "
            <script>
              openFeedbackModal('feedbackModal', 'Erro', 'Ocorreu um erro durante a exclusão.', ".$_SESSION['btn'].");
            </script>
            ";
          case 'successEdit':
            echo "
            <script>
              openFeedbackModal('feedbackModal', 'Sucesso', 'Edição bem sucedida.', ".$_SESSION['btn'].");
            </script>
            ";
            break;
          case 'errorEdit':
            echo "
            <script>
              openFeedbackModal('feedbackModal', 'Erro', 'Ocorreu um erro durante a edição.', ".$_SESSION['btn'].");
            </script>
            ";
          case 'errorModalInfo':
            echo "
            <script>
            openFeedbackModal('feedbackModal', 'Erro', 'Ocorreu um erro ao receber as informações!', ".$_SESSION['btn'].");
            </script>
            ";
            break;
          case 'successInternshipPlan':
            echo "
            <script>
            openFeedbackModal('feedbackModal', 'Sucesso', 'Seu plano de estágio já foi registrado com sucesso!', ".$_SESSION['btn'].");
            </script>
            ";
            break;
          case 'errorInternshipPlan':
            echo "
            <script>
            openFeedbackModal('feedbackModal', 'Erro', 'Ocorreu um erro ao tentar registrar seu plano de estágio!', ".$_SESSION['btn'].");
            </script>
            ";
            break;
          case 'planAlreadyExists':
            echo "
            <script>
              openFeedbackModal('feedbackModal', 'Aviso', 'Você já preencheu seu plano de estágio, aguarde o parecer de seus superiores.', ".$_SESSION['btn'].");
            </script>
            ";
            break;
        }

        if($_SESSION['feedback'] == 'successEmail') {
            
        } else if($_SESSION['feedback'] == 'errorEmail') {
          
        } else if($_SESSION['feedback'] == 'successExclude') {
          
        } else if($_SESSION['feedback'] == 'errorExclude') {
          
        }
        
        unset($_SESSION['feedback']);
        unset($_SESSION['btn']);
      }

    ?>
</body>
</html>