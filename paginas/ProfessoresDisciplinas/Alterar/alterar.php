<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']) || $_SESSION['administradorLogin']!=1)
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
	<link rel="stylesheet" href="../../../css/bootstrap-4.6.1-dist/bootstrap-4.6.1-dist/css/bootstrap.css">
	<link rel ="stylesheet" href="../../../css/bootstrap-select-1.13.14/bootstrap-select-1.13.14/dist/css/bootstrap-select.min.css"/>
	<script src="../../../js/jquery-3.6.0.min.js"></script>
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
    <button class="button btnVoltar button-go-return"><span class="material-icons button-go-return">reply</span><a class="button-go-return" href="../index.php">Voltar</a></button><br/>
    <form action="php1.php" method="POST">
        <label for="id">Id: </label><input id="id" name="id" type="number" placeholder="Digite o id do vínculo" min="1" max="99999999999" required> <br/>
		<button type="submit" name="submit" class="button-search" value="Enviar"><span class="material-icons button-search">search</span>Pesquisar</button>
    </form>
    <hr/>
    <?php
        if(isset($_SESSION['queryProfessorDisciplina2'])){
            $id = -1;
            $disciplina = 'Disciplina';
            $professor = 'Professor';
            $periodo = 0;
            $dataInicial = '2100-01-01';
            $dataFinal = '2100-01-01';
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
            echo '<select id="periodoSelect" class="selectpicker" data-size="10" data-live-search="true" onchange="mudaPeriodo()">';
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
            echo '<button name="submit" onclick="return confirmarSubmit('."'Você realmente deseja excluir esse registro? Não será possível reverter sua ação!'".')" type="submit" class="button-delete" value="Excluir" /><span class="material-icons button-delete">delete</span>Excluir</button>';				
			echo '<button name="submit" onclick="return confirmarSubmit('."'Você realmente deseja cancelar a alteração? Não será possível reverter sua ação!'".')" type="submit" value="Cancelar" class="button-cancel"><span class="material-icons button-cancel">close</span>Cancelar</button>';
			echo '<button name="submit" type="submit" class="button-confirm" value="Alterar" /><span class="material-icons button-confirm">done</span>Confirmar</button>';
            echo '</form>';
            unset($_SESSION['queryProfessorDisciplina2']);}
    ?>
    <script>
        function mudaPeriodo(){
            document.getElementById('periodo').value = document.getElementById('periodoSelect').value;
        }
		function confirmarSubmit(mensagem){
			var confirmar=confirm(mensagem);
			return confirmar? true:false
		}
    </script>
	<div id="push"></div>
    <div id="footer"></div>
	<script src="../../../js/node_modules/popper.js/dist/umd/popper.js"></script>
	<script src="../../../css/bootstrap-4.6.1-dist/bootstrap-4.6.1-dist/js/bootstrap.min.js"></script>
	<script src="../../../css/bootstrap-select-1.13.14/bootstrap-select-1.13.14/dist/js/bootstrap-select.min.js"></script>	    
</body>
</html>