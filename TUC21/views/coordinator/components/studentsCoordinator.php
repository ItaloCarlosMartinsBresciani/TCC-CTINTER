<?php 
    session_start();
    if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 9){
        echo "<script>alert('Usuário não reconhecido.');</script>";
        header("Location: ../../../index.php ");
        exit();
    }
    
?>

<h2>Alunos</h2>

<p>
    Página de visualização e convite de alunos
</p>

<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sendEmailModal">Convidar Alunos</button>

<div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendEmailModalLabel">Convidar Alunos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../app/php/email/sendEmailCoordinator.php?type=invitation" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="message-email" class="col-form-label">E-mail:</label>
                        <input type="email" class="form-control" id="message-email" name="message-email">
                    </div>
                    <div class="mb-3">
                        <label for="message-subject" class="col-form-label">Assunto:</label>
                        <input type="text" class="form-control" id="message-subject" name="message-subject" value="Estagio CTI">
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Conteúdo:</label>
                        <textarea class="form-control" id="message-text" name="message-text">Utilizando o link a seguir você poderá se cadastrar como aluno(a) no controle de estágio da CTI</textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <input type="submit" class="btn btn-primary" value="Enviar E-mail">
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="registerStudentsModal" tabindex="0" aria-labelledby="registerStudentsLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="width:850px;">
            <div class="modal-header">
                <h5 class="modal-title" id="registerStudentsLabel">Cadastrar Vários Alunos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <?php 

                require_once('../../../app/php/functions.php');
                require_once('../../../app/db/connect.php');
                
                $acessKey = bin2hex(random_bytes(32));

                $query = 'INSERT INTO tokens VALUES(DEFAULT, :acessKey, :validDate);';
                    
                $stmt = $conn->prepare($query);

                $validDate = $expires = date("U") + (3600 * 24 * 7);

                $stmt->bindValue(':acessKey', $acessKey); 
                $stmt->bindValue(':validDate', $validDate);

                $stmt->execute();
            ?>  
            
            <form action="../../app/php/registerMultipleStudentsLogic.php?key=<?php echo $acessKey; ?>&id_professor=<?php echo $_SESSION['idUser']; ?>" method="POST" >   

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="col-form-label">Copie da sua planilha excel os alunos a serem cadastrados e cole no campo abaixo: </label>
                        <label class="col-form-label">Deve ser obedecida a ordem nome, email, rg, tratamento, cpf, telefone, RA, código do curso, ano de entrada e todos os campos devem ser separados por uma barra (|) sem espaços entre os dados.</label>
                        <label class="col-form-label">Caso haja cabeçalho, ao final do mesmo deve haver um asterisco (*) para que o sistema entenda que serão disponibilados os dados em seguida.</label>
                        <label class="col-form-label">Como diferenciação dos dados de cada estudante, é necessário haver um ponto e vírgula (;) após a inserção do ano de entrada.</label>
                        <label class="col-form-label">Obs.: Nenhum dado pode estar em branco e o último dado do último aluno a ser cadastrado NÃO pode ser sucedido por ponto e vírgula (;).</label>
                    </div>
                    <div class="mb-3">
                        <input type="checkbox" id="check-header" name="check-header" checked> Há cabeçalho? (nome | email | rg | tratamento | cpf | telefone | RA | código curso | ano de entrada) </input>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Conteúdo:</label>
                        <textarea class="form-control" id="students-table" name="students-table" style="height:200px;" required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <input type="submit" class="btn btn-primary" value="Cadastrar Alunos">
                </div>
            </form>
        </div>
    </div>
</div>

<nav class="navbar navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand">Pesquisa</a>
    <div class="d-flex">
        <a href="coordinatorRecycle.php?type=student" class="btn btn-success mx-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-recycle" viewBox="0 0 16 16">
                <path d="M9.302 1.256a1.5 1.5 0 0 0-2.604 0l-1.704 2.98a.5.5 0 0 0 .869.497l1.703-2.981a.5.5 0 0 1 .868 0l2.54 4.444-1.256-.337a.5.5 0 1 0-.26.966l2.415.647a.5.5 0 0 0 .613-.353l.647-2.415a.5.5 0 1 0-.966-.259l-.333 1.242-2.532-4.431zM2.973 7.773l-1.255.337a.5.5 0 1 1-.26-.966l2.416-.647a.5.5 0 0 1 .612.353l.647 2.415a.5.5 0 0 1-.966.259l-.333-1.242-2.545 4.454a.5.5 0 0 0 .434.748H5a.5.5 0 0 1 0 1H1.723A1.5 1.5 0 0 1 .421 12.24l2.552-4.467zm10.89 1.463a.5.5 0 1 0-.868.496l1.716 3.004a.5.5 0 0 1-.434.748h-5.57l.647-.646a.5.5 0 1 0-.708-.707l-1.5 1.5a.498.498 0 0 0 0 .707l1.5 1.5a.5.5 0 1 0 .708-.707l-.647-.647h5.57a1.5 1.5 0 0 0 1.302-2.244l-1.716-3.004z"/>
            </svg>
        </a>
        <input class="form-control me-2" type="search" id="search-text-person" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" onclick="getStudentContent()">Search</button>
    </div>
  </div>
</nav>

<div id="person-content">
    <?php
        require_once('../content/studentContent.php');
    ?>
</div>