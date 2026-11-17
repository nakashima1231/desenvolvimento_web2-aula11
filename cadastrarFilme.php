<?php 
    require('conexao.php');
    session_start();
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de filme</title>
</head>
<body>
    <h1>Cadastro de filme</h1>
    <!-- $_POST[] -->
    <form action="salva_filme.php" method='post' enctype="multipart/form-data">
        <label for="">Nome:</label>
        <input type="text" name='nome'><br>

        <label for="">Descricao:</label>
        <input type="text" name='descricao'><br>

        <label for="">Foto: </label>
        <input type="file" name="foto"> <br>

        <label for="">Link:</label>
        <input type="text" name='link'><br>

        <label for="">Classificacao: </label>
        <input type="number" name="class"> <br>

        <label for="">Nota: </label>
        <input type="number" name="nota" min=0 max=10> <br>


        <input type="submit">
    </form>
</body>
</html>