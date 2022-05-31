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
	
	<script src="../js/sorttable.js"></script>
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
	  $result = "SELECT D1.Código,D1.Sigla,D1.Nome,C1.Nome 'CursoNome' FROM $db.$TB_DISCIPLINA D1 inner join $db.$TB_CURSO C1 on D1.Curso_idCurso = C1.idCurso order by D1.Nome";
      $select = $conx->prepare($result);
      $select->execute();
	  echo "<script>var search_terms = []</script>" ;
	  foreach($select->fetchAll() as $linha_array){
		echo "<script>search_terms.push(\"".$linha_array['Nome']." (".$linha_array['Sigla']." : ".$linha_array['Código'].") - ".$linha_array['CursoNome']."\")</script>" ;
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
			echo "<script>search_terms2.push(\"".$linha_array['Nome']." (".$linha_array['Sigla']." : ".$linha_array['Código'].")\")</script>" ;
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
	<?php
	if($_SESSION['tipoLogin'] == 2){
		echo '<h2>Buscar disciplina</h2>';	
		echo '<form><input class="search-discipline" type="text" name="searchDisciplina" id="searchDisciplina" placeholder="Pesquisar..." onKeyUp="showResults(this.value)" />';
		echo '<div class="search-result" id="result"></div>';
		echo '<input type="checkbox" id="checkDisciplinasCurso" name="checkDisciplinasCurso" checked> <label for="checkDisciplinasCurso">Buscar apenas disciplinas no meu curso</label> <br/><br/>';
		echo '</form>';
		echo "	<br/><br/><br/>";}
	  echo "<form id='formVisualizar' method='POST' action='./Disciplinas/Visualizar/php.php'>";
		echo '<input type="hidden" id="codigo" name="codigo" value="" />';
		echo '<input style="display:none;" type="submit" name="submit2" value="Enviar">';
	  echo "</form>";
    if($_SESSION['tipoLogin'] == 2){
			$result = "SELECT C1.idCritica,C1.Data,D1.Sigla,U2.Nome,C1.NotaDisciplina,C1.Descrição FROM $db.$TB_CRITICA C1 inner join $db.$TB_PROFESSORDISCIPLINA PD1 on PD1.idProfessorDisciplina = C1.ProfessorDisciplina_idProfessorDisciplina inner join $db.$TB_DISCIPLINA D1 on PD1.Disciplina_idDisciplina = D1.idDisciplina inner join $db.$TB_PROFESSOR P1 on P1.idProfessor = PD1.Professor_idProfessor inner join $db.$TB_USUARIO U2 on U2.idUsuario = P1.Usuario_idUsuario inner join $db.$TB_ALUNO A1 on A1.idAluno = C1.Aluno_idAluno inner join $db.$TB_USUARIO U1 on A1.Usuario_idUsuario = U1.idUsuario where U1.idUsuario = :id LIMIT 9";
			$select = $conx->prepare($result);
			$select->execute([':id'=>$_SESSION['idUsuarioLogin']]);
            echo "<h2>Suas últimas críticas</h2>";
			echo "<table class='sortable'>";
            echo "<thead>";
                echo"<tr>";
				echo"<th>Data</th>";
                echo"<th>Disciplina</th>";
				echo"<th>Professor</th>";
                echo"<th>Nota da disciplina</th>";
                echo"<th>Comentário</th>";
				echo"<th></th>";
                echo"</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach($select->fetchAll() as $linha_array) {
                echo "<tr>";
				echo "<td>".$linha_array['Data']."</td>";
				echo "<td>".$linha_array['Sigla']."</td>";
				echo "<td>".$linha_array['Nome']."</td>";
				echo "<td>".$linha_array['NotaDisciplina']."</td>";
				echo "<td>".$linha_array['Descrição']."</td>";
				echo "<td>".'<button value="Alterar" onclick="editar('.$linha_array['idCritica'].')" class="button-go-update"><span class="material-icons button-go-update">edit</span></button>' ."</td>";
                echo "</tr>";}
            echo  "</tbody>";
            echo "</table>";
			echo "<form id='formConsultarAlterar' method='POST' action='./Criticas/Alterar/php1.php'>";
				echo '<input type="hidden" id="id2" name="id2" value="" />';
				echo '<input style="display:none;" type="submit" name="submit2" value="Enviar">';
			echo "</form><br/>";	
			echo '<button class="button btnConsultar" id="btnConsultarCriticas"><a href="./Criticas/Consultar/consultar.php">Ver mais críticas</a></button> <br/>';
	}else if($_SESSION['tipoLogin'] == 1){
			$result = "SELECT C1.Data,D1.Sigla,C1.NotaDisciplina, C1.Elogios,C1.Criticas,C1.Descrição FROM $db.$TB_CRITICA C1 inner join $db.$TB_PROFESSORDISCIPLINA PD1 on C1.ProfessorDisciplina_idProfessorDisciplina = PD1.idProfessorDisciplina inner join $db.$TB_DISCIPLINA D1 on D1.idDisciplina = PD1.Disciplina_idDisciplina inner join $db.$TB_PROFESSOR P1 on PD1.Professor_idProfessor = P1.idProfessor inner join $db.$TB_USUARIO U1 on U1.idUsuario = P1.Usuario_idUsuario where U1.idUsuario = :id LIMIT 9";
			$select = $conx->prepare($result);
			$select->execute([':id'=>$_SESSION['idUsuarioLogin']]);
            echo "<h2>Últimas críticas recebidas</h2>";
			echo "<table class='sortable'>";
            echo "<thead>";
                echo"<tr>";
				echo"<th>Data</th>";
                echo"<th>Disciplina</th>";
                echo"<th>Nota da disciplina</th>";
				echo"<th>Elogios</th>";
				echo"<th>Críticas</th>";
                echo"<th>Comentário</th>";
                echo"</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach($select->fetchAll() as $linha_array) {
                echo "<tr>";
				echo "<td>".$linha_array['Data']."</td>";
				echo "<td>".$linha_array['Sigla']."</td>";
				echo "<td>".$linha_array['NotaDisciplina']."</td>";
				$elogios = explode('-', $linha_array['Elogios']);
                $elogiosFinal = "";
                foreach($elogios as $elogio){
                  if($elogio != "Nenhum"){
                    $elogiosFinal = $elogio."<br/>".$elogiosFinal;
                  }
                }
                echo "<td>".$elogiosFinal."</td>";
                $criticas = explode('-', $linha_array['Criticas']);
                $criticasFinal = "";
                foreach($criticas as $criticas){
                  if($criticas != "Nenhum"){
                    $criticasFinal = $criticas."<br/>".$criticasFinal;
                  }
                }
                echo "<td>".$criticasFinal."</td>";
				echo "<td>".$linha_array['Descrição']."</td>";
                echo "</tr>";}
            echo  "</tbody>";
            echo "</table> <br/>";	
			echo '<button class="button btnConsultar" id="btnConsultarCriticas"><a href="./Criticas/ConsultarDisciplina/consultar.php">Ver mais críticas</a></button> <br/>';			
	}
	else if($_SESSION['administradorLogin']){
			$result = "SELECT C1.Data,U1.Nome 'NomeAluno', D1.Sigla,U2.Nome 'NomeProfessor',C1.NotaDisciplina,C1.Descrição FROM $db.$TB_CRITICA C1 inner join $db.$TB_PROFESSORDISCIPLINA PD1 on PD1.idProfessorDisciplina = C1.ProfessorDisciplina_idProfessorDisciplina inner join $db.$TB_DISCIPLINA D1 on PD1.Disciplina_idDisciplina = D1.idDisciplina inner join $db.$TB_PROFESSOR P1 on P1.idProfessor = PD1.Professor_idProfessor inner join $db.$TB_USUARIO U2 on U2.idUsuario = P1.Usuario_idUsuario inner join $db.$TB_ALUNO A1 on A1.idAluno = C1.Aluno_idAluno inner join $db.$TB_USUARIO U1 on A1.Usuario_idUsuario = U1.idUsuario order by C1.Data DESC LIMIT 9";
			$select = $conx->prepare($result);
			$select->execute();
			echo '<div id="divHomepageAdministradorTituloEstatistica">';
            echo "<h2>Últimas críticas cadastradas no sistema</h2>";
			echo '<form action="./Criticas/Estatisticas/php.php" method="POST">';
			echo '<button type="submit" name="submit" class="button-go-statics" value="Consultar estatisticas gerais"><span class="material-icons button-go-statics">bar_chart</span>Consultar Estatísticas Gerais</button>';
			echo '</form>';
			echo '</div>';
			echo "<table class='sortable'>";
            echo "<thead>";
                echo"<tr>";
				echo"<th>Data</th>";
				echo"<th>Aluno</th>";
                echo"<th>Disciplina</th>";
				echo"<th>Professor</th>";
                echo"<th>Nota da disciplina</th>";
                echo"<th>Comentário</th>";
                echo"</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach($select->fetchAll() as $linha_array) {
                echo "<tr>";
				echo "<td>".$linha_array['Data']."</td>";
				echo "<td>".$linha_array['NomeAluno']."</td>";
				echo "<td>".$linha_array['Sigla']."</td>";
				echo "<td>".$linha_array['NomeProfessor']."</td>";
				echo "<td>".$linha_array['NotaDisciplina']."</td>";
				echo "<td>".$linha_array['Descrição']."</td>";
                echo "</tr>";}
            echo  "</tbody>";
            echo "</table> <br/>";		
		echo '<button class="button btnConsultar" id="btnConsultarCriticas"><a href="./Criticas/ConsultarDisciplina/consultar.php">Ver mais críticas</a></button> <br/>';	
	}
	?>
	
    <div id="footer"></div>   
	<script>
	function editar(id){
		var hiddenId = document.getElementById('id2')
		hiddenId.value = id
		form = document.getElementById('formConsultarAlterar').submit();
	}
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
		list += '<li onclick="visualizar(' + terms[i].split(" : ")[1].substr(0,4) + ')"><b>' + terms[i] + '</b></li>';
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