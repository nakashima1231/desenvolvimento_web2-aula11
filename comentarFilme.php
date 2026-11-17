<?php
require('conexao.php');
session_start();

if(isset($_SESSION['id_usuario'])) {
    if(isset($_POST['id_filme']) && isset($_POST['comentario'])) {
        $id_logado = $_SESSION['id_usuario'];
        $id_filme = $_POST['id_filme'];
        $comentario = $_POST['comentario'];

        $comandoF = "SELECT `id_usuario` FROM `filmes` WHERE `id` = '$id_filme'";
        $stmF = $conexao->prepare($comandoF);
        $stmF->execute();
        $filme = $stmF->fetch(PDO::FETCH_ASSOC);

        if(isset($filme['id_usuario'])) {
            $id_autor = $filme['id_usuario'];

            $checkAmigo = "SELECT `id_usuario` FROM `amigos` WHERE `id_usuario` = '$id_logado' AND `id_amigo` = '$id_autor'";
            $stmA = $conexao->prepare($checkAmigo);
            $stmA->execute();

            if($stmA->fetch(PDO::FETCH_ASSOC)) {
                $comando = "INSERT INTO `comentarios` (`id_filme`, `id_usuario`, `comentario`) VALUES ('$id_filme', '$id_logado', '$comentario')";
                $linhas = $conexao->exec($comando);

                if($linhas > 0) {
                    echo "Comentário enviado com sucesso!";
                } else {
                    echo "Erro ao salvar comentário.";
                }
            } else {
                echo "Você só pode comentar em filmes cadastrados pelos seus amigos!";
            }
        } else {
            echo "Filme não encontrado.";
        }
    } else {
        echo "Preencha o comentário antes de enviar.";
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
