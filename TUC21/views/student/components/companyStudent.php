<?php 
    session_start();
    if(!isset($_SESSION['isAuth']) || $_SESSION['access_level'] != 1){
        echo "<script>alert('Usuário não reconhecido.');</script>";
        header("Location: ../../../index.php ");
        exit();
    }
    
    
?>

<h2>Empresas</h2>

<p>
    Página das informações das empresas conveniadas 
</p>

<nav class="navbar navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand">Pesquisa</a>
    <div class="d-flex">
        <input class="form-control me-2" type="search" id="search-text-company" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" onclick="getCompanyContent()">Search</button>
    </div>
  </div>
</nav>

<div id="company-content">
    <?php
        require_once('../content/companyContent.php');
    ?>
</div>
