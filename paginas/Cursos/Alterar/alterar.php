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
    <h1>Alterar curso</h1>
    <button class="button btnVoltar button-go-return"><span class="material-icons button-go-return">reply</span><a class="button-go-return" href="../index.php">Voltar</a></button><br/>
    <form action="php1.php" method="POST">
        <label for="id">Id: </label><input id="id" name="id" type="number" placeholder="Digite o id" min="1" max="99999999999" required> <br/>
		<button type="submit" name="submit" class="button-search" value="Enviar"><span class="material-icons button-search">search</span>Pesquisar</button>
    </form>
    <hr/>
    <?php
        if(isset($_SESSION['queryCurso2'])){
            $nome = 'Curso';
            $id = -1;
            foreach($_SESSION['queryCurso2'] as $linha_array){
                $nome = $linha_array['Nome'];
                $id = $linha_array['idCurso'];
                $_SESSION['idAlteracao'] = $id;
            }
            echo '<form method="POST" action="php2.php">';
            echo '<label for="id">Id:</label> <input value='.$id.' id="id" name="id" type="number" placeholder="Id do curso" min="1" max="99999999999" required readonly="readonly"/> <br/>';
            echo '<label for="nome">Nome:</label> <input value='."'$nome'".' id="nome" name="nome" type="text" placeholder="Nome do curso" maxlength="50" required /> <br/>';
			echo '<button name="submit" onclick="return confirmarSubmit('."'Você realmente deseja excluir esse registro? Não será possível reverter sua ação!'".')" type="submit" class="button-delete" value="Excluir" /><span class="material-icons button-delete">delete</span>Excluir</button>';				
			echo '<button name="submit" onclick="return confirmarSubmit('."'Você realmente deseja cancelar a alteração? Não será possível reverter sua ação!'".')" type="submit" value="Cancelar" class="button-cancel"><span class="material-icons button-cancel">close</span>Cancelar</button>';
			echo '<button name="submit" type="submit" class="button-confirm" value="Alterar" /><span class="material-icons button-confirm">done</span>Confirmar</button>';
            echo '</form>';
            unset($_SESSION['queryCurso2']);}
    ?>
	<div id="push"></div>
    <div id="footer"></div>    
	<script>
		function confirmarSubmit(mensagem){
			var confirmar=confirm(mensagem);
			return confirmar? true:false;
		}
	</script>
</body>
</html>