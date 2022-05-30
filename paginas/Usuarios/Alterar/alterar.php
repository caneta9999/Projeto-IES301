<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']))
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
    <h1>Alterar usuário</h1>
	<?php
		if($_SESSION['administradorLogin']){
			echo '<button class="button btnVoltar button-go-return"><span class="material-icons button-go-return">reply</span><a class="button-go-return" href="../index.php">Voltar</a></button><br/>';
			echo '<form action="php1.php" method="POST">';
			echo '<label for="id">Id: </label><input id="id" name="id" type="number" placeholder="Digite o id" min="1" max="99999999999" required> <br/>';
			echo '<input type="submit" name="submit" value="Enviar">';
			echo '</form>';
			echo '<hr/>';
		}
		else{
			echo '<button class="button btnVoltar"><a href="../../index.php">Voltar</a></button><br/>';
		}
        if(isset($_SESSION['queryUsuario3'])){
            $nome = 'André';
            $id = -1;
            $senha = 'senha';
			if($_SESSION['administradorLogin']){
				$cpf = 0;
				$login = 'login';
				$administrador = 0;
				$tipo = 0;
				$curso = 0;
				$matricula = 0;
				$nomeCursoSelecionado = 0;
				$ativo = 0;}
            foreach($_SESSION['queryUsuario3'] as $linha_array){
                $nome = $linha_array['Nome'];
                $id = $linha_array['idUsuario'];
                $senha = $linha_array['Senha'];
				if($_SESSION['administradorLogin']){
					$cpf = $linha_array['Cpf'];
					$login = $linha_array['Login'];
					$administrador = $linha_array['Administrador'];       
					$tipo = $linha_array['Tipo'];
					$ativo = $linha_array['Ativo'];
					if($tipo == 2){
						$curso = $linha_array['Curso_idCurso'];
						$matricula = $linha_array['Matricula'];
						$nomeCursoSelecionado = $linha_array['NomeCurso'];
					}
					$_SESSION['tipoAlteracao'] = $tipo;
				}
                $_SESSION['idAlteracao2'] = $id;
            }
            echo '<form method="POST" action="php2.php">';
            echo '<label for="id">Id:</label> <input value='.$id.' id="id" name="id" type="number" placeholder="Id do usuário" min="1" max="99999999999" required readonly="readonly"/> <br/>';
            if($_SESSION['administradorLogin']){
				echo '<label for="login">Login:</label> <input value='."'$login'".' id="login" name="login" type="text" placeholder="Login do usuário" maxlength="100" required /> <br/>';
			}
            echo '<label for="senha">Senha:</label> <input value='."'$senha'".' id="senha" name="senha" type="text" placeholder="Senha do usuário" maxlength="100" required /> <br/>';
            echo '<label for="nome">Nome:</label> <input value='."'$nome'".' id="nome" name="nome" type="text" placeholder="Nome do usuário" maxlength="100" required /> <br/>';
			if($_SESSION['administradorLogin']){
				echo '<label for="cpf">Cpf:</label> <input value='."'$cpf'".' id="cpf" name="cpf" type="number" placeholder="Digite o cpf" min="1" max="99999999999" required> <br/>';
				if($administrador){
					echo '<input type="checkbox" id="administrador" name="administrador" checked> <label for="administrador">Administrador</label> <br/>';
				}else{
					echo '<input type="checkbox" id="administrador" name="administrador"> <label for="administrador">Administrador</label> <br/>';
				}
				if($ativo){
					echo '<input type="checkbox" id="ativo" name="ativo" checked> <label for="ativo">Ativo</label> <br/>';
				}else{
					echo '<input type="checkbox" id="ativo" name="ativo"> <label for="ativo">Ativo</label> <br/>';
				}
				if($tipo == 0){
					$tipoString = 'Nenhum';
				}else if($tipo == 1){
					$tipoString = 'Professor';
				}else{
					$tipoString = 'Aluno';
				}
				echo '<label for="tipo">Tipo:</label> <input id="tipo" name="tipo" type="text" placeholder="" value='."'$tipoString'".' maxlength="10" readonly="readonly">';
				echo ' <br/>';
				echo '<label id="labelCurso" for="cursoSelect"> Curso do usuário: </label>';
				echo '<select id="cursoSelect" onchange="mudaCurso()">';
				foreach($_SESSION['queryPessoaCursos1'] as $linha_array) {
					$nomeCurso = $linha_array['Nome'];
					if($nomeCurso == $nomeCursoSelecionado){
						echo '<option value='."'$nomeCurso'"." selected>".$nomeCurso."</option>";
					}else{
						echo '<option value='."'$nomeCurso'".">".$nomeCurso."</option>";                    
					}
				} 
				foreach($_SESSION['queryPessoaCursos1'] as $linha_array) {
					echo '<input type="hidden" id="curso" name="curso" value='."'$nomeCursoSelecionado'"."/>";
					break;
				}  
				echo '</select>';
				echo '<br/>';
				echo '<label id="labelMatricula" for="matricula">Matricula: </label><input value='."'$matricula'".' id="matricula" name="matricula" type="text" placeholder="Digite a matricula" min="1" max="99999999"> <br/>';
				if($tipo != 2){
					echo '<script>document.getElementById("cursoSelect").style.visibility= "hidden"</script>'; 
					echo '<script>document.getElementById("labelCurso").style.visibility= "hidden"</script>';
					echo '<script>document.getElementById("matricula").style.visibility= "hidden"</script>';
					echo '<script>document.getElementById("labelMatricula").style.visibility= "hidden"</script>';
				}
				echo '<input name="submit" type="submit" value="Excluir" />';}
            echo '<input name="submit" type="submit" value="Alterar" />';
            echo '</form>';
            unset($_SESSION['queryUsuario3']);}
    ?>
        <script>
        function mudaCurso(){
            document.getElementById('curso').value = document.getElementById('cursoSelect').value;
        }
    </script>
    <div id="footer"></div>    
</body>
</html>