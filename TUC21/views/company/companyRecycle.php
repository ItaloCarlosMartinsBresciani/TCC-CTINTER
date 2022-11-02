<?php
    session_start();

    if(!isset($_SESSION['isAuth'])){
        echo "<script>alert('Usuário não reconhecido.');</script>";
        header("Location: ../../index.php ");
        exit();
    }
    
    

    require_once('../../app/db/connect.php');
    require_once('../../app/php/functions.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de alunos - Empresa</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../public/bootstrap/bootstrap.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../../public/css/sidebar.css">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a39639353a.js" crossorigin="anonymous"></script>
</head>
<body>
  <div id="wrapper" class="p-0">
      <div id="navbar-wrapper">
          <nav class="navbar navbar-inverse">
              <div class="container-fluid">
                  <div class="navbar-header">
                      <a href="companyPage.php" class="navbar-brand" style="font-size: 24px;"><i class="fas fa-arrow-circle-left"></i></a>
                  </div>
              </div>
          </nav>
    </div>

    <?php
      if (isset($_GET['type'])) {
        $type = cleanString($_GET['type']);  
      } else {
      header('Location: companyPage.php');
      }
    ?>

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 py-2" id="content">
                    <form action="../../app/php/company/logic/companyEditLogic.php?<?php echo "type=$type&id=$id"?>" method="POST">
                        
                        <?php
                            if ($type == 'person') {
                              // Edição de Pessoas

                                $query = 'SELECT p.id_person, p.name_person, p.telephone_person, p.email_person, p.deleted_date, p.valid FROM person p, university_employee u WHERE NOT p.valid AND u.fk_id = p.id_person';

                                $stmt = $conn->prepare($query);

                                $stmt->execute();

                                $return = $stmt->fetchAll(PDO::FETCH_ASSOC); 

                                if(count($return) > 0) {
                                    echo '<h2 class="py-2">Pessoas Excluídas</h2>';
                               
                                    echo '
                                    <table class="table table-striped">
                                        <thead class="table-dark">
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Nome</th>
                                                <th scope="col">Data de Exclusão</th>
                                                <th scope="col">Contato</th>
                                                <th scope="col">Configurações</th>
                                            </tr>
                                        </thead>
                                        <tbody id="person-content">';
                            
                                    foreach($return as $key => $filterPerson) {
                                        $date = date('d/m/Y', strtotime($filterPerson['deleted_date']));
                                        $idHex = codeId($filterPerson['id_person']);
                            
                                        echo '
                                        <tr>
                                            <th scope="row">'.($key + 1).'</th>
                                            <td>'. $filterPerson['name_person'].'</td>
                                            <td>'.$date.'</td>
                                            <td>
                                                <a href="mailto:'.$filterPerson['email_person'].'?subject=Hello%20again" class="btn btn-primary text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                                                        <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>
                                                    </svg>
                                                </a>
                                                <a href="tel:'.$filterPerson['telephone_person'].'" class="btn btn-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                                                    </svg>
                                                </a>
                                            </td>
                                            <td>
                                                <button class="btn btn-success" onclick="recycle(10,'.$idHex.')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-recycle" viewBox="0 0 16 16">
                                                        <path d="M9.302 1.256a1.5 1.5 0 0 0-2.604 0l-1.704 2.98a.5.5 0 0 0 .869.497l1.703-2.981a.5.5 0 0 1 .868 0l2.54 4.444-1.256-.337a.5.5 0 1 0-.26.966l2.415.647a.5.5 0 0 0 .613-.353l.647-2.415a.5.5 0 1 0-.966-.259l-.333 1.242-2.532-4.431zM2.973 7.773l-1.255.337a.5.5 0 1 1-.26-.966l2.416-.647a.5.5 0 0 1 .612.353l.647 2.415a.5.5 0 0 1-.966.259l-.333-1.242-2.545 4.454a.5.5 0 0 0 .434.748H5a.5.5 0 0 1 0 1H1.723A1.5 1.5 0 0 1 .421 12.24l2.552-4.467zm10.89 1.463a.5.5 0 1 0-.868.496l1.716 3.004a.5.5 0 0 1-.434.748h-5.57l.647-.646a.5.5 0 1 0-.708-.707l-1.5 1.5a.498.498 0 0 0 0 .707l1.5 1.5a.5.5 0 1 0 .708-.707l-.647-.647h5.57a1.5 1.5 0 0 0 1.302-2.244l-1.716-3.004z"/>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                        ';
                                    }
                            
                                    echo '
                                        </tbody>
                                    </table>
                                    ';
                                }   
                                else {
                                    echo '<div class="h3 text-center my-5 py-5 text-secondary">Você não têm pessoas excluídas</div>';
                                }
                                
                            } 
                            else if ($type == 'employee') {
                                // Edição de Pessoas
  
                                $query = 'SELECT p.id_person, p.name_person, p.telephone_person, p.email_person, p.treatment_person, p.register_date, p.deleted_date, p.valid FROM person p, company_employee s WHERE NOT p.valid AND p.id_person = s.fk_id';
  
                                  $stmt = $conn->prepare($query);
  
                                  $stmt->execute();
  
                                  $return = $stmt->fetchAll(PDO::FETCH_ASSOC); 
  
                                  if(count($return) > 0) {
                                      echo '<h2 class="py-2">Funcionários Excluídos</h2>';
                                 
                                      echo '
                                      <table class="table table-striped">
                                          <thead class="table-dark">
                                              <tr>
                                                  <th scope="col">#</th>
                                                  <th scope="col">Nome</th>
                                                  <th scope="col">Data de Exclusão</th>
                                                  <th scope="col">Contato</th>
                                                  <th scope="col">Configurações</th>
                                              </tr>
                                          </thead>
                                          <tbody id="person-content">';
                              
                                      foreach($return as $key => $filterPerson) {
                                          $date = date('d/m/Y', strtotime($filterPerson['deleted_date']));
                                          $idHex = codeId($filterPerson['id_person']);
                              
                                          echo '
                                          <tr>
                                              <th scope="row">'.($key + 1).'</th>
                                              <td>'. $filterPerson['name_person'].'</td>
                                              <td>'.$date.'</td>
                                              <td>
                                                  <a href="mailto:'.$filterPerson['email_person'].'?subject=Hello%20again" class="btn btn-primary text-white">
                                                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                                                          <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>
                                                      </svg>
                                                  </a>
                                                  <a href="tel:'.$filterPerson['telephone_person'].'" class="btn btn-primary">
                                                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                                                          <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                                                      </svg>
                                                  </a>
                                              </td>
                                              <td>
                                                  <button class="btn btn-success" onclick="recycle(15,'.$idHex.')">
                                                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-recycle" viewBox="0 0 16 16">
                                                          <path d="M9.302 1.256a1.5 1.5 0 0 0-2.604 0l-1.704 2.98a.5.5 0 0 0 .869.497l1.703-2.981a.5.5 0 0 1 .868 0l2.54 4.444-1.256-.337a.5.5 0 1 0-.26.966l2.415.647a.5.5 0 0 0 .613-.353l.647-2.415a.5.5 0 1 0-.966-.259l-.333 1.242-2.532-4.431zM2.973 7.773l-1.255.337a.5.5 0 1 1-.26-.966l2.416-.647a.5.5 0 0 1 .612.353l.647 2.415a.5.5 0 0 1-.966.259l-.333-1.242-2.545 4.454a.5.5 0 0 0 .434.748H5a.5.5 0 0 1 0 1H1.723A1.5 1.5 0 0 1 .421 12.24l2.552-4.467zm10.89 1.463a.5.5 0 1 0-.868.496l1.716 3.004a.5.5 0 0 1-.434.748h-5.57l.647-.646a.5.5 0 1 0-.708-.707l-1.5 1.5a.498.498 0 0 0 0 .707l1.5 1.5a.5.5 0 1 0 .708-.707l-.647-.647h5.57a1.5 1.5 0 0 0 1.302-2.244l-1.716-3.004z"/>
                                                      </svg>
                                                  </button>
                                              </td>
                                          </tr>
                                          ';
                                      }
                              
                                      echo '
                                          </tbody>
                                      </table>
                                      ';
                                  }   
                                  else {
                                      echo '<div class="h3 text-center my-5 py-5 text-secondary">Você não tem funcionários excluídos</div>';
                                  }
                                  
                              } 
                            
                            else if ($type == 'intern') {
                              // Edição de Pessoas

                              $query = 'SELECT p.id_person, p.name_person, p.telephone_person, p.email_person, p.treatment_person, p.register_date, p.deleted_date, i.valid FROM person p, internship_data i WHERE NOT p.valid AND p.id_person = i.fk_student';

                                $stmt = $conn->prepare($query);

                                $stmt->execute();

                                $return = $stmt->fetchAll(PDO::FETCH_ASSOC); 

                                if(count($return) > 0) {
                                    echo '<h2 class="py-2">Estagiários Excluídos</h2>';
                               
                                    echo '
                                    <table class="table table-striped">
                                        <thead class="table-dark">
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Nome</th>
                                                <th scope="col">Data de Exclusão</th>
                                                <th scope="col">Contato</th>
                                                <th scope="col">Configurações</th>
                                            </tr>
                                        </thead>
                                        <tbody id="person-content">';
                            
                                    foreach($return as $key => $filterPerson) {
                                        $date = date('d/m/Y', strtotime($filterPerson['deleted_date']));
                                        $idHex = codeId($filterPerson['id_person']);
                            
                                        echo '
                                        <tr>
                                            <th scope="row">'.($key + 1).'</th>
                                            <td>'. $filterPerson['name_person'].'</td>
                                            <td>'.$date.'</td>
                                            <td>
                                                <a href="mailto:'.$filterPerson['email_person'].'?subject=Hello%20again" class="btn btn-primary text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                                                        <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>
                                                    </svg>
                                                </a>
                                                <a href="tel:'.$filterPerson['telephone_person'].'" class="btn btn-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                                                    </svg>
                                                </a>
                                            </td>
                                            <td>
                                                <button class="btn btn-success" onclick="recycle(10,'.$idHex.')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-recycle" viewBox="0 0 16 16">
                                                        <path d="M9.302 1.256a1.5 1.5 0 0 0-2.604 0l-1.704 2.98a.5.5 0 0 0 .869.497l1.703-2.981a.5.5 0 0 1 .868 0l2.54 4.444-1.256-.337a.5.5 0 1 0-.26.966l2.415.647a.5.5 0 0 0 .613-.353l.647-2.415a.5.5 0 1 0-.966-.259l-.333 1.242-2.532-4.431zM2.973 7.773l-1.255.337a.5.5 0 1 1-.26-.966l2.416-.647a.5.5 0 0 1 .612.353l.647 2.415a.5.5 0 0 1-.966.259l-.333-1.242-2.545 4.454a.5.5 0 0 0 .434.748H5a.5.5 0 0 1 0 1H1.723A1.5 1.5 0 0 1 .421 12.24l2.552-4.467zm10.89 1.463a.5.5 0 1 0-.868.496l1.716 3.004a.5.5 0 0 1-.434.748h-5.57l.647-.646a.5.5 0 1 0-.708-.707l-1.5 1.5a.498.498 0 0 0 0 .707l1.5 1.5a.5.5 0 1 0 .708-.707l-.647-.647h5.57a1.5 1.5 0 0 0 1.302-2.244l-1.716-3.004z"/>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                        ';
                                    }
                            
                                    echo '
                                        </tbody>
                                    </table>
                                    ';
                                }   
                                else {
                                    echo '<div class="h3 text-center my-5 py-5 text-secondary">Você não têm estagiários excluídos</div>';
                                }
                                
                            } 
                            else {
                              header('Location: companyPage.php');
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

    <!-- Recycle Modal -->
    <div class="modal fade show" id="recycleModal" role="dialog" tabindex="-1" aria-labelledby="recycleModalTitle" aria-hidden="true" onload="teste()">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="recycleModalTitle"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="../../app/php/company/logic/companyRecycleLogic.php" method="GET">
            <div class="modal-body"></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
              <input type="submit" class="btn btn-success" value="Confirmar Reativação">
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Bootstrap -->
    <script src="../../public/bootstrap/bootstrap.min.js"></script>

    <script src="../../js/company.js"></script>

    <?php
      if(isset($_SESSION['feedback'])) {
        if($_SESSION['feedback'] == 'successRecycle') {
          echo "
          <script>
            openFeedbackModal('feedbackModal', 'Sucesso', 'Recuperação realizada com sucesso.');
          </script>
          ";  
        } else if($_SESSION['feedback'] == 'errorRecycle') {
          echo "
          <script>
            openFeedbackModal('feedbackModal', 'Erro', 'Ocorreu um erro durante a recuperação.');
          </script>
          ";
        }
        
        unset($_SESSION['feedback']);
      }

    ?>
</body>
</html>