<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']))
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
    <h2>Os professores serão mostradas caso só haja uma disciplina com o nome passado</h2>
    <button class="button btnVoltar"><a href="../index.php">Voltar</a></button><br/>
    <form action="php.php" method="POST">
        <label for="nome">Nome: </label><input id="nome" name="nome" type="text" placeholder="Digite o nome" maxlength="50"> <br/>
        <input type="submit" name="submit" value="Enviar">
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
				echo "<table>";
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
					echo "</tr>";}
				echo  "</tbody>";
				echo "</table>";}
			else{
				echo "<p class='mensagemErro'>"."Não há professores associados à disciplina!"."</p>";
			}
            unset($_SESSION['queryProfessorDisciplina1']);
		}
		?>
    <div id="push"></div>
    <div id="footer"></div>    
</body>
</html>