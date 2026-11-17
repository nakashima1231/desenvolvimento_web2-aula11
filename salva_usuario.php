<?php
require('conexao.php');

// print_r($_POST);
$email = $_POST['email'];
$senha = $_POST['senha'];
$nome = $_POST['nome'];
$nasc = $_POST['nascimento'];

$senha = hash('sha256', $senha);

$comando = "INSERT INTO `usuario` (`email`, `nome`, `senha`, `nascimento`) VALUES ('$email', '$nome', '$senha', '$nasc')";

$linhas = $conexao->exec($comando);

if($linhas > 0) {
    echo "Usuário cadastrado com sucesso!";
} else {
    echo "Erro no cadastro.";
}

?>