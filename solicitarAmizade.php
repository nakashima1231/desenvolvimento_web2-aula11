<?php
require('conexao.php');
session_start();

if(isset($_SESSION['id_usuario'])) {
    if(isset($_POST['email_amigo'])) {
        $id_solicitante = $_SESSION['id_usuario'];
        $email_amigo = $_POST['email_amigo'];

        $comandoS = "SELECT `id` FROM `usuario` WHERE `email` = '$email_amigo'";
        $stm = $conexao->prepare($comandoS);
        $stm->execute();
        $resultado = $stm->fetch(PDO::FETCH_ASSOC);

        if(isset($resultado['id'])) {
            $id_recebedor = $resultado['id'];

            if($id_solicitante == $id_recebedor) {
                echo "Você não pode adicionar a si mesmo!";
            } else {
                // CORRIGIDO: Busca id_usuario em vez de id na tabela amigos
                $checkAmigos = "SELECT `id_usuario` FROM `amigos` WHERE `id_usuario` = '$id_solicitante' AND `id_amigo` = '$id_recebedor'";
                $stmA = $conexao->prepare($checkAmigos);
                $stmA->execute();

                $checkPendente = "SELECT `id` FROM `pendente` WHERE (`id_solicitante` = '$id_solicitante' AND `id_recebedor` = '$id_recebedor') OR (`id_solicitante` = '$id_recebedor' AND `id_recebedor` = '$id_solicitante')";
                $stmP = $conexao->prepare($checkPendente);
                $stmP->execute();

                if($stmA->fetch(PDO::FETCH_ASSOC)) {
                    echo "Vocês já são amigos!";
                } elseif($stmP->fetch(PDO::FETCH_ASSOC)) {
                    echo "Já existe uma solicitação pendente entre vocês!";
                } else {
                    $comando = "INSERT INTO `pendente` (`id_solicitante`, `id_recebedor`) VALUES ('$id_solicitante', '$id_recebedor')";
                    $linhas = $conexao->exec($comando);

                    if($linhas > 0) {
                        echo "Solicitação de amizade enviada com sucesso!";
                    } else {
                        echo "Erro ao enviar solicitação.";
                    }
                }
            }
        } else {
            echo "Usuário com esse e-mail não encontrado!";
        }
    } else {
        echo "Preencha o formulário primeiro.";
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
