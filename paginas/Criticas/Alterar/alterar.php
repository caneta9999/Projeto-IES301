<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']) || ($_SESSION['tipoLogin'] != 2 && !$_SESSION['administradorLogin']))
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
    <h1>Alterar crítica</h1>
    <button class="button btnVoltar button-go-return"><span class="material-icons button-go-return">reply</span><a class="button-go-return" href="../index.php">Voltar</a></button><br/>
    <form action="php1.php" method="POST">
        <label for="id">Id: </label><input id="id" name="id" type="number" placeholder="Digite o id" min="1" max="99999999999" required> <br/>
        <input type="submit" name="submit" value="Enviar">
    </form>
    <hr/>
    <?php
        function selectElogio($numeroSelect, $item){
            echo '<label id=labelElogio'.$numeroSelect.' for=elogioSelect'.$numeroSelect.' > Elogio: </label>';
            echo '<select id=elogioSelect'.$numeroSelect.' onchange=mudaElogio'.$numeroSelect.'() >';
                echo '<option value="Nenhum" '.($item=='Nenhum'?"selected":"").'>Nenhum</option>';
                echo '<option value="Explicação" '.($item=='Explicação'?"selected":"").'>Explicação</option>';
                echo '<option value="Material" '.($item=='Material'?"selected":"").'>Material</option>';
                echo '<option value="Organização" '.($item=='Organização'?"selected":"").'>Organização</option>';
                echo '<option value="Pontualidade" '.($item=='Pontualidade'?"selected":"").'>Pontualidade</option>';
                echo '<option value="Prestativo" '.($item=='Prestativo'?"selected":"").'>Prestativo</option>';
                echo '<option value="Carismático" '.($item=='Carismático'?"selected":"").'>Carismático</option>';
                echo '</select><br/>';
            echo '<input type="hidden" id=elogio'.$numeroSelect.' name=elogio'.$numeroSelect.' value='."'$item'".'/>';
        }
        function selectCritica($numeroSelect, $item){
            echo '<label id=labelCritica'.$numeroSelect.' for=criticaSelect'.$numeroSelect.' > Possível melhoria: </label>';
            echo '<select id=criticaSelect'.$numeroSelect.' onchange=mudaCritica'.$numeroSelect.'() >';
            echo '<option value="Nenhum" '.($item=='Nenhum'?"selected":"").'>Nenhum</option>';
            echo '<option value="Explicação" '.($item=='Explicação'?"selected":"").'>Explicação</option>';
            echo '<option value="Material" '.($item=='Material'?"selected":"").'>Material</option>';
            echo '<option value="Organização" '.($item=='Organização'?"selected":"").'>Organização</option>';
            echo '<option value="Pontualidade" '.($item=='Pontualidade'?"selected":"").'>Pontualidade</option>';
                echo '<option value="Comunicação"'.($item=='Comunicação'?"selected":"").'>Comunicação</option>';
                echo '<option value="Método de avaliação"'.($item=='Método de avaliação'?"selected":"").'>Método de avaliação</option>';
                echo '</select><br/>';
            echo '<input type="hidden" id=critica'.$numeroSelect.' name=critica'.$numeroSelect.' value='."'$item'".'/>';
        }
    ?>
    <?php
        if(isset($_SESSION['queryCritica3'])){
            $idCritica = -1;
            $nome = "";
            $notaDisciplina = 3;
            $notaProfessor = 3;
            $descricao = '';
            $idProfessorDisciplina=0;
            $ano = 0;
            $semestre = 0;
            $elogios = '';
            $criticas = '';
            foreach($_SESSION['queryCritica3'] as $linha_array){
                $idCritica = $linha_array['idCritica'];
                $nome = $linha_array['Nome'];
                $notaDisciplina = $linha_array['NotaDisciplina'];
                $notaAluno = $linha_array['NotaAluno'];
                $notaEvolucao = $linha_array['NotaEvolucao'];
                $descricao = $linha_array['Descrição'];
                $ano = substr($linha_array['AnoSemestre'], 0, 4);
                $semestre = substr($linha_array['AnoSemestre'], 4, 1);
                $elogios = explode('-', $linha_array['Elogios']);
                $criticas = explode('-', $linha_array['Criticas']);
                $idProfessorDisciplina = $linha_array['ProfessorDisciplina_idProfessorDisciplina'];
                $_SESSION['idAlteracao6'] = $idCritica;
            }
            require '../../../camadaDados/conectar.php';
            require '../../../camadaDados/tabelas.php';      
            $result = "SELECT D1.Código, PD1.idProfessorDisciplina, D1.Nome 'DisciplinaNome',U1.Nome 'ProfessorNome', PD1.Periodo, PD1.DiaSemana FROM $db.$TB_PROFESSORDISCIPLINA PD1 inner join $db.$TB_DISCIPLINA D1 ON PD1.Disciplina_idDisciplina = D1.idDisciplina inner join $db.$TB_PROFESSOR P1 On P1.idProfessor = PD1.Professor_idProfessor inner join $db.$TB_USUARIO U1 on P1.Usuario_idUsuario = U1.idUsuario where PD1.idProfessorDisciplina like :id";
            $select = $conx->prepare($result);
            $select->bindParam(':id',$idProfessorDisciplina);
            $select->execute();
            echo '<form method="POST" action="php2.php">';
            echo '<label for="id">Id:</label> <input value='.$idCritica.' id="id" name="id" type="number" placeholder="Id do curso" min="1" max="99999999999" required readonly="readonly"/> <br/>';
            $codigo = '';
			$disciplina = '';
            $professor = '';
            $id = '';
            $periodo = '';
            $diaSemana = '';            
            foreach($select->fetchAll() as $linha_array) {
                $disciplina = $linha_array['DisciplinaNome'];
                $professor = $linha_array['ProfessorNome'];
                $id = $linha_array['idProfessorDisciplina'];
                $periodo = $linha_array['Periodo'];
                $diaSemana = $linha_array['DiaSemana'];
				$codigo = $linha_array['Código'];}
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
            $disciplina = $codigo." - ".$disciplina." - ".$professor." - ".$periodo." - ".$diaSemana;
            echo '<label for="disciplina">Disciplina:</label><input type="text" id="disciplina" readonly="readonly" name="disciplina" value='."'$disciplina' style='min-width:500px' "."/>";         
            echo '<br/>';
            echo '<label for="notaDisciplina">Nota para a disciplina: </label><input type="number" value='.$notaDisciplina.' name="notaDisciplina" id="notaDisciplina" min="1" max="5" required> <br/>';
            echo '<label for="notaEvolucao">Nota para sua evolução: </label><input class="inputNota" value='.$notaEvolucao.' type="number" placeholder="Nota para o quanto você evoluiu durante a disciplina" name="notaEvolucao" id="notaEvolucao" min="1" max="5" required> <br/>';
            echo '<label for="notaAluno">Nota para você: </label><input class="inputNota" type="number" value='.$notaAluno.' placeholder="Nota para sua dedicação na disciplina" name="notaAluno" id="notaAluno" min="1" max="5" required> <br/>';
            echo '<label for="ano">Ano de conclusão da disciplina: </label><input class="inputAnoSemestre" value='.$ano.' type="number" placeholder="Ano de conclusão" name="ano" id="ano" min="1973" max="2100" required> <br/>';              
            echo '<label for="semestre">Semestre de conclusão da disciplina: </label><input value='.$semestre.' class="inputAnoSemestre" type="number" placeholder="Semestre de conclusão" name="semestre" id="semestre" min="1" max="2" required> <br/>';                		
            echo '<h2>Elogios para o professor (máximo 3):</h2>';
            foreach($elogios as $indice => $elogio){
                selectElogio($indice+1, $elogio);
            }
            echo '<h2>Críticas/Áreas de melhoria para o professor (máximo 3):</h2>';
            foreach($criticas as $indice => $critica){
                selectCritica($indice+1, $critica);
            }
            echo '<label for="descricao"> Descrição: </label><textarea rows="5" cols="30" id="descricao" name="descricao" placeholder="Defina sua critica" required maxlength="500" >'.$descricao.'</textarea> <br/>';
            echo '<input name="submit" type="submit" value="Excluir" />';
            echo '<input name="submit" type="submit" value="Alterar" />';
            echo '</form>';
            unset($_SESSION['queryCritica3']);}
    ?>
    <script>
        function mudaTipo(){
            document.getElementById('tipo').value = document.getElementById('tipoSelect').value;
        }
        function mudaElogio1(){
            document.getElementById('elogio1').value = document.getElementById('elogioSelect1').value;
        }
        function mudaElogio2(){
            document.getElementById('elogio2').value = document.getElementById('elogioSelect2').value;
        }
        function mudaElogio3(){
            document.getElementById('elogio3').value = document.getElementById('elogioSelect3').value;
        }
        function mudaCritica1(){
            document.getElementById('critica1').value = document.getElementById('criticaSelect1').value;
        }
        function mudaCritica2(){
            document.getElementById('critica2').value = document.getElementById('criticaSelect2').value;
        }
        function mudaCritica3(){
            document.getElementById('critica3').value = document.getElementById('criticaSelect3').value;
        }      
    </script>
    <div id="push"></div>
    <div id="footer"></div>    
</body>
</html>