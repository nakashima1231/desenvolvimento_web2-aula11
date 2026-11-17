<?php
require('conexao.php');
session_start();

if(isset($_SESSION['id_usuario'])) {
    $id_logado = $_SESSION['id_usuario'];
    $id_pendente = $_GET['id_pendente'];

    $comandoS = "SELECT `id_solicitante`, `id_recebedor` FROM `pendente` WHERE `id` = '$id_pendente' AND `id_recebedor` = '$id_logado'";
    $stm = $conexao->prepare($comandoS);
    $stm->execute();
    $pedido = $stm->fetch(PDO::FETCH_ASSOC);

    if(isset($pedido['id_solicitante'])) {
        $id_solicitante = $pedido['id_solicitante'];
        $id_recebedor = $pedido['id_recebedor'];

        $comando1 = "INSERT INTO `amigos` (`id_usuario`, `id_amigo`) VALUES ('$id_recebedor', '$id_solicitante')";
        $comando2 = "INSERT INTO `amigos` (`id_usuario`, `id_amigo`) VALUES ('$id_solicitante', '$id_recebedor')";
        
        $linhas1 = $conexao->exec($comando1);
        $linhas2 = $conexao->exec($comando2);

        if($linhas1 > 0 && $linhas2 > 0) {
            $comandoDel = "DELETE FROM `pendente` WHERE `id` = '$id_pendente'";
            $conexao->exec($comandoDel);

            echo "Amizade aprovada com sucesso!";
        } else {
            echo "Erro ao aprovar amizade.";
        }
    } else {
        echo "Solicitação não encontrada.";
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
