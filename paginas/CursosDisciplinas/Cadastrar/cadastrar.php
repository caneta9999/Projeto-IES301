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
    <h1>Cadastrar disciplina em curso</h1>
    <button class="button btnVoltar"><a href="../index.php">Voltar</a></button><br/>
    <form action="php.php" method="POST">
        <?php
            echo '<label id="labelCurso" for="cursoSelect"> Curso: </label>';
            echo '<select id="cursoSelect" onchange="mudaCurso()">';
			$nomeSelect1 = '';
            foreach($_SESSION['queryCursosDisciplinasCursos1'] as $linha_array) {
                if($nomeSelect1 == ''){
					$nomeSelect1 = $linha_array['Nome'];}
				$nome = $linha_array['Nome'];
                echo '<option value='."'$nome'".">".$nome."</option>";
            } 
            foreach($_SESSION['queryCursosDisciplinasCursos1'] as $linha_array) {
                echo '<input type="hidden" id="curso" name="curso" value='."'$nomeSelect1'"."/>";
                break;
            }            
            echo '</select>';
            echo '<br/>';

            echo '<label id="labelDisciplina" for="disciplinaSelect"> Disciplina: </label>';
            echo '<select id="disciplinaSelect" onchange="mudaDisciplina()">';
			$nomeSelect2 = '';
            foreach($_SESSION['queryCursosDisciplinasDisciplinas1'] as $linha_array) {
                if($nomeSelect2 == ''){
					$nomeSelect2 = $linha_array['Nome'];}
				$nome = $linha_array['Nome'];
                echo '<option value='."'$nome'".">".$nome."</option>";
            } 
            foreach($_SESSION['queryCursosDisciplinasDisciplinas1'] as $linha_array) {
                echo '<input type="hidden" id="disciplina" name="disciplina" value='."'$nomeSelect2'"."/>";
                break;
            }            
            echo '</select>';
            echo '<br/>';
        ?>
        <label for="tipoSelect"> Tipo: </label>
        <select id="tipoSelect" onchange="mudaTipo()">
            <option value="Obrigatória" selected> Obrigatória </option>
            <option value="Eletiva"> Eletiva </option>
            <option value="Escolha"> Escolha </option>
        </select><br/>
        <input id="tipo" name="tipo" type="hidden" placeholder="" value="Obrigatória" maxlength="11">
        <input type="checkbox" id="ativa" name="ativa" checked> <label for="ativa">Ativa</label> <br/>
        <input type="submit" name="submit" value="Enviar">
    </form>
    <script>
        function mudaCurso(){
            document.getElementById('curso').value = document.getElementById('cursoSelect').value;
        }
        function mudaDisciplina(){
            document.getElementById('disciplina').value = document.getElementById('disciplinaSelect').value;
        }
        function mudaTipo(){
            document.getElementById('tipo').value = document.getElementById('tipoSelect').value;
        }
    </script>
    <div id="footer"></div>    
</body>
</html>