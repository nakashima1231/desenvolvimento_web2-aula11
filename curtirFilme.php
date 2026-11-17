<?php
require('conexao.php');
session_start();

if(isset($_SESSION['id_usuario'])) {
    if(isset($_POST['id_filme'])) {
        $id_logado = $_SESSION['id_usuario'];
        $id_filme = $_POST['id_filme'];

        $checkCurtida = "SELECT `id` FROM `curtidas` WHERE `id_filme` = '$id_filme' AND `id_usuario` = '$id_logado'";
        $stm = $conexao->prepare($checkCurtida);
        $stm->execute();
        $jaCurtiu = $stm->fetch(PDO::FETCH_ASSOC);

        if($jaCurtiu) {
            $comandoDel = "DELETE FROM `curtidas` WHERE `id_filme` = '$id_filme' AND `id_usuario` = '$id_logado'";
            $conexao->exec($comandoDel);
            echo "Curtida removida!";
        } else {
            $comandoIns = "INSERT INTO `curtidas` (`id_filme`, `id_usuario`) VALUES ('$id_filme', '$id_logado')";
            $linhas = $conexao->exec($comandoIns);

            if($linhas > 0) {
                echo "Filme curtido com sucesso!";
            } else {
                echo "Erro ao curtir o filme.";
            }
        }
    } else {
        echo "Filme não informado.";
    }
    ?>
    <br><a href="filmes.php">Voltar para os filmes</a>
    <?php
} else {
    ?>
    <a href="login.php">Fazer login</a>
    <?php
}
?>
