<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']))
{
  header('location:../Login/index.php');
}?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel ="stylesheet" href="../../css/css.css"/>

    <script type="module" src="../../js/componentes.js"></script>

    <title>Projeto IES301</title>
</head>
<body>
    <div id="navbar"></div>
    <?php
			if(isset($_SESSION['mensagemFinalizacao'])){
				echo "<p class='mensagemFinalizacao'>".$_SESSION['mensagemFinalizacao']."</p>";
				unset($_SESSION['mensagemFinalizacao']);
			}
			if(isset($_SESSION['mensagemErro'])){
				echo "<p class='mensagemErro'>".$_SESSION['mensagemErro']."</p>";
				unset($_SESSION['mensagemErro']);
			}
	?>
    <h1>Cursos e suas disciplinas</h1>
    <button class="button btnVoltar"><a href="../index.php">Voltar</a></button><br/>
    <?php
    if($_SESSION['administradorLogin']){
        echo '<button class="button btnCadastrar" id="btnCadastrarCursosDisciplinas"><a href="./Cadastrar/cadastrar.php">Cadastrar disciplina em curso</a></button> <br/>';
        echo '<button class="button btnAlterar" id="btnAlterarCursosDisciplinas"><a href="./Alterar/alterar.php">Alterar disciplina em curso</a></button> <br/>';}
    ?>
    <button class="button btnConsultar" id="btnConsultarCursosDisciplinas"><a href="./Consultar/consultar.php">Consultar curso e suas disciplinas</a></button>
    <div id="footer"></div>    
</body>
</html>