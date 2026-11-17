<?php
require('conexao.php');


$email = $_POST['email'];
$senha = hash('sha256', $_POST['senha']);

$comandoS = "SELECT `id`, `nome`, `senha`, `nascimento` FROM `usuario` WHERE `email` = '$email'";
$stm2 = $conexao->prepare($comandoS);
$stm2->execute();
$resultado = $stm2->fetch(PDO::FETCH_ASSOC);

if(isset($resultado['id'])) { 
    if(hash_equals(strtolower($resultado['senha']), $senha)) {
        session_start();
        $_SESSION['id_usuario'] = $resultado['id'];
        $_SESSION['nome'] = $resultado['nome'];

        $hoje = new DateTime();
        $nascimento = new DateTime($resultado['nascimento']);
        $idade = $hoje->diff($nascimento)->y;
        $_SESSION['idade'] = $idade;

        echo "Olá {$resultado['nome']}!";
        ?>
        <br> <a href="cadastrarFilme.php">Cadastrar filme</a> <br>
        <a href="sair.php">sair</a>
        <?php
    } else {
        echo "Usuário inexistente!";
        ?>
        <a href="login.php">Fazer login</a>
        <?php
    }
} else {
    echo "Usuário inexistente!";
    ?>
    <a href="login.php">Fazer login</a>
    <?php
}
?>
