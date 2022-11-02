<?php
//a parte que o programa vai pegar a senha
$senha = 'teste'
password_hash($senha, PASSWORD_DEFAULT)
//depois disso ele vai mandar a senha para o banco de dados
//depois, para verificar a senha, tem dois jeitos


$senha2 ='teste' //senha2 é a q a gente tá comparando com a outra
if(password_hash($senha2), PASSWORD_DEFAULT == $senha) //lembrando q aqui a senha 1 já tá criptografado
//a gente tá criptografando a segunda e vendo se é igual a primeira criptografada
//esse jeito é um pouco mais longo

$hash = '$2y$07$BCryptRequires22Chrcte/VlQH0piJtjXl.0t1XkA8pw9dMXTpOq'; //isso aqui já é a senha criptografada
password_verify('teste', $hash)
//esse jeito aqui tá comparando direto, isso torna ele um pouco mais simples
//lembrando q ele retorna um bool

?>