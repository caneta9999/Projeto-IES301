<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']))
{
  header('location:../../Login/index.php');
}
require '../../../camadaDados/conectar.php';
require '../../../camadaDados/tabelas.php';
$result = "SELECT idDisciplina,Nome,Código FROM $db.$TB_DISCIPLINA order by Nome";
$select = $conx->prepare($result);
$select->execute();
$_SESSION['queryProfessoresDisciplinasDisciplinas2'] = $select->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel ="stylesheet" href="../../../css/css.css"/>

    <script type="module" src="../../../js/componentes.js"></script>
	
	<script src="../../../js/sorttable.js"></script>

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
    <h1>Consultar disciplina e seus professores</h1>
    <button class="button btnVoltar button-go-return"><span class="material-icons button-go-return">reply</span><a class="button-go-return" href="../index.php">Voltar</a></button><br/>
    <form action="php.php" method="POST">
            <?php
			echo '<label id="labelDisciplina" for="disciplinaSelect"> Selecione a disciplina: </label>';
            echo '<select id="disciplinaSelect" onchange="mudaDisciplina()">';
			$idSelect1 = '';
            foreach($_SESSION['queryProfessoresDisciplinasDisciplinas2'] as $linha_array) {
				$idDisciplina = $linha_array['idDisciplina'];
				if($idSelect1 == ''){
					$idSelect1 = $linha_array['idDisciplina'];
				}
                echo '<option value='."'$idDisciplina'".">".$linha_array['Código']." - ".$linha_array['Nome']."</option>";
            } 
            foreach($_SESSION['queryProfessoresDisciplinasDisciplinas2'] as $linha_array) {
                echo '<input type="hidden" id="disciplina" name="disciplina" value='."'$idSelect1'"."/>";
                break;
            }            
            echo '</select>';
            echo '<br/>';
			?>
			<script>
				function mudaDisciplina(){
					document.getElementById('disciplina').value = document.getElementById('disciplinaSelect').value;
				}
			</script>
        <button type="submit" name="submit" class="button-search" value="Enviar"><span class="material-icons button-search">search</span>Pesquisar</button>
    </form>
    <?php
		if(isset($_SESSION['queryProfessorDisciplina1'])){
            $disciplina = '';
            foreach($_SESSION['queryProfessorDisciplina1'] as $linha_array) {
              $disciplina = $linha_array['DisciplinaNome'];
              break;
            }
			if($disciplina != ''){
				echo "<h2>"."$disciplina"."</h2>";
				echo "<table class='sortable'>";
				echo "<thead>";
					echo"<tr>";
					if(isset($_SESSION['administradorLogin'])){
					  echo "<th>Id</th>";
					}
					echo"<th >Nome do professor</th>";
					echo"<th >Periodo</th>";
					echo"<th >Data Inicial</th>";
					echo"<th >Data Final</th>";
					echo"<th >Dia da Semana</th>";
					if($_SESSION['administradorLogin']){
						echo"<th ></th>";}
					echo"</tr>";
				echo "</thead>";
				echo "<tbody>";
				foreach($_SESSION['queryProfessorDisciplina1'] as $linha_array) {
					echo "<tr>";
					if(isset($_SESSION['administradorLogin'])){
					  echo "<th>".$linha_array['idProfessorDisciplina']."</th>";
					}
					echo "<td>". $linha_array['Nome'] ."</td>";
					if($linha_array['Periodo'] == 2){
					  $linha_array['Periodo'] = 'Noite';
					}else if($linha_array['Periodo'] == 1){
					  $linha_array['Periodo'] = 'Tarde';
					}else{
					  $linha_array['Periodo']='Manhã';
					}      
					echo "<td>". $linha_array['Periodo'] ."</td>";	
					echo "<td>".$linha_array['dataInicial']."</td>";
					echo "<td>".($linha_array['dataFinal']!='0000-00-00'?$linha_array['dataFinal']:"Não finalizada!")."</td>";
					$diaSemana = $linha_array['diaSemana'];
					if($diaSemana == 2){
					  $diaSemana = 'Segunda-feira';
					}else if($diaSemana == 3){
					  $diaSemana = 'Terça-feira';
					}else if($diaSemana == 4){
					  $diaSemana = 'Quarta-feira';
					}else if($diaSemana == 5){
					  $diaSemana = 'Quinta-feira';
					}else if($diaSemana == 6){
					  $diaSemana = 'Sexta-feira';
					}else{
					  $diaSemana = 'Sabado';
					}
					echo "<td>".$diaSemana."</td>";
					if($_SESSION['administradorLogin']){
						echo "<td>".'<button value="Alterar" onclick="editar('.$linha_array['idProfessorDisciplina'].')" class="button-go-update"><span class="material-icons button-go-update">edit</span>Alterar</button>' ."</td>";
					}
					echo "</tr>";}
				echo  "</tbody>";
				echo "</table>";}
			else{
				echo "<p class='mensagemErro'>"."Entretanto, não há professores associados à disciplina!"."</p>";
			}
            unset($_SESSION['queryProfessorDisciplina1']);
		}
		echo "<form id='formConsultarAlterar' method='POST' action='../Alterar/php1.php'>";
		echo '<input type="hidden" id="id" name="id" value="" />';
		echo '<input style="display:none;" type="submit" name="submit2" value="Enviar">';
		echo "</form>";
		?>
    <div id="push"></div>
    <div id="footer"></div> 
	<script>
		function editar(id){
			var hiddenId = document.getElementById('id')
			hiddenId.value = id
			form = document.getElementById('formConsultarAlterar').submit();
		}
	</script>	
</body>
</html>