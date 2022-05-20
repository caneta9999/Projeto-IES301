<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']) || $_SESSION['administradorLogin']!=1)
{
  header('location:../../Login/index.php');
}?>
<?php
    require '../../../camadaDados/conectar.php';
    require '../../../camadaDados/tabelas.php';
    $result = "SELECT idUsuario,Nome FROM $db.$TB_USUARIO Where Tipo=1";
    $select = $conx->prepare($result);
    $select->execute();
    $_SESSION['queryProfessoresDisciplinasProfessores1'] = $select->fetchAll();
?>
<?php
    $result = "SELECT idDisciplina,Código,Nome FROM $db.$TB_DISCIPLINA";
    $select = $conx->prepare($result);
    $select->execute();
    $_SESSION['queryProfessoresDisciplinasDisciplinas1'] = $select->fetchAll();
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
    <h1>Cadastrar professor em disciplina</h1>
    <button class="button btnVoltar"><a href="../index.php">Voltar</a></button><br/>
    <form action="php.php" method="POST">
        <?php
            echo '<label id="labelProfessor" for="professorSelect"> Professor: </label>';
            echo '<select id="professorSelect" onchange="mudaProfessor()">';
			$idSelect1 = '';
            foreach($_SESSION['queryProfessoresDisciplinasProfessores1'] as $linha_array) {
                $nome = $linha_array['Nome'];
				if($idSelect1 == ''){
					$idSelect1 = $linha_array['idUsuario'];
				}
                $id = $linha_array['idUsuario'];
                echo '<option value='."'$id'".">".$nome."</option>";
            } 
            foreach($_SESSION['queryProfessoresDisciplinasProfessores1'] as $linha_array) {
                echo '<input type="hidden" id="professor" name="professor" value='."'$idSelect1'"."/>";
                break;
            }            
            echo '</select>';
            echo '<br/>';

            echo '<label id="labelDisciplina" for="disciplinaSelect"> Disciplina: </label>';
            echo '<select id="disciplinaSelect" onchange="mudaDisciplina()">';
			$idSelect2 = '';
            foreach($_SESSION['queryProfessoresDisciplinasDisciplinas1'] as $linha_array) {
				if($idSelect2 == ''){
					$idSelect2 = $linha_array['idDisciplina'];
				}
                echo '<option value='.$linha_array['idDisciplina']." >".$linha_array['Código']." - ".$linha_array['Nome']."</option>";
            } 
            foreach($_SESSION['queryProfessoresDisciplinasDisciplinas1'] as $linha_array) {
                echo '<input type="hidden" id="disciplina" name="disciplina" value='."'$idSelect2'"."/>";
                break;
            }            
            echo '</select>';
            echo '<br/>';
        ?>
        <label for="periodoSelect"> Período: </label>
        <select id="periodoSelect" onchange="mudaPeriodo()">
            <option value="0" selected> Manhã </option>
            <option value="1"> Tarde </option>
            <option value="2"> Noite </option>
        </select><br/>
        <input id="periodo" name="periodo" type="hidden" placeholder="" value=0 maxlength="15"><br/> 
        <label for="dataInicial">Data Inicial: </label> <input type="date" id="dataInicial" name="dataInicial" checked required> <br/>
        <label for="dataFinal">Data Final: </label> <input type="date" id="dataFinal" name="dataFinal" checked> <br/>
        <label for="diaSemana">Dia da Semana: </label> <input type="number" id="diaSemana" name="diaSemana" type="number" min="2" max="7" placeholder="2-7" required > <br/>
        <input type="submit" name="submit" value="Enviar">
    </form>
    <script>
        function mudaProfessor(){
            document.getElementById('professor').value = document.getElementById('professorSelect').value;
        }
        function mudaDisciplina(){
            document.getElementById('disciplina').value = document.getElementById('disciplinaSelect').value;
        }
        function mudaPeriodo(){
            document.getElementById('periodo').value = document.getElementById('periodoSelect').value;
        }
    </script>
    <div id="footer"></div>    
</body>
</html>