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
    <h1>Consultar disciplina</h1>
    <h2>Consulta por nome possui prioridade</h2>
    <h2>Para listar todas as disciplinas, deixe os dois campos em branco</h2>
    <button class="button btnVoltar button-go-return"><span class="material-icons button-go-return">reply</span><a class="button-go-return" href="../index.php">Voltar</a></button><br/>
    <form action="php.php" method="POST">
        <label for="nome">Nome: </label><input id="nome" name="nome" type="text" placeholder="Digite o nome" maxlength="50"> <br/>
        <label for="sigla">Sigla: </label><input id="sigla" name="sigla" type="text" placeholder="Digite a sigla" maxlength="6"> <br/>
        <button type="submit" name="submit" class="button-search" value="Enviar"><span class="material-icons button-search">search</span>Pesquisar</button>
    </form>
	<br/>
    <?php
		if(isset($_SESSION['queryDisciplina1'])){
            echo "<table class='sortable'>";
            echo "<thead>";
                echo"<tr>";
				if($_SESSION['administradorLogin']){
					echo"<th >Id</th>";}
                echo"<th >Nome</th>";
                echo"<th> Descrição</th>";
                echo"<th> Código </th>";
                echo"<th> Sigla </th>";
				echo"<th> Tipo </th>";
				echo"<th> Ativa </th>";
				echo"<th> Curso </th>";
				if($_SESSION['administradorLogin']){
					echo"<th> </th>";}
                echo"</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach($_SESSION['queryDisciplina1'] as $linha_array) {
                echo "<tr>";
				if($_SESSION['administradorLogin']){
					echo "<td>". $linha_array['idDisciplina'] ."</td>";}        
                echo "<td>". $linha_array['Nome'] ."</td>";	
                echo "<td>". $linha_array['Descrição'] ."</td>";	
                echo "<td>". $linha_array['Código'] ."</td>";	
                echo "<td>". $linha_array['Sigla'] ."</td>";	
				if($linha_array['Tipo'] == 2){
				  $linha_array['Tipo'] = 'Escolha';
				}else if($linha_array['Tipo'] == 1){
				  $linha_array['Tipo'] = 'Eletiva';
				}else{
				  $linha_array['Tipo']='Obrigatória';
				}      
				echo "<td>". $linha_array['Tipo'] ."</td>";	
				echo "<td>".($linha_array['Ativa']?"Sim":"Não")."</td>";
				echo "<td>".$linha_array['NomeCurso']."</td>";
				if($_SESSION['administradorLogin']){
					echo "<td>".'<button value="Alterar" onclick="editar('.$linha_array['idDisciplina'].')" class="button-go-update"><span class="material-icons button-go-update">edit</span>Alterar</button>' ."</td>";
				}
                echo "</tr>";}
            echo  "</tbody>";
            echo "</table>";
            unset($_SESSION['queryDisciplina1']);
		}
		if($_SESSION['administradorLogin']){
			echo "<form id='formConsultarAlterar' method='POST' action='../Alterar/php1.php'>";
				echo '<input type="hidden" id="id" name="id" value="" />';
				echo '<input style="display:none;" type="submit" name="submit2" value="Enviar">';
			echo "</form>";}
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