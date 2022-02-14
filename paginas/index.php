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
    <h1>Acessar</h1>
    <?php
      if($_SESSION['administradorLogin']){
        echo '<button class="button btnUsuarios btnEntidades"><a href="./Usuarios/index.php">Usuarios</a></button> <br/>';
      }
    ?>
    <button class="button btnCursos btnEntidades"><a href="./Cursos/index.php">Cursos</a></button> <br/>
    <button class="button btnDisciplinas btnEntidades"><a href="./Disciplinas/index.php">Disciplinas</a></button><br/>
    <button class="button btnCursosDisciplinas btnEntidades"><a href="./CursosDisciplinas/index.php">Cursos e suas disciplinas</a></button> <br/>
    <button class="button btnProfessoresDisciplinas btnEntidades"><a href="./ProfessoresDisciplinas/index.php">Disciplinas e seus professores</a></button> <br/>
    <button class="button btnCriticas btnEntidades"><a href="./Criticas/index.php">Criticas sobre disciplinas</a></button> <br/>
    <div id="footer"></div>    
</body>
</html>