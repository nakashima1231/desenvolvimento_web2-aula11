<?php
require('conexao.php');
session_start();

if(!isset($_SESSION['id_usuario'])) {
    ?>
    <a href="login.php">Fazer login</a>
    <?php
    exit;
}

$id_logado = $_SESSION['id_usuario'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filmes Cadastrados</title>
</head>
<body>
    <h1>Filmes Cadastrados</h1>
    <a href="cadastrarFilme.php">Cadastrar Novo Filme</a> | 
    <a href="amigos.php">Meus Amigos</a> | 
    <a href="sair.php">Sair</a>
    <hr>

    <?php
    $comandoF = "SELECT filmes.*, usuario.nome AS nome_autor 
                 FROM `filmes` 
                 INNER JOIN `usuario` ON filmes.id_usuario = usuario.id";
    $stmF = $conexao->prepare($comandoF);
    $stmF->execute();
    $filmes = $stmF->fetchAll(PDO::FETCH_ASSOC);

    if(count($filmes) > 0) {
        foreach($filmes as $filme) {
            $id_filme = $filme['id'];
            $id_autor = $filme['id_usuario'];
            ?>
            <div>
                <h2><?php echo $filme['nome']; ?> (Nota: <?php echo $filme['nota_usuario']; ?>)</h2>
                <p><strong>Cadastrado por:</strong> <?php echo $filme['nome_autor']; ?></p>
                <p><strong>Classificação:</strong> <?php echo $filme['classificacao']; ?> anos</p>
                <p><strong>Descrição:</strong> <?php echo $filme['descricao']; ?></p>
                
                <?php if(!empty($filme['foto'])) { ?>
                    <img src="<?php echo $filme['foto']; ?>" alt="Capa" width="150"><br>
                <?php } ?>
                
                <?php if(!empty($filme['link'])) { ?>
                    <a href="<?php echo $filme['link']; ?>" target="_blank">Assistir / Ver Link</a><br>
                <?php } ?>

                <?php
                $comandoTotal = "SELECT COUNT(*) AS total FROM `curtidas` WHERE `id_filme` = '$id_filme'";
                $stmTotal = $conexao->prepare($comandoTotal);
                $stmTotal->execute();
                $dadosCurtidas = $stmTotal->fetch(PDO::FETCH_ASSOC);
                $total_curtidas = $dadosCurtidas['total'];

                $checkUserCurtiu = "SELECT `id` FROM `curtidas` WHERE `id_filme` = '$id_filme' AND `id_usuario` = '$id_logado'";
                $stmUser = $conexao->prepare($checkUserCurtiu);
                $stmUser->execute();
                $curtiu = $stmUser->fetch(PDO::FETCH_ASSOC);
                ?>

                <p><strong>Curtidas:</strong> <?php echo $total_curtidas; ?></p>
                <form action="curtirFilme.php" method="post">
                    <input type="hidden" name="id_filme" value="<?php echo $id_filme; ?>">
                    <input type="submit" value="<?php echo $curtiu ? 'Descurtir' : 'Curtir'; ?>">
                </form>

                <?php
                $checkAmigo = "SELECT `id_usuario` FROM `amigos` WHERE `id_usuario` = '$id_logado' AND `id_amigo` = '$id_autor'";
                $stmA = $conexao->prepare($checkAmigo);
                $stmA->execute();
                $ehAmigo = $stmA->fetch(PDO::FETCH_ASSOC);

                if($ehAmigo || $id_autor == $id_logado) {
                    ?>
                    <h3>Comentários:</h3>
                    <?php
                    $comandoC = "SELECT comentarios.comentario, usuario.nome 
                                 FROM `comentarios` 
                                 INNER JOIN `usuario` ON comentarios.id_usuario = usuario.id 
                                 WHERE comentarios.id_filme = '$id_filme'";
                    $stmC = $conexao->prepare($comandoC);
                    $stmC->execute();
                    $comentarios = $stmC->fetchAll(PDO::FETCH_ASSOC);

                    if(count($comentarios) > 0) {
                        foreach($comentarios as $c) {
                            echo "<strong>{$c['nome']}:</strong> {$c['comentario']} <br>";
                        }
                    } else {
                        echo "Nenhum comentário ainda.<br>";
                    }

                    if($ehAmigo) {
                        ?>
                        <br>
                        <form action="comentarFilme.php" method="post">
                            <input type="hidden" name="id_filme" value="<?php echo $id_filme; ?>">
                            <input type="text" name="comentario" placeholder="Escreva um comentário..." required>
                            <input type="submit" value="Comentar">
                        </form>
                        <?php
                    } else {
                        echo "<p><em>(Este filme foi cadastrado por você. Apenas seus amigos podem comentar aqui.)</em></p>";
                    }
                } else {
                    echo "<p><em>(Adicione {$filme['nome_autor']} como amigo para ver e fazer comentários neste filme)</em></p>";
                }
                ?>
            </div>
            <hr>
            <?php
        }
    } else {
        echo "Nenhum filme cadastrado ainda.";
    }
    ?>
</body>
</html>
