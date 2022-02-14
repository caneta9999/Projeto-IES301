<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']) || $_SESSION['administradorLogin']!=1)
{
  header('location:../../Login/index.php');
}?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel ="stylesheet" href="../../../css/css.css"/>

    <script type="module" src="../../../js/componentes.js"></script>

    <title>Projeto IES301</title>
</head>
<body>
    <div id="navbar"></div>
    <h1>Cadastrar disciplina</h1>
    <button class="button btnVoltar"><a href="../index.php">Voltar</a></button><br/>
    <form action="php.php" method="POST">
        <label for="nome">Nome: </label><input id="nome" name="nome" type="text" placeholder="Digite o nome" maxlength="50" required> <br/>
        <label for="descricao"> Descrição: </label><textarea rows="5" cols="30" id="descricao" name="descricao" placeholder="Digite a descrição da matéria" required maxlength="500" ></textarea> <br/>
        <label for="codigo">Código: </label><input id="codigo" name="codigo" placeholder="Código da disciplina" type="number" min="1" max="9999" required> <br/>
        <label for="sigla">Sigla: </label><input id="sigla" name="sigla" placeholder="Sigla da disciplina" type="text" maxlength="6" required> <br/>                     
        <input type="submit" name="submit" value="Enviar">
    </form>
    <div id="footer"></div>    
</body>
</html>