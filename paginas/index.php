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
  <?php 
      if($_SESSION['administradorLogin']) {
        echo "<div id='menu' class='menu-adm'></div>";
      } else {
        echo "<div id='menu'></div>";
      }
	  require '../camadaDados/conectar.php';
	  require '../camadaDados/tabelas.php';
	  $result = "SELECT D1.Código,D1.Sigla,D1.Nome FROM $db.$TB_DISCIPLINA D1 order by D1.Nome";
      $select = $conx->prepare($result);
      $select->execute();
	  echo "<script>var search_terms = []</script>" ;
	  foreach($select->fetchAll() as $linha_array){
		echo "<script>search_terms.push(\"".$linha_array['Código']." - ".$linha_array['Sigla']." - ".$linha_array['Nome']."\")</script>" ;
	  }
	  echo "<script>aluno = 0</script>";
	  if($_SESSION['tipoLogin'] == 2){
		  echo "<script>aluno = 1</script>";
		  $result = "SELECT A1.Curso_idCurso from $db.$TB_ALUNO A1 where A1.Usuario_idUsuario = :id";
		  $select = $conx->prepare($result);
		  $select->execute([':id'=>$_SESSION['idUsuarioLogin']]);
		  $curso = '';
		  foreach($select->fetchAll() as $linha_array){
			$curso = $linha_array['Curso_idCurso'];
			break;
		  }	  
		  $result = "SELECT D1.Código,D1.Sigla,D1.Nome FROM $db.$TB_DISCIPLINA D1 where D1.Curso_idCurso = :curso order by D1.Nome";
		  $select = $conx->prepare($result);
		  $select->execute([':curso'=>$curso]);
		  echo "<script>var search_terms2 = []</script>" ;
		  foreach($select->fetchAll() as $linha_array){
			echo "<script>search_terms2.push(\"".$linha_array['Código']." - ".$linha_array['Sigla']." - ".$linha_array['Nome']."\")</script>" ;
		  }
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
    <h1>Bem vindo(a)!</h1>
	<h2>Buscar disciplina</h2>	
	<form><input type="text" name="searchDisciplina" id="searchDisciplina" onKeyUp="showResults(this.value)" />
	<div id="result"></div>
	</form>
	<?php
	 if($_SESSION['tipoLogin'] == 2){
		echo '<input type="checkbox" id="checkDisciplinasCurso" name="checkDisciplinasCurso" checked> <label for="checkDisciplinasCurso">Buscar apenas disciplinas no meu curso</label> <br/><br/>';
	 }
	 echo "	<br/><br/><br/>";
      if($_SESSION['administradorLogin']){
        echo '<button class="button btnUsuarios btnEntidades"><a href="./Usuarios/index.php">Usuários</a></button> <br/>';
      }
	  else{
		$_SESSION['alterarProprioUsuario'] = $_SESSION['idUsuarioLogin'];
		echo '<button class="button btnUsuarios btnEntidades"><a href="./Usuarios/Alterar/php1.php">Alterar seu usuário</a></button> <br/>';
	  }
	  echo "<form id='formVisualizar' method='POST' action='./Disciplinas/Visualizar/php.php'>";
		echo '<input type="hidden" id="codigo" name="codigo" value="" />';
		echo '<input style="display:none;" type="submit" name="submit2" value="Enviar">';
	  echo "</form>";
    ?>
	
    <button class="button btnCursos btnEntidades"><a href="./Cursos/index.php">Cursos</a></button> <br/>
    <button class="button btnDisciplinas btnEntidades"><a href="./Disciplinas/index.php">Disciplinas</a></button><br/>
    <button class="button btnCriticas btnEntidades"><a href="./Criticas/index.php">Críticas sobre disciplinas</a></button> <br/>
    <div id="footer"></div>   
	<script>
	function autocompleteMatch(input) {
	  if (input == '') {
		return [];
	  }
	  var reg = new RegExp(input)
	  if(aluno){
		if(document.getElementById('checkDisciplinasCurso').checked){
		  return search_terms2.filter(function(term) {
			  if (term.match(reg)) {
			  return term;
			  }
		  })}
	  }
	  return search_terms.filter(function(term) {
		  if (term.match(reg)) {
		  return term;
		  }
	  });
	} 
	function showResults(val) {
	  res = document.getElementById("result");
	  res.innerHTML = '';
	  let list = '';
	  let terms = autocompleteMatch(val);
	  for (i=0; i<terms.length; i++) {
		list += '<li onclick="visualizar(' + terms[i].substr(0,4) + ')">' + terms[i] + '</li>';
	  }
	  res.innerHTML = '<ul>' + list + '</ul>';
	}
	function visualizar(codigo){
		var hiddenCodigo = document.getElementById('codigo')
		hiddenCodigo.value = codigo
		form = document.getElementById('formVisualizar').submit();
	}	
	</script>
</body>
</html>