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
    <h1>Alterar disciplina</h1>
    <button class="button btnVoltar button-go-return"><span class="material-icons button-go-return">reply</span><a class="button-go-return" href="../index.php">Voltar</a></button><br/>
    <form action="php1.php" method="POST">
        <label for="id">Id: </label><input id="id" name="id" type="number" placeholder="Digite o id" min="1" max="99999999999" required> <br/>
        <button type="submit" name="submit" class="button-search" value="Enviar"><span class="material-icons button-search">search</span>Pesquisar</button>
    </form>
    <hr/>
    <?php
        if(isset($_SESSION['queryDisciplina2'])){
            $nome = 'Disciplina';
            $id = -1;
            $sigla = 'AAA000';
            $codigo = 1111;
            $descricao = 'Disciplina...';
			$tipo = 0;
            $ativa = 0;
			$curso = 0;
            foreach($_SESSION['queryDisciplina2'] as $linha_array){
                $nome = $linha_array['Nome'];
                $id = $linha_array['idDisciplina'];
                $sigla = $linha_array['Sigla'];
                $codigo = $linha_array['Código'];
				$tipo = $linha_array['Tipo'];
                $ativa = $linha_array['Ativa'];
                $descricao = $linha_array['Descrição'];
				$curso = $linha_array['NomeCurso'];
                $_SESSION['idAlteracao3'] = $id;
            }
            echo '<form method="POST" action="php2.php">';
            echo '<label for="id">Id:</label> <input value='.$id.' id="id" name="id" type="number" placeholder="Id da disciplina" min="1" max="99999999999" required readonly="readonly"/> <br/>';
            echo '<label for="nome">Nome:</label> <input value='."'$nome'".' id="nome" name="nome" type="text" placeholder="Nome da disciplina" maxlength="50" required /> <br/>';
            echo '<label for="descricao"> Descrição: </label><textarea rows="5" cols="30" id="descricao" name="descricao" placeholder="Digite a descrição da matéria" required maxlength="7200" >'."$descricao".'</textarea> <br/>';
            echo '<label for="codigo">Código: </label><input value='."'$codigo'".'id="codigo" name="codigo" placeholder="AAA000" type="number" min="1" max="9999" required> <br/>';
            echo '<label for="sigla">Sigla: </label><input value='."'$sigla'".'id="sigla" name="sigla" placeholder="Sigla da disciplina" type="text" maxlength="6" required> <br/>';        
            echo '<label for="tipoSelect"> Tipo: </label>';
            echo '<select class="selectpicker" data-size="10" data-live-search="true" id="cursoSelect" id="tipoSelect" onchange="mudaTipo()">';
            if($tipo == 0){
                echo '<option value="0" selected> Obrigatória </option>';
                echo '<option value="1"> Eletiva </option>';
                echo '<option value="2"> Escolha </option>';}
            else if($tipo == 1){
                echo '<option value="0"> Obrigatória </option>';
                echo '<option value="1" selected> Eletiva </option>';
                echo '<option value="2"> Escolha </option>';                
            }else{
                echo '<option value="0"> Obrigatória </option>';
                echo '<option value="1"> Eletiva </option>';
                echo '<option value="2" selected> Escolha </option>';                 
            }
			echo '<input id="tipo" name="tipo" type="hidden" placeholder="" value='."'$tipo'".'>';
			echo '<br/><br/>';
			echo '<label for="curso">Curso:</label><input type="text" id="curso" readonly="readonly" name="curso" value='."'$curso'"."/>";
			echo '<br/>';
			echo '<button name="submit" onclick="return confirmarSubmit('."'Você realmente deseja excluir esse registro? Não será possível reverter sua ação!'".')" type="submit" class="button-delete" value="Excluir" /><span class="material-icons button-delete">delete</span>Excluir</button>';				
			echo '<button name="submit" onclick="return confirmarSubmit('."'Você realmente deseja cancelar a alteração? Não será possível reverter sua ação!'".')" type="submit" value="Cancelar" class="button-cancel"><span class="material-icons button-cancel">close</span>Cancelar</button>';
			echo '<button name="submit" type="submit" class="button-confirm" value="Alterar" /><span class="material-icons button-confirm">done</span>Confirmar</button>';
            echo '</form>';
            unset($_SESSION['queryDisciplina2']);}
    ?>
	<script>
		function mudaTipo(){
			document.getElementById('tipo').value = document.getElementById('tipoSelect').value;
		}
		function confirmarSubmit(mensagem){
			var confirmar=confirm(mensagem);
			return confirmar? true:false
		}
    </script>
	<script src="../../../js/node_modules/popper.js/dist/umd/popper.js"></script>
	<script src="../../../css/bootstrap-4.6.1-dist/bootstrap-4.6.1-dist/js/bootstrap.min.js"></script>
	<script src="../../../css/bootstrap-select-1.13.14/bootstrap-select-1.13.14/dist/js/bootstrap-select.min.js"></script>
    <div id="push"></div>
	<div id="footer"></div>    
</body>
</html>