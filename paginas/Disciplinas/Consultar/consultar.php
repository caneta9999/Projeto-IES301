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
    <h1>Consultar disciplina</h1>
    <h2>Consulta por nome possui prioridade</h2>
    <h2>Para listar todas as disciplinas, deixe os dois campos em branco</h2>
    <button class="button btnVoltar"><a href="../index.php">Voltar</a></button><br/>
    <form action="php.php" method="POST">
        <label for="nome">Nome: </label><input id="nome" name="nome" type="text" placeholder="Digite o nome" maxlength="50"> <br/>
        <label for="sigla">Sigla: </label><input id="sigla" name="sigla" type="text" placeholder="Digite a sigla" maxlength="6"> <br/>
        <input type="submit" name="submit" value="Enviar">
    </form>
    <?php
		if(isset($_SESSION['queryDisciplina1'])){
            echo "<table>";
            echo "<thead>";
                echo"<tr>";
                echo"<th >Id</th>";
                echo"<th >Nome</th>";
                echo"<th> Descrição</th>";
                echo"<th> Código </th>";
                echo"<th> Sigla </th>";
                echo"</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach($_SESSION['queryDisciplina1'] as $linha_array) {
                echo "<tr>";
                echo "<td>". $linha_array['idDisciplina'] ."</td>";        
                echo "<td>". $linha_array['Nome'] ."</td>";	
                echo "<td>". $linha_array['Descrição'] ."</td>";	
                echo "<td>". $linha_array['Código'] ."</td>";	
                echo "<td>". $linha_array['Sigla'] ."</td>";	 
                echo "</tr>";}
            echo  "</tbody>";
            echo "</table>";
            unset($_SESSION['queryDisciplina1']);
		}
		?>
    <div id="push"></div>
    <div id="footer"></div>    
</body>
</html>