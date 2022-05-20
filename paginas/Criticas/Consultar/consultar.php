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
    <h1>Consultar críticas realizadas por alunos</h1>
	<?php
		if($_SESSION['administradorLogin']){
			echo '<h2>Digite o id de aluno do usuário, não o id de usuário.</h2>';
		}
	?>
    <button class="button btnVoltar"><a href="../index.php">Voltar</a></button><br/>
    <form action="php.php" method="POST">
        <?php
        if($_SESSION['administradorLogin']){
          echo '<label for="id">Id: </label><input id="id" name="id" type="number" placeholder="Digite o id do aluno" min="1" max="99999999999"/> <br/>';
          echo '<input type="submit" name="submit" value="Ver as críticas do aluno">';}
        else if($_SESSION['tipoLogin'] == 2){
          echo '<input type="submit" name="submit" value="Ver suas críticas">';}
        ?>
    </form>
    <?php
		if(isset($_SESSION['queryCritica1'])){
            $aluno = '';
            foreach($_SESSION['queryCritica1'] as $linha_array) {
              $aluno = $linha_array['Nome'];
              break;
            }
            echo "<h2>".$aluno."</h2>";
            echo "<table class='sortable'>";
            echo "<thead>";
                echo"<tr>";
                echo "<th>Id da crítica</th>";
                echo"<th >Disciplina</th>";
                echo"<th >Nota da disciplina</th>";
                echo"<th >Nota para evolucao do aluno</th>";
                echo"<th >Nota para o aluno</th>";
                echo"<th >Descrição</th>";
                echo"<th >Ano e Semestre</th>";
                echo"<th>Elogios</th>";
                echo"<th >Críticas</th>";
                echo"</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach($_SESSION['queryCritica1'] as $linha_array) {
                echo "<tr>";
                echo "<th>".$linha_array['idCritica']."</th>";
                $disciplina = "";
                require '../../../camadaDados/conectar.php';
                require '../../../camadaDados/tabelas.php';
                $result = "SELECT PD1.idProfessorDisciplina, D1.Nome 'DisciplinaNome',U1.Nome 'ProfessorNome', PD1.Periodo, PD1.DiaSemana FROM $db.$TB_PROFESSORDISCIPLINA PD1 inner join $db.$TB_DISCIPLINA D1 ON PD1.Disciplina_idDisciplina = D1.idDisciplina inner join $db.$TB_PROFESSOR P1 On P1.idProfessor = PD1.Professor_idProfessor inner join $db.$TB_USUARIO U1 on P1.Usuario_idUsuario = U1.idUsuario where PD1.idProfessorDisciplina like :id";
                $select = $conx->prepare($result);
                $select->bindParam(':id',$linha_array['ProfessorDisciplina_idProfessorDisciplina']);
                $select->execute();
                foreach($select->fetchAll() as $linha_array2){
                  $disciplina = $linha_array2['DisciplinaNome'];
                  $professor = $linha_array2['ProfessorNome'];
                  $id = $linha_array2['idProfessorDisciplina'];
                  $periodo = $linha_array2['Periodo'];
                  $diaSemana = $linha_array2['DiaSemana'];
                }
                if($diaSemana == 2){
                  $diaSemana = 'Segunda-feira';
                }else if($diaSemana == 3){
                  $diaSemana = 'Terça-feira';
                }else if($diaSemana == 4){
                  $diaSemana = 'Quarta-feira';
                }else if($diaSemana == 5){
                    $diaSemana = 'Quinta-feira';
                }else if($diaSemana == 6){
                  $diaSemana = 'Sexta-feira';
                }else{
                  $diaSemana = 'Sabado';
                }
                if(!$_SESSION['administradorLogin']){
                  $id = "";
                }
                if($periodo == 2){
                  $periodo = 'Noite';
                }else if($periodo == 1){
                  $periodo = 'Tarde';
                }else{
                  $periodo='Manhã';
                }  
                $disciplina = $id." - ".$disciplina." - ".$professor." - ".$periodo." - ".$diaSemana;
                echo "<td>". $disciplina ."</td>";
                echo "<td>". $linha_array['NotaDisciplina'] ."</td>";
                echo "<td>". $linha_array['NotaEvolucao'] ."</td>";
                echo "<td>". $linha_array['NotaAluno'] ."</td>";
                echo "<td>". $linha_array['Descrição'] ."</td>";
                echo "<td>".substr($linha_array['AnoSemestre'], 0, 4)."-".substr($linha_array['AnoSemestre'], 4, 1)."</td>";
                $elogios = explode('-', $linha_array['Elogios']);
                $elogiosFinal = "";
                foreach($elogios as $elogio){
                  if($elogio != "Nenhum"){
                    $elogiosFinal = $elogio."<br/>".$elogiosFinal;
                  }
                }
                echo "<td>".$elogiosFinal."</td>";
                $criticas = explode('-', $linha_array['Criticas']);
                $criticasFinal = "";
                foreach($criticas as $criticas){
                  if($criticas != "Nenhum"){
                    $criticasFinal = $criticas."<br/>".$criticasFinal;
                  }
                }
                echo "<td>".$criticasFinal."</td>";
                echo "</tr>";}
            echo  "</tbody>";
            echo "</table>";
            unset($_SESSION['queryCritica1']);
		}
		?>
    <div id="push"></div>
    <div id="footer"></div>    
</body>
</html>