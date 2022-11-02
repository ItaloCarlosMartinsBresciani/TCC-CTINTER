<?php
    session_start();

    require_once('../app/php/functions.php');
    require_once('../app/db/connect.php');
    
    if(isset($_GET['key'])){
        $key = cleanString($_GET['key']);

        $query = 'SELECT token, valid_date FROM tokens WHERE token = :acessKey';
    
        $stmt = $conn->prepare($query);

        $stmt->bindValue(':acessKey', $key);

        $stmt->execute();
        $return = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        
        $validDate = date("U", $return[0]['valid_date']); 

        if(count($return) < 1) {
            header("Location: ../index.php ");
            exit();
        } else if ($validDate < date('U', time())) {       
            // Exclusão do Token

            $query = 'DELETE FROM tokens WHERE token = :token';

            $stmt = $conn->prepare($query);

            $stmt->bindValue(':token', $key);

            $stmt->execute();

            header('Location: ../index.php');
            exit();
        }
    }
    else {
        header("Location: ../index.php ");
        exit();
    }  
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Instituição de Ensino</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../public/bootstrap/bootstrap.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../public/css/style.css">
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

        <div class="w-100 rounded bg-primary" style="padding: 1em;">
            <form action="../app/php/registerUniversityLogic.php?key=<?php echo $_GET['key']; ?>" , method="POST">
                <h2 class="text-white">Diretor</h2>
                <div class="row">
                    <div class="col-lg-6 col-sm-12">
                        <label for="name-principal" class="lead fw-normal">Nome:</label>
                        <input type="text" id="name-principal" class="form-control" name="name-principal" required><br>
                        <label for="email-principal" class="lead fw-normal">Email:</label>
                        <input type="email" id="email-principal" class="form-control" name="email-principal" required><br>
                        <label for="telephone-principal" class="lead fw-normal">Telefone:</label>
                        <input type="tel" id="telephone-principal" class="form-control" name="telephone-principal" required><br>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <label for="cpf-principal" class="lead fw-normal">CPF:</label>
                        <input type="text" id="cpf-principal" class="form-control" name="cpf-principal" required><br>
                        <label for="rg-principal" class="lead fw-normal">RG:</label>
                        <input type="text" id="rg-principal" class="form-control" name="rg-principal" required><br>
                        <label for="treatment-principal" class="lead fw-normal">Tratamento:</label>
                        <input type="text" id="treatment-principal" class="form-control" placeholder="Ex: Doutor" name="treatment-principal" required><br>
                    </div> 
                </div> 
                <h2 class="text-white">Instituição de Ensino</h2>
                <div class="row">
                    <div class="col-lg-6 col-sm-12">
                        <label for="cnpj-university" class="lead fw-normal">CNPJ:</label>
                        <input type="text" id="cnpj-university" class="form-control" name="cnpj-university" required><br>
                        <label for="name-university" class="lead fw-normal">Nome:</label>
                        <input type="text" id="name-university" class="form-control" name="name-university" required><br>
                        <label for="state-registration-university" class="lead fw-normal">Inscrição estadual:</label>
                        <input type="text" id="state-registration-university" class="form-control" name="state-registration-university" required><br>
                        <label for="corporate-name-university" class="lead fw-normal">Razão social:</label>
                        <input type="text" id="corporate-name-university" class="form-control" name="corporate-name-university" required><br>
                        <label for="legal-representative-university" class="lead fw-normal">Representante legal:</label>
                        <input type="text" id="legal-representative-university" class="form-control" name="legal-representative-university" required><br>
                        <label for="activity-branch-university" class="lead fw-normal">Ramo de atividade:</label>
                        <input type="text" id="activity-branch-university" class="form-control" name="activity-branch-university" required><br>
                        <label for="address-university" class="lead fw-normal">Endereço:</label>
                        <input type="text" id="address-university" class="form-control" name="address-university" required><br>
                        <label for="home-page-university" class="lead fw-normal">Homepage:</label>
                        <input type="text" id="home-page-university" class="form-control" name="home-page-university" required><br>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <label for="district-university" class="lead fw-normal">Bairro:</label>
                        <input type="text" id="district-university" class="form-control" name="district-university" required><br>
                        <label for="cep-university" class="lead fw-normal">CEP:</label>
                        <input type="text" id="cep-university" class="form-control" name="cep-university" required><br>
                        <label for="mailbox-university" class="lead fw-normal">Caixa postal:</label>
                        <input type="text" id="mailbox-university" class="form-control" name="mailbox-university" required><br>
                        <label for="city-university" class="lead fw-normal">Cidade:</label>
                        <input type="text" id="city-university" class="form-control" name="city-university" required><br>
                        <label for='state-university' class="lead fw-normal">Estado</label>
                            <select class='form-control' name='state-university' id='state-university' required>
                                <option selected value=''>Escolha um Estado:</option>
                                <option value='AC'>AC</option>    
                                <option value='AL'>AL</option>   
                                <option value='AP'>AP</option>  
                                <option value='AM'>AM</option>
                                <option value='BA'>BA</option>
                                <option value='CE'>CE</option>
                                <option value='DF'>DF</option>
                                <option value='ES'>ES</option>
                                <option value='GO'>GO</option>
                                <option value='AM'>AM</option>
                                <option value='MT'>MT</option>
                                <option value='MS'>MS</option>
                                <option value='MG'>MG</option>
                                <option value='PA'>PA</option>
                                <option value='PB'>PB</option>
                                <option value='PR'>PR</option>
                                <option value='PE'>PE</option>
                                <option value='PI'>PI</option>
                                <option value='RJ'>RJ</option>
                                <option value='RN'>RN</option>
                                <option value='RS'>RS</option>
                                <option value='RO'>RO</option>
                                <option value='RR'>RR</option>
                                <option value='SC'>SC</option>
                                <option value='SP'>SP</option>
                                <option value='SE'>SE</option>
                                <option value='TO'>TO</option>
                            </select> <br>
                        </label>

                        <label for="telephone-university" class="lead fw-normal">Telefone:</label>
                        <input type="tel" id="telephone-university" class="form-control" name="telephone-university" required><br>
                        <label for="email-university" class="lead fw-normal">Email:</label>
                        <input type="text" id="email-university" class="form-control" name="email-university" required><br>
                    </div>
                </div>

                <input type="submit" class="btn btn-dark col-lg-3 col-12 fw-bold" value="Enviar"> 
            </form>
        </div>

         <footer class="text-center pt-5 pb-3">
             &copy; 2022 CTI - Colégio Técnico Industrial "Prof. Isaac Portal Roldán"
         </footer>
    </div>

    <!-- Bootstrap -->
    <script src="../public/bootstrap/bootstrap.min.js"></script>
</body>
</html>