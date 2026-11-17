<?php
require('conexao.php');

session_start();

$nome_original = $_FILES['foto']['name'];
$nome_tmp = $_FILES['foto']['tmp_name'];
$destino = "imagens/$nome_original";

if(isset($_SESSION['id_usuario'])) {

    $c = $_POST['class'];

    if($_SESSION['idade'] > $c) {
        move_uploaded_file($nome_tmp, $destino);

        $id = $_SESSION['id_usuario'];
        $n = $_POST['nome'];
        $d = $_POST['descricao'];
        $f = "$destino";
        $l = $_POST['link'];
        $c = $_POST['class'];
        $nota = $_POST['nota'];

        $comando = "INSERT INTO `filmes` (`id_usuario`, `nome`, `descricao`, `foto`, `link`, `classificacao`, `nota_usuario`) VALUES ('$id', '$n', '$d', '$f', '$l', '$c', '$nota')";
        $linhas = $conexao->exec($comando);

        if($linhas > 0) {
            echo "Filme foi cadastrado com sucesso!";
        } else {
            echo "Erro no cadastro do filme.";
        }
    } else {
        echo "Voce  nao tem idade para ver esse filme";
        ?>
        <a href="cadastrarFilme.php">Fazer login</a>
        <?php
    }
} else {

    ?>
<a href="login.php">Fazer login</a>
<?php
}
?>
