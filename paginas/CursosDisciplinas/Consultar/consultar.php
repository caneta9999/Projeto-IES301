<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']))
{
  header('location:../../Login/index.php');
}
require '../../../camadaDados/conectar.php';
require '../../../camadaDados/tabelas.php';
$result = "SELECT idCurso,Nome FROM $db.$TB_CURSO order by Nome";
$select = $conx->prepare($result);
$select->execute();
$_SESSION['queryCursosDisciplinasCursos2'] = $select->fetchAll();
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
    <h1>Consultar curso e suas disciplinas</h1>
    <button class="button btnVoltar"><a href="../index.php">Voltar</a></button><br/>
    <form action="php.php" method="POST">
        <?php
            echo '<label id="labelCurso" for="cursoSelect"> Selecione o curso: </label>';
            echo '<select id="cursoSelect" onchange="mudaCurso()">';
			$idSelect1 = '';
            foreach($_SESSION['queryCursosDisciplinasCursos2'] as $linha_array) {
				$idCurso = $linha_array['idCurso'];
                if($idSelect1 == ''){
					$idSelect1 = $linha_array['idCurso'];}				
                echo '<option value='."'$idCurso'".">".$linha_array['Nome']."</option>";
            } 
            foreach($_SESSION['queryCursosDisciplinasCursos2'] as $linha_array) {
                echo '<input type="hidden" id="curso" name="curso" value='."'$idSelect1'"."/>";
                break;
            }            
            echo '</select>';
            echo '<br/>';
		?>
        <input type="submit" name="submit" value="Enviar">
    </form>
    <script>
        function mudaCurso(){
            document.getElementById('curso').value = document.getElementById('cursoSelect').value;
        }
    </script>
    <?php
		if(isset($_SESSION['queryCursoDisciplina1'])){
            $curso = '';
            foreach($_SESSION['queryCursoDisciplina1'] as $linha_array) {
              $curso = $linha_array['CursoNome'];
              break;
            }
			if($curso){
				echo "<h2>".$curso."</h2>";
				echo "<table class='sortable'>";
				echo "<thead>";
					echo"<tr>";
					if(isset($_SESSION['administradorLogin'])){
					  echo "<th>Id</th>";
					}
					echo"<th >Nome da disciplina</th>";
					echo"<th >Tipo</th>";
					echo"<th >Ativa</th>";
					echo"</tr>";
				echo "</thead>";
				echo "<tbody>";
				foreach($_SESSION['queryCursoDisciplina1'] as $linha_array) {
					echo "<tr>";
					if(isset($_SESSION['administradorLogin'])){
					  echo "<th>".$linha_array['CursoDisciplinaId']."</th>";
					}
					echo "<td>". $linha_array['nome'] ."</td>";
					if($linha_array['tipo'] == 2){
					  $linha_array['tipo'] = 'Escolha';
					}else if($linha_array['tipo'] == 1){
					  $linha_array['tipo'] = 'Eletiva';
					}else{
					  $linha_array['tipo']='Obrigatória';
					}      
					echo "<td>". $linha_array['tipo'] ."</td>";	
					echo "<td>".($linha_array['ativa']?"Sim":"Não")."</td>";
					echo "</tr>";}
				echo  "</tbody>";
				echo "</table>";				
			}else{
				echo "<p class='mensagemErro'>".'Entretanto, não há disciplinas cadastradas no curso!'."</p>";
			}
            unset($_SESSION['queryCursoDisciplina1']);
		}
		?>
    <div id="push"></div>
    <div id="footer"></div>    
</body>
</html>