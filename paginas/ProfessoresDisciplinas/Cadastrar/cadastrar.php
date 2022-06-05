<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']) || $_SESSION['administradorLogin']!=1)
{
  header('location:../../Login/index.php');
}?>
<?php
    require '../../../camadaDados/conectar.php';
    require '../../../camadaDados/tabelas.php';
    $result = "SELECT idUsuario,Nome FROM $db.$TB_USUARIO Where Tipo=1";
    $select = $conx->prepare($result);
    $select->execute();
    $_SESSION['queryProfessoresDisciplinasProfessores1'] = $select->fetchAll();
?>
<?php
    $result = "SELECT C1.Nome 'NomeCurso',D1.idDisciplina,D1.Código,D1.Nome 'NomeDisciplina',D1.Sigla FROM $db.$TB_DISCIPLINA D1 inner join $db.$TB_CURSO C1 on C1.idCurso = D1.Curso_idCurso";
    $select = $conx->prepare($result);
    $select->execute();
    $_SESSION['queryProfessoresDisciplinasDisciplinas1'] = $select->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../../../css/bootstrap-4.6.1-dist/bootstrap-4.6.1-dist/css/bootstrap.css">
	<link rel ="stylesheet" href="../../../css/bootstrap-select-1.13.14/bootstrap-select-1.13.14/dist/css/bootstrap-select.min.css"/>
	<script src="../../../js/jquery-3.6.0.min.js"></script>
    <link rel ="stylesheet" href="../../../css/css.css"/>

    <script type="module" src="../../../js/componentes.js"></script>

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
    <h1>Cadastrar professor em disciplina</h1>
    <button class="button btnVoltar button-go-return"><span class="material-icons button-go-return">reply</span><a class="button-go-return" href="../index.php">Voltar</a></button><br/>
    <form action="php.php" method="POST">
        <?php
            echo '<label id="labelProfessor" for="professorSelect"> Professor: </label>';
            echo '<select id="professorSelect" class="selectpicker" data-size="10" data-live-search="true" onchange="mudaProfessor()">';
			$idSelect1 = '';
            foreach($_SESSION['queryProfessoresDisciplinasProfessores1'] as $linha_array) {
                $nome = $linha_array['Nome'];
				if($idSelect1 == ''){
					$idSelect1 = $linha_array['idUsuario'];
				}
                $id = $linha_array['idUsuario'];
                echo '<option value='."'$id'".">".$nome."</option>";
            } 
            foreach($_SESSION['queryProfessoresDisciplinasProfessores1'] as $linha_array) {
                echo '<input type="hidden" id="professor" name="professor" value='."'$idSelect1'"."/>";
                break;
            }            
            echo '</select>';
            echo '<br/><br/>';

            echo '<label id="labelDisciplina" for="disciplinaSelect"> Disciplina: </label>';
            echo '<select id="disciplinaSelect" class="selectpicker" data-size="10" data-live-search="true" onchange="mudaDisciplina()">';
			$idSelect2 = '';
            foreach($_SESSION['queryProfessoresDisciplinasDisciplinas1'] as $linha_array) {
				if($idSelect2 == ''){
					$idSelect2 = $linha_array['idDisciplina'];
				}
                echo '<option value='.$linha_array['idDisciplina']." >"."{$linha_array['NomeDisciplina']} ({$linha_array['Sigla']} : {$linha_array['Código']}) - {$linha_array['NomeCurso']}"."</option>";
            } 
            foreach($_SESSION['queryProfessoresDisciplinasDisciplinas1'] as $linha_array) {
                echo '<input type="hidden" id="disciplina" name="disciplina" value='."'$idSelect2'"."/>";
                break;
            }            
            echo '</select>';
            echo '<br/><br/>';
        ?>
        <label for="periodoSelect"> Período: </label>
        <select id="periodoSelect" class="selectpicker" data-size="10" data-live-search="true" onchange="mudaPeriodo()">
            <option value="0" selected> Manhã </option>
            <option value="1"> Tarde </option>
            <option value="2"> Noite </option>
        </select><br/><br/>
        <input id="periodo" name="periodo" type="hidden" placeholder="" value=0 maxlength="15"><br/> 
        <label for="dataInicial">Data Inicial: </label> <input type="date" id="dataInicial" name="dataInicial" checked required> <br/>
        <label for="dataFinal">Data Final: </label> <input type="date" id="dataFinal" name="dataFinal" checked> <br/>
        <label for="diaSemana">Dia da Semana: </label> <input type="number" id="diaSemana" name="diaSemana" type="number" min="2" max="7" placeholder="2-7" required > <br/>
        <button type="submit" name="submit" class="button-create" value="Enviar"><span class="material-icons button-create">add_circle</span>Cadastrar</button>
    </form>
    <script>
        function mudaProfessor(){
            document.getElementById('professor').value = document.getElementById('professorSelect').value;
        }
        function mudaDisciplina(){
            document.getElementById('disciplina').value = document.getElementById('disciplinaSelect').value;
        }
        function mudaPeriodo(){
            document.getElementById('periodo').value = document.getElementById('periodoSelect').value;
        }
    </script>
	<div id="push"></div>
    <div id="footer"></div> 
	<script src="../../../js/node_modules/popper.js/dist/umd/popper.js"></script>
	<script src="../../../css/bootstrap-4.6.1-dist/bootstrap-4.6.1-dist/js/bootstrap.min.js"></script>
	<script src="../../../css/bootstrap-select-1.13.14/bootstrap-select-1.13.14/dist/js/bootstrap-select.min.js"></script>	
</body>
</html>