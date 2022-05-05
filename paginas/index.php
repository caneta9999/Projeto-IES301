<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']))
{
  header('location:./Login/index.php');
}?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel ="stylesheet" href="../css/css.css"/>

    <script type="module" src="../js/componentes.js"></script>

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
    <h1>Acessar</h1>
    <?php
      if($_SESSION['administradorLogin']){
        echo '<button class="button btnUsuarios btnEntidades"><a href="./Usuarios/index.php">Usuários</a></button> <br/>';
      }
	  else{
		$_SESSION['alterarProprioUsuario'] = $_SESSION['idUsuarioLogin'];
		echo '<button class="button btnUsuarios btnEntidades"><a href="./Usuarios/Alterar/php1.php">Alterar seu usuário</a></button> <br/>';
	  }
    ?>
    <button class="button btnCursos btnEntidades"><a href="./Cursos/index.php">Cursos</a></button> <br/>
    <button class="button btnDisciplinas btnEntidades"><a href="./Disciplinas/index.php">Disciplinas</a></button><br/>
    <button class="button btnCursosDisciplinas btnEntidades"><a href="./CursosDisciplinas/index.php">Cursos e suas disciplinas</a></button> <br/>
    <button class="button btnProfessoresDisciplinas btnEntidades"><a href="./ProfessoresDisciplinas/index.php">Disciplinas e seus professores</a></button> <br/>
    <button class="button btnCriticas btnEntidades"><a href="./Criticas/index.php">Críticas sobre disciplinas</a></button> <br/>
    <div id="footer"></div>    
</body>
</html>