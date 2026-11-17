<?php
require('conexao.php');
session_start();

if(isset($_SESSION['id_usuario'])) {
    $id_logado = $_SESSION['id_usuario'];
    $id_pendente = $_GET['id_pendente'];

    $comandoDel = "DELETE FROM `pendente` WHERE `id` = '$id_pendente' AND `id_recebedor` = '$id_logado'";
    $linhas = $conexao->exec($comandoDel);

    if($linhas > 0) {
        echo "Solicitação recusada.";
    } else {
        echo "Erro ao recusar solicitação.";
    }
    ?>
    <br><a href="amigos.php">Voltar</a>
    <?php
} else {
    ?>
    <a href="login.php">Fazer login</a>
    <?php
}
?>
