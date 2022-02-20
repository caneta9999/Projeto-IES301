<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']))
{
  header('location:../../Login/index.php');
}
?>
<?php
    require '../../../camadaDados/conectar.php';
    require '../../../camadaDados/tabelas.php';
    $result = "SELECT PD1.idProfessorDisciplina, D1.Nome 'DisciplinaNome',U1.Nome 'ProfessorNome', PD1.Periodo, PD1.DiaSemana FROM $db.$TB_PROFESSORDISCIPLINA PD1 inner join $db.$TB_DISCIPLINA D1 ON PD1.Disciplina_idDisciplina = D1.idDisciplina inner join $db.$TB_PROFESSOR P1 On P1.idProfessor = PD1.Professor_idProfessor inner join $db.$TB_USUARIO U1 on P1.Usuario_idUsuario = U1.idUsuario inner join $db.$TB_CURSODISCIPLINA CD1 ON CD1.Disciplina_idDisciplina = D1.idDisciplina order by D1.Nome";
    $select = $conx->prepare($result);
    $select->execute();
    $_SESSION['queryProfessorDisciplinaCriticas2'] = $select->fetchAll();
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
    <h1>Consultar criticas sobre disciplina</h1>
    <button class="button btnVoltar"><a href="../index.php">Voltar</a></button><br/>
    <form action="php.php" method="POST">
      <?php
            echo '<label id="labelDisciplina" for="disciplinaSelect"> Disciplina: </label>';
            echo '<select id="disciplinaSelect" onchange="mudaDisciplina()">';
            $primeiroId = 0;
            foreach($_SESSION['queryProfessorDisciplinaCriticas2'] as $linha_array) {
                $disciplina = $linha_array['DisciplinaNome'];
                $professor = $linha_array['ProfessorNome'];
                $id = $linha_array['idProfessorDisciplina'];
                if($primeiroId ==0){
                  $primeiroId = $id;
                }
                $periodo = $linha_array['Periodo'];
                $diaSemana = $linha_array['DiaSemana'];
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
                if($periodo == 0){
                    $periodo = 'Manhã';
                }else if($periodo == 1){
                    $periodo = 'Tarde';
                }else{
                    $periodo = 'Noite';
                }
                echo '<option value='."'$id'".">".$disciplina." - ".$professor." - ".$periodo." - ".$diaSemana."</option>";
                $_SESSION['nomeDisciplinaProfessor'] = $disciplina." - ".$professor." - ".$periodo." - ".$diaSemana;
            } 
            foreach($_SESSION['queryProfessorDisciplinaCriticas1'] as $linha_array) {
                echo '<input type="hidden" id="disciplina" name="disciplina" value='."'$primeiroId'"."/>";
                break;
            }            
            echo '</select>';
            echo '<br/>';
        ?>
        <input type="submit" name="submit" value="Enviar">
    </form>
    <?php
		if(isset($_SESSION['queryCritica2'])){
            echo "<table>";
            echo "<thead>";
                echo"<tr>";
                if(isset($_SESSION['administradorLogin'])){
                  echo"<th >Id da critica</th>";
                  echo"<th >Matricula Aluno</th>";}
                echo"<th> Nota da disciplina</th>";
                echo"<th >Nota para evolucao do aluno</th>";
                echo"<th >Nota para o aluno</th>";
                echo"<th> Descrição </th>";
                echo"<th> Data </th>";
                echo"</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach($_SESSION['queryCritica2'] as $linha_array) {
                echo "<tr>";
                if(isset($_SESSION['administradorLogin'])){
                  echo "<td>". $linha_array['idCritica'] ."</td>";        
                  echo "<td>". $linha_array['Matricula'] ."</td>";}	
                echo "<td>". $linha_array['NotaDisciplina'] ."</td>";	
                echo "<td>". $linha_array['NotaEvolucao'] ."</td>";
                echo "<td>". $linha_array['NotaAluno'] ."</td>";	
                echo "<td>". $linha_array['Descrição'] ."</td>";
                echo "<td>". $linha_array['Data'] ."</td>";	 	 
                echo "</tr>";}
            echo  "</tbody>";
            echo "</table>";
            unset($_SESSION['queryCritica2']);
		}
		?>
    <script>
        function mudaDisciplina(){
            document.getElementById('disciplina').value = document.getElementById('disciplinaSelect').value;
        }
    </script>
    <div id="push"></div>
    <div id="footer"></div>    
</body>
</html>