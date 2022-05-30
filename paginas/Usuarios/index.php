<?php
session_start();
if(isset($_SESSION['idUsuarioLogin']) && $_SESSION['administradorLogin']!=1){
	header('location:../index.php');
}
else if(!isset($_SESSION['idUsuarioLogin']) || $_SESSION['administradorLogin']!=1)
{
  header('location:../Login/index.php');
}
?>
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
			if(isset($_SESSION['mensagemFinalizacao']) && $_SESSION['administradorLogin']==1){
				echo "<p class='mensagemFinalizacao'>".$_SESSION['mensagemFinalizacao']."</p>";
				unset($_SESSION['mensagemFinalizacao']);
			}
			if(isset($_SESSION['mensagemErro']) && $_SESSION['administradorLogin']==1){
				echo "<p class='mensagemErro'>".$_SESSION['mensagemErro']."</p>";
				unset($_SESSION['mensagemErro']);
			}
	?>
    <h1>Usuários</h1>
    <button class="button btnVoltar button-go-return"><span class="material-icons button-go-return">reply</span><a class="button-go-return" href="../index.php">Voltar</a></button><br/>
    <button class="button btnCadastrar" id="btnCadastrarUsuarios"><a href="./Cadastrar/cadastrar.php">Cadastrar</a></button> <br/>
    <button class="button btnAlterar" id="btnAlterarUsuarios"><a href="./Alterar/alterar.php">Alterar</a></button> <br/>
    <button class="button btnConsultar" id="btnConsultarUsuarios"><a href="./Consultar/consultar.php">Consultar</a></button>
    <div id="footer"></div>    
</body>
</html>