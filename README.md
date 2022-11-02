# Estagio-CTI

## Observações

### Funções

```PHP

function cleanString($string) {
    return filter_var(htmlspecialchars(trim($string), FILTER_SANITIZE_STRING));
}

```

Utilizeer está função para realizar a sanitização de `inputs` realizados pelo usuário, destá forma evitaremos ataques de SQL injection e quebra de segurança. 

```PHP

function codeId($id) {
    return hexdec($id * 1000);
}

function decodeId($id) {
    return dechex($id) / 1000;
}

```

Ao passar o id de algum usuário, empresa ou universidade por `GET` é interessante a utilização destas funções apenas para não expor ao usuário o id final e dificultar a manipulação desta chave de acesso. 

### Pastas 

As pastas devem ser bem divididas, segundo o nível de segurança e importância, facilitando a organização da aplicação. 

- *App:*
arquivos que correspondem à lógica da aplicação e manipulação do banco de dados

- *Js:*
Arquivos JavaScript para manipulação da `DOM` e execução de requisições assincronas para a aplicação.

- *Public:*
Arquivos de imagem, icones, CSS e estilização em geral.

- *Views:*
Telas da aplicação em geral. Está pasta deve conter todas as páginas da aplicação exceto o `index.php`. Está pasta deve conter outras subpastas mais especializadas para determinado nível de acesso de usuário (Exemplo: `Admin`, que encapsula as páginas que o admin deve ter acesso). 

### Medidas de Segurança

Todas as páginas devem ser validadas segundo o nível de acesso do usuário e seu id. O código abaixo corresponde a validação de administrador e pode ser encontrado em todas as páginas que o mesmo deve ter acesso.

```PHP

<?php
    session_start();

    if(!isset($_SESSION['isAuth']) || $_SESSION['idUser'] != -1){
        header("Location: ../../index.php ");
        exit();
    }

?>

```

Note que a validação ocorre segundo o `ID` do usuário, que deve ser validado e devidademente autenticado em [verifyIntegrity.php](https://github.com/GabrielNicolim/Estagio-UNESP/blob/main/TUC21/app/php/google/verifyIntegrity.php). 

*OBS: Poderia ser criado um arquivo `.php` contendo as diversas validações de autenticações de usuário em seus diferentes níveis, transformadas em funções. Desta forma, o código acima, por exemplo, poderia ser subistuído por:*

```PHP

<?php

    require_once('../../app/php/validateFunctions.php');

    adminValidate(); // Função de validação de administrador

?>

```

*Toda a lógica vista anteriormente poderia ser transformada em uma única função.*

### Organização

Utilizem o Git e GitHub para a organização. É importante que reuniões sejam realizadas e cada pessoa se organize segundo o projeto. A tabela do projeto pode ser manipula e utilizada para que cada pessoa possa assumir tarefas e realizar anotações dos problemas encontrados. 

![image](https://user-images.githubusercontent.com/69210720/130323951-b98cd0a7-4fee-4282-98f0-757ed33917eb.png)

## Aplicativos

Esses links devem ser utilizados para realizar a instalação dos aplicativos utilizados para o desenvolvimento do projeto em conjunto.

- [Visual Studio Code](https://code.visualstudio.com/download)
- [GitHub Desktop](https://desktop.github.com/)
- [Xampp](https://www.apachefriends.org/download.html)
- [Pg Admin](https://www.pgadmin.org/download/)

## Instalação 

Os arquivos `envEmail.php`, `google.php`, `admin.php` e `env.php` devem ser alterados conforme a pessoa e a necessidade de instalação do projeto.

- *google.php:*
    Dados utilizados pela API do Google para realizar a conexão e envio de dados do usuário para a aplicação. Algunas dados também devem ser alterados no botão de conexão com o Google econtrado em `index.php`.

- *admin.php:*
    Contém o email que será o administrador da aplicação.

- *env.php:*
    Contém os dados de conexão com o banco de dados da aplicação. Deverá ser alterado no futuro para se adequar ao servidor da UNESP. 

- *envEmail.php:*
    Deve ser criado segundo o email utilizado para o envio de emails pela aplicação. Contem os dados utilizados pelo PHP Mailer.

