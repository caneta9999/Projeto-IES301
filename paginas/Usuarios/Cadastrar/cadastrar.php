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
    $_SESSION['queryPessoaCursos1'] = $select->fetchAll();
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
    <h1>Cadastrar usuário</h1>
<button class="button btnVoltar button-go-return"><span class="material-icons button-go-return">reply</span><a class="button-go-return" href="../index.php">Voltar</a></button><br/>
    <form action="php.php" method="POST">
        <label for="login">Login: </label><input id="login" name="login" type="email" placeholder="Digite o email" minlength="1" maxlength="100" required> <br/>
        <label for="senha">Senha: </label><input id="senha" name="senha" type="password" placeholder="Digite a senha" minlength="8" maxlength="50" required> <br/>
        <label for="nome">Nome: </label><input id="nome" name="nome" type="text" placeholder="Digite o nome" maxlength="100" required> <br/>
        <input type="checkbox" style="opacity:0;" id="administrador" name="administrador"> <label for="administrador" style="opacity:0;">Administrador</label> <br/>
        <label for="cpf">CPF: </label><input onkeypress="$(this).mask('000.000.000-00');" id="cpf" name="cpf" type="text" placeholder="000.000.000-00" required> <br/>
        <label for="tipoSelect"> Tipo de usuário: </label>
        <select id="tipoSelect" class="selectpicker" data-size="10" data-live-search="true" onchange="mudaTipo()">
            <option value="Nenhum"> Nenhum </option>
            <option value="Professor"> Professor </option>
            <option value="Aluno" selected> Aluno </option>
        </select><br/><br/>
        <input id="tipo" name="tipo" type="hidden" placeholder="" value="Aluno" maxlength="10">
        <?php
            echo '<label id="labelCurso" for="cursoSelect"> Curso do usuário: </label>';
            echo '<select id="cursoSelect" class="selectpicker" data-size="10" data-live-search="true" onchange="mudaCurso()">';
			$nomeSelect1 = '';
            foreach($_SESSION['queryPessoaCursos1'] as $linha_array) {
				$nome = $linha_array['Nome'];
				if($nomeSelect1 == ''){
					$nomeSelect1 = $linha_array['Nome'];
				}
                echo '<option value='."'$nome'".">".$nome."</option>";
            } 
            foreach($_SESSION['queryPessoaCursos1'] as $linha_array) {
                echo '<input type="hidden" id="curso" name="curso" value='."'$nomeSelect1'"."/>";
                break;
            }            
            echo '</select>';
            echo '<br/><br/>';
            echo '<label id="labelMatricula" for="matricula">Matricula: </label><input id="matricula" name="matricula" type="text" placeholder="Digite a matricula" min="1" max="99999999"> <br/>'
        ?>
        <button type="submit" name="submit" class="button-create" value="Enviar"><span class="material-icons button-create">add_circle</span>Cadastrar</button>
    </form>
    <script>
        function mudaTipo(){
           var select = document.getElementById('tipoSelect').value;
           document.getElementById('tipo').value = select;
           if(select!='Aluno'){
				document.getElementById("administrador").style.opacity = 1;
				document.querySelector("label[for=administrador]").style.opacity = 1;
				document.getElementsByClassName("dropdown-toggle")[1].style.visibility = "hidden"; 
                document.getElementById("cursoSelect").style.visibility = "hidden";
                document.getElementById("matricula").style.visibility = "hidden";
                document.getElementById("labelCurso").style.visibility = "hidden";
                document.getElementById("labelMatricula").style.visibility = "hidden";
           }
           else{
				document.getElementById("administrador").style.opacity = 0;
				document.querySelector("label[for=administrador]").style.opacity = 0;
				document.getElementsByClassName("btn dropdown-toggle btn-light")[1].style.visibility = "visible";
                document.getElementById("cursoSelect").style.visibility = "visible";
                document.getElementById("matricula").style.visibility = "visible"; 
                document.getElementById("labelCurso").style.visibility = "visible";
                document.getElementById("labelMatricula").style.visibility = "visible";             
           }
        }
        function mudaCurso(){
            document.getElementById('curso').value = document.getElementById('cursoSelect').value;
        }
    </script>
	<script src="../../../js/node_modules/popper.js/dist/umd/popper.js"></script>
	<script src="../../../css/bootstrap-4.6.1-dist/bootstrap-4.6.1-dist/js/bootstrap.min.js"></script>
	<script src="../../../css/bootstrap-select-1.13.14/bootstrap-select-1.13.14/dist/js/bootstrap-select.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <div id="footer"></div>    
</body>
</html>