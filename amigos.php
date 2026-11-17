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
    <title>Sistema de Amizade</title>
</head>
<body>
    <h1>Olá, <?php echo $_SESSION['nome']; ?>!</h1>

    <h2>Adicionar Amigo</h2>
    <form action="solicitarAmizade.php" method="post">
        <label for="">E-mail do amigo:</label>
        <input type="email" name="email_amigo" required>
        <input type="submit" value="Enviar Solicitação">
    </form>

    <hr>

    <h2>Solicitações Pendentes</h2>
    <?php
    $comandoP = "SELECT pendente.id, usuario.nome, usuario.email 
                 FROM `pendente` 
                 INNER JOIN `usuario` ON pendente.id_solicitante = usuario.id 
                 WHERE pendente.id_recebedor = '$id_logado'";
    $stmP = $conexao->prepare($comandoP);
    $stmP->execute();
    $pendentes = $stmP->fetchAll(PDO::FETCH_ASSOC);

    if(count($pendentes) > 0) {
        foreach($pendentes as $p) {
            echo "Pedido de: {$p['nome']} ({$p['email']}) ";
            ?>
            <a href="aprovarAmizade.php?id_pendente=<?php echo $p['id']; ?>">Aceitar</a> | 
            <a href="recusarAmizade.php?id_pendente=<?php echo $p['id']; ?>">Recusar</a><br>
            <?php
        }
    } else {
        echo "Nenhuma solicitação pendente.";
    }
    ?>

    <hr>

    <h2>Meus Amigos</h2>
    <?php
    $comandoA = "SELECT usuario.nome, usuario.email 
                 FROM `amigos` 
                 INNER JOIN `usuario` ON amigos.id_amigo = usuario.id 
                 WHERE amigos.id_usuario = '$id_logado'";
    $stmA = $conexao->prepare($comandoA);
    $stmA->execute();
    $listaAmigos = $stmA->fetchAll(PDO::FETCH_ASSOC);

    if(count($listaAmigos) > 0) {
        foreach($listaAmigos as $amigo) {
            echo "- {$amigo['nome']} ({$amigo['email']}) <br>";
        }
    } else {
        echo "Você ainda não tem amigos adicionados.";
    }
    ?>
</body>
</html>
