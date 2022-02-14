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
    <h1>Consultar usuario</h1>
    <h2>Consulta por nome possui prioridade</h2>
    <button class="button btnVoltar"><a href="../index.php">Voltar</a></button><br/>
    <form action="php.php" method="POST">
        <label for="nome">Nome: </label><input id="nome" name="nome" type="text" placeholder="Digite o nome" maxlength="100"> <br/>
        <label for="matricula">Matricula: </label><input matricula="matricula" name="matricula" type="number" placeholder="Digite a matricula" min="1" max="99999999999"> <br/>
        <input type="submit" name="submit" value="Enviar">
    </form>
    <?php
        
		if(isset($_SESSION['queryUsuario1'])){
            echo "<h1>Usuarios</h1>";
            echo "<table>";
            echo "<thead>";
                echo "<tr>";
                echo "<th >Id</th>";
                echo "<th >Login</th>";
                echo "<th >Nome</th>";
                echo "<th >Administrador</th>";
                echo "<th >Cpf</th>";
                echo "<th >Tipo</th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach($_SESSION['queryUsuario1'] as $linha_array) {
                echo "<tr>";
                echo "<td>". $linha_array['idUsuario'] ."</td>";        
                echo "<td>". $linha_array['Login'] ."</td>";	
                echo "<td>". $linha_array['Nome'] ."</td>";        
                if($linha_array['Administrador']){
                    echo "<td>". "Sim"."</td>";
                }else{
                    echo "<td>". "Não"."</td>";
                }      	
                echo "<td>". $linha_array['Cpf'] ."</td>";        
                if($linha_array['Tipo'] == 0){
                    echo "<td>". 'Nenhum' ."</td>";
                }else if($linha_array['Tipo'] == 1){
                    echo "<td>". 'Professor' ."</td>";
                }else{
                    echo "<td>". 'Aluno' ."</td>";
                }
                echo "</tr>";}
            echo  "</tbody>";
            echo "</table>";
            unset($_SESSION['queryUsuario1']);
		}
        if(isset($_SESSION['queryUsuario2'])){
            echo "<h1>Alunos</h1>";
            echo "<table>";
            echo "<thead>";
                echo "<tr>";
                echo "<th >Id</th>";
                echo "<th >Login</th>";
                echo "<th >Nome</th>";
                echo "<th >Administrador</th>";
                echo "<th >Cpf</th>";
                echo "<th >Tipo</th>";
                echo "<th>Matricula</th>";
                echo "<th>Curso</th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach($_SESSION['queryUsuario2'] as $linha_array) {
                echo "<tr>";
                echo "<td>". $linha_array['idUsuario'] ."</td>";        
                echo "<td>". $linha_array['Login'] ."</td>";	
                echo "<td>". $linha_array['Nome'] ."</td>";        
                if($linha_array['Administrador']){
                    echo "<td>". "Sim"."</td>";
                }else{
                    echo "<td>". "Não"."</td>";
                }  	
                echo "<td>". $linha_array['Cpf'] ."</td>";
                if($linha_array['Tipo'] == 0){
                    echo "<td>". 'Nenhum' ."</td>";
                }else if($linha_array['Tipo'] == 1){
                    echo "<td>". 'Professor' ."</td>";
                }else{
                    echo "<td>". 'Aluno' ."</td>";
                }
                echo "<td>". $linha_array['Matricula'] ."</td>";
                echo "<td>". $linha_array['CursoNome'] ."</td>";
                echo "</tr>";}
            echo  "</tbody>";
            echo "</table>";
            unset($_SESSION['queryUsuario2']);
		}
		?>
    <div id="push"></div>
    <div id="footer"></div>    
</body>
</html>