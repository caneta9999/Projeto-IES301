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
    <?php 
      if($_SESSION['administradorLogin']) {
        echo "<div id='menu' class='menu-adm'></div>";
      } else {
        echo "<div id='menu'></div>";
      }
    ?>
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
    <h1>Disciplinas e seus professores</h1>
    <button class="button btnVoltar"><a href="../index.php">Voltar</a></button><br/>
    <?php
    if($_SESSION['administradorLogin']){
        echo '<button class="button btnCadastrar" id="btnCadastrarProfessoresDisciplinas"><a href="./Cadastrar/cadastrar.php">Cadastrar professor em disciplina</a></button> <br/>';
        echo '<button class="button btnAlterar" id="btnAlterarProfessoresDisciplinas"><a href="./Alterar/alterar.php">Alterar disciplina e professor</a></button> <br/>';}
    ?>
    <button class="button btnConsultar" id="btnConsultarProfessoresDisciplinas"><a href="./Consultar/consultar.php">Consultar disciplina e seus professores</a></button>
    <div id="footer"></div>    
</body>
</html>