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
    <h1>Alterar disciplina vinculada a curso</h1>
    <button class="button btnVoltar"><a href="../index.php">Voltar</a></button><br/>
    <form action="php1.php" method="POST">
        <label for="id">Id: </label><input id="id" name="id" type="number" placeholder="Digite o id" min="1" max="99999999999" required> <br/>
        <input type="submit" name="submit" value="Enviar">
    </form>
    <hr/>
    <?php
        if(isset($_SESSION['queryCursoDisciplina2'])){
            $curso = 'Curso';
            $id = -1;
            $disciplina = 'Disciplina';
            $tipo = 0;
            $ativa = 0;
            foreach($_SESSION['queryCursoDisciplina2'] as $linha_array){
                $curso = $linha_array['CursoNome'];
                $disciplina = $linha_array['nome'];
                $tipo = $linha_array['tipo'];
                $ativa = $linha_array['ativa'];
                $id = $linha_array['CursoDisciplinaId'];
                $_SESSION['idAlteracao4'] = $id;
            }
            echo '<form method="POST" action="php2.php">';
            echo '<label for="id">Id:</label> <input value='.$id.' id="id" name="id" type="number" placeholder="Id do curso" min="1" max="99999999999" required readonly="readonly"/> <br/>';
            echo '<label for="curso">Curso:</label><input type="text" id="curso" readonly="readonly" name="curso" value='."'$curso'"."/>";           
            echo '<br/>';
            echo '<label for="disciplina">Disciplina:</label><input type="text" id="disciplina" readonly="readonly" name="disciplina" value='."'$disciplina'"."/>";         
            echo '<br/>';
            echo '<label for="tipoSelect"> Tipo: </label>';
            echo '<select id="tipoSelect" onchange="mudaTipo()">';
            if($tipo == 0){
                $tipo = 'Obrigatória';
                echo '<option value="Obrigatória" selected> Obrigatória </option>';
                echo '<option value="Eletiva"> Eletiva </option>';
                echo '<option value="Escolha"> Escolha </option>';}
            else if($tipo == 1){
                $tipo = 'Eletiva';
                echo '<option value="Obrigatória"> Obrigatória </option>';
                echo '<option value="Eletiva" selected> Eletiva </option>';
                echo '<option value="Escolha"> Escolha </option>';                
            }else{
                $tipo = 'Escolha';
                echo '<option value="Obrigatória"> Obrigatória </option>';
                echo '<option value="Eletiva"> Eletiva </option>';
                echo '<option value="Escolha" selected> Escolha </option>';                 
            }
            echo '<input id="tipo" name="tipo" type="hidden" placeholder="" value='."'$tipo'".' maxlength="11">';
            echo '<br/>';
            $checked = $ativa?"checked":"";
            echo '<input type="checkbox" id="ativa" name="ativa" '.$checked.'><label for="ativa">Ativa</label> <br/>';    
            echo '</select>';
            echo '<br/>';         
            echo '<input name="submit" type="submit" value="Excluir" />';
            echo '<input name="submit" type="submit" value="Alterar" />';
            echo '</form>';
            unset($_SESSION['queryCursoDisciplina2']);}
    ?>
    <script>
        function mudaTipo(){
            document.getElementById('tipo').value = document.getElementById('tipoSelect').value;
        }
    </script>
    <div id="footer"></div>    
</body>
</html>