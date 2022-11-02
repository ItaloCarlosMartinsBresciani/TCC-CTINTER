<?php
    session_start();
    if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 9){
      echo "<script>alert('Usuário não reconhecido.');</script>";
      header("Location: ../../index.php ");
      exit();
    }
    
    $_SESSION["email"] = 0;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Coordenador</title>

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
      <a href="coordinatorPage.php">
        <span>Coordenador</span>
      </a>
    </div>
    <ul class="sidebar-nav">
      <li class="active">
        <div><i class="fa fa-home"></i>Home</div>
      </li>
      <li>
        <div><i class="fa fa-user-friends"></i> Orientadores</div> 
      </li>
      <li>
        <div><i class="fa fa-user-graduate"></i> Alunos</div> 
      </li>
      <li>
        <div><i class="fa fa-user-graduate"></i> Estagiários</div> <!-- Ele só vai ver os estudantes procurando orientadores -->
      </li>
      <li>
        <div><i class="fa fa-clipboard-check"></i> Estágios Concluídos</div> 
      </li>
      <li>
        <div><i class='fa fa-folder-open'></i> Documentos</div>
      </li>
      <li>
        <div><i class='fa fa-folder-open'></i> Planos de Estágio</div>
      </li>
      <li>
        <div><i class="fa fa-graduation-cap"></i> Instituição de Ensino</div>
      </li>
      <li>
        <div><i class="fa fa-briefcase"></i> Empresas</div>
      </li>
      <li>
        <div><i class="fa fa-user"></i> Perfil</div>
      </li>
      

      <a href="../../app/php/logout.php" id="logout" style="margin-top: 110%;">
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
          <form action="../../app/php/coordinator/logic/coordinatorExcludeLogic.php" method="GET">
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
    <script src="../../js/coordinator.js"></script>

    <?php
      if(isset($_SESSION['feedback'])) {
        switch($_SESSION['feedback']) {
            case 'successEmail':
            echo "
              <script>
                openFeedbackModal('feedbackModal', 'Sucesso', 'Seu email foi enviado com sucesso.', 2);
              </script>
              ";
            break;
            case 'errorEmail':
              echo "
              <script>
                openFeedbackModal('feedbackModal', 'Erro', 'Ocorreu um erro durante o envio do Email.', 2);
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
              break;
            case 'successRecycle':
                echo "
                <script>
                  openFeedbackModal('feedbackModal', 'Sucesso', 'Recuperação bem sucedida.', ".$_SESSION['btn'].");
                </script>
                ";
                break;
            case 'errorRecycle':
              echo "
              <script>
                openFeedbackModal('feedbackModal', 'Erro', 'Ocorreu um erro durante a recuperação.', ".$_SESSION['btn'].");
              </script>
              ";
              break;
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
              break;
            case 'successAllowedEdition':
              echo "
              <script>
              openFeedbackModal('feedbackModal', 'Sucesso', 'Edição permitida com sucesso.', ".$_SESSION['btn'].");
              </script>
              ";
              break;
            case 'successDeniedEdition':
              echo "
              <script>
              openFeedbackModal('feedbackModal', 'Sucesso', 'Edição negada com sucesso.', ".$_SESSION['btn'].");
              </script>
              ";
              break;
            case 'errorAllowedEdition':
              echo "
              <script>
              openFeedbackModal('feedbackModal', 'Erro', 'Erro ao permitir edição.', ".$_SESSION['btn'].");
              </script>
              ";
              break;
            case 'errorDeniedEdition':
              echo "
              <script>
              openFeedbackModal('feedbackModal', 'Erro', 'Erro ao negar edição.', ".$_SESSION['btn'].");
              </script>
              ";
              break;
            case 'successAllowEdition':
              echo "
              <script>
              openFeedbackModal('feedbackModal', 'Sucesso', 'Solicitação de edição permitida com sucesso.', ".$_SESSION['btn'].");
              </script>
              ";
              break;
            case 'successBlockEdition':
              echo "
              <script>
              openFeedbackModal('feedbackModal', 'Sucesso', 'Solicitação de edição bloqueada com sucesso.', ".$_SESSION['btn'].");
              </script>
              ";
              break;
            case 'errorAllowEdition':
              echo "
              <script>
              openFeedbackModal('feedbackModal', 'Erro', 'Erro ao permitir solicitação de edição.', ".$_SESSION['btn'].");
              </script>
              ";
              break;
            case 'errorBlockEdition':
              echo "
              <script>
              openFeedbackModal('feedbackModal', 'Erro', 'Erro ao bloquear a solicitação de edição.', ".$_SESSION['btn'].");
              </script>
              ";
              break;
            case 'successDeniedReport':
              echo "
              <script>
                openFeedbackModal('feedbackModal', 'Sucesso', 'Relatório negado com sucesso.', ".$_SESSION['btn'].");
              </script>
              ";
              break;
            case 'errorAllowedReport':
              echo "
              <script>
                openFeedbackModal('feedbackModal', 'Erro', 'Erro ao aprovar relatório.', ".$_SESSION['btn'].");
              </script>
              ";
              break;
            case 'errorDeniedReport':
              echo "
              <script>
                openFeedbackModal('feedbackModal', 'Erro', 'Erro ao negar relatório.', ".$_SESSION['btn'].");
              </script>
              ";
              break;
            case 'successAllowedReport':
              echo "
              <script>
                openFeedbackModal('feedbackModal', 'Sucesso', 'Relatório aprovado com sucesso.', ".$_SESSION['btn'].");
              </script>
              ";
              break;
            case 'successInvalidateInternship':
              echo "
              <script>
                openFeedbackModal('feedbackModal', 'Sucesso', 'Estágio invalidado com sucesso.', ".$_SESSION['btn'].");
              </script>
              ";
              break;
            case 'successValidateInternship':
              echo "
              <script>
                openFeedbackModal('feedbackModal', 'Sucesso', 'Estágio validado com sucesso.', ".$_SESSION['btn'].");
              </script>
              ";
              break;
            case 'errorInvalidateInternship':
              echo "
              <script>
                openFeedbackModal('feedbackModal', 'Erro', 'Erro ao invalidar estágio.', ".$_SESSION['btn'].");
              </script>
              ";
              break;
            case 'errorValidateInternship':
              echo "
              <script>
                openFeedbackModal('feedbackModal', 'Erro', 'Erro ao validar estágio.', ".$_SESSION['btn'].");
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

