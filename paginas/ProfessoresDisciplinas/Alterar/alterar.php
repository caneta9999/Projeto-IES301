<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']) || $_SESSION['administradorLogin']!=1)
{
  header('location:../../Login/index.php');
}?>
<?php
    require '../../../camadaDados/conectar.php';
    require '../../../camadaDados/tabelas.php';
    $result = "SELECT Nome FROM $db.$TB_CURSO";
    $select = $conx->prepare($result);
    $select->execute();
    $_SESSION['queryCursosDisciplinasCursos1'] = $select->fetchAll();
?>
<?php
    require '../../../camadaDados/conectar.php';
    require '../../../camadaDados/tabelas.php';
    $result = "SELECT Nome FROM $db.$TB_DISCIPLINA";
    $select = $conx->prepare($result);
    $select->execute();
    $_SESSION['queryCursosDisciplinasDisciplinas1'] = $select->fetchAll();
?>
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
    <h1>Alterar disciplina e professor</h1>
    <button class="button btnVoltar"><a href="../index.php">Voltar</a></button><br/>
    <form action="php1.php" method="POST">
        <label for="id">Id: </label><input id="id" name="id" type="number" placeholder="Digite o id do vínculo" min="1" max="99999999999" required> <br/>
        <input type="submit" name="submit" value="Enviar">
    </form>
    <hr/>
    <?php
        if(isset($_SESSION['queryProfessorDisciplina2'])){
            $id = -1;
            $disciplina = 'Disciplina';
            $professor = 'Professor';
            $periodo = 0;
            $dataInicial = '0000-00-00';
            $dataFinal = '0000-00-00';
            foreach($_SESSION['queryProfessorDisciplina2'] as $linha_array){
                $disciplina = $linha_array['DisciplinaNome'];
                $professor = $linha_array['Nome'];
                $periodo = $linha_array['Periodo'];
                $dataInicial = $linha_array['dataInicial'];
                $dataFinal = $linha_array['dataFinal'];
                $diaSemana = $linha_array['diaSemana'];
                $id = $linha_array['idProfessorDisciplina'];
                $_SESSION['idAlteracao5'] = $id;
            }
            echo '<form method="POST" action="php2.php">';
            echo '<label for="id">Id:</label> <input value='.$id.' id="id" name="id" type="number" min="1" max="99999999999" required readonly="readonly"/> <br/>';
            echo '<label for="disciplina">Disciplina:</label><input type="text" id="disciplina" readonly="readonly" name="disciplina" value='."'$disciplina'"."/>";           
            echo '<br/>';
            echo '<label for="professor">Professor:</label><input type="text" id="professor" readonly="readonly" name="professor" value='."'$professor'"."/>";         
            echo '<br/>';
            echo '<label for="periodoSelect"> Período: </label>';
            echo '<select id="periodoSelect" onchange="mudaPeriodo()">';
            if($periodo == 0){
                echo '<option value="0" selected> Manhã </option>';
                echo '<option value="1"> Tarde </option>';
                echo '<option value="2"> Noite </option>';}
            else if($periodo == 1){
                echo '<option value="0"> Manhã </option>';
                echo '<option value="1" selected> Tarde </option>';
                echo '<option value="2"> Noite </option>';                
            }else{
                echo '<option value="0"> Manhã </option>';
                echo '<option value="1"> Tarde </option>';
                echo '<option value="2" selected> Noite </option>';                 
            }
            echo '</select><br/>';
            echo '<input id="periodo" name="periodo" type="hidden" placeholder="" value='."'$periodo'".' maxlength="1">';
            echo '<br/>';         
            echo '<label for="dataInicial">Data Inicial: </label> <input type="date" id="dataInicial" value='."'$dataInicial'".' name="dataInicial" checked required> <br/>';
            echo '<label for="dataFinal">Data Final: </label> <input type="date" id="dataFinal" value='."'$dataFinal'".' name="dataFinal" checked> <br/>';
            echo '<label for="diaSemana">Dia da Semana: </label> <input type="number" id="diaSemana" value='."'$diaSemana'".' name="diaSemana" type="number" min="2" max="7" required > <br/>';
            echo '<input name="submit" type="submit" value="Excluir" />';
            echo '<input name="submit" type="submit" value="Alterar" />';
            echo '</form>';
            unset($_SESSION['queryProfessorDisciplina2']);}
    ?>
    <script>
        function mudaPeriodo(){
            document.getElementById('periodo').value = document.getElementById('periodoSelect').value;
        }
    </script>
    <div id="footer"></div>    
</body>
</html>