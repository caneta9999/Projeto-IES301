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
		<button type="submit" name="submit" class="button-search" value="Enviar"><span class="material-icons button-search">search</span>Pesquisar</button>
    </form>
    <hr/>
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
				break;
            }
            
            $elogiosChecked = ["Carismático" => "", "Explicação" => "", "Material" => "", "Organização" => "", "Pontualidade" => "", "Prestativo" => ""];
            foreach ($elogios as $elogio) {
                if (strcmp($elogio, "Nenhum") !== 0) {
                    $elogiosChecked[$elogio] = "checked";
                }
            }

            $criticasChecked = ["Comunicação" => "", "Explicação" => "", "Material" => "", "Método de avaliação" => "", "Organização" => "", "Pontualidade" => ""];
            foreach ($criticas as $critica) {
                if (strcmp($critica, "Nenhum") !== 0) {
                    $criticasChecked[$critica] = "checked";
                }
            }

            require '../../../camadaDados/conectar.php';
            require '../../../camadaDados/tabelas.php';      
            $result = "SELECT D1.Sigla, D1.Código, PD1.idProfessorDisciplina, D1.Nome 'DisciplinaNome',U1.Nome 'ProfessorNome', PD1.Periodo, PD1.DiaSemana FROM $db.$TB_PROFESSORDISCIPLINA PD1 inner join $db.$TB_DISCIPLINA D1 ON PD1.Disciplina_idDisciplina = D1.idDisciplina inner join $db.$TB_PROFESSOR P1 On P1.idProfessor = PD1.Professor_idProfessor inner join $db.$TB_USUARIO U1 on P1.Usuario_idUsuario = U1.idUsuario where PD1.idProfessorDisciplina like :id";
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
			$sigla = '';
            foreach($select->fetchAll() as $linha_array) {
                $disciplina = $linha_array['DisciplinaNome'];
                $professor = $linha_array['ProfessorNome'];
                $id = $linha_array['idProfessorDisciplina'];
                $periodo = $linha_array['Periodo'];
                $diaSemana = $linha_array['DiaSemana'];
				$codigo = $linha_array['Código'];
				$sigla = $linha_array['Sigla'];}
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
            $disciplina = "{$disciplina} ({$sigla} : {$codigo}) - {$professor} ({$periodo})";
            echo '<label for="disciplina">Disciplina:</label><input type="text" id="disciplina" readonly="readonly" name="disciplina" value='."'$disciplina' style='min-width:500px' "."/>";         
            echo '<br/>';
            echo '<label for="notaDisciplina">Nota para a disciplina: </label><input type="number" value='.$notaDisciplina.' name="notaDisciplina" placeholder="1-5" id="notaDisciplina" min="1" max="5" required> <br/>';
            echo '<label for="notaEvolucao">Nota para sua evolução: </label><input class="inputNota" value='.$notaEvolucao.' type="number" placeholder="1-5" name="notaEvolucao" id="notaEvolucao" min="1" max="5" required> <br/>';
            echo '<label for="notaAluno">Nota para você: </label><input class="inputNota" type="number" value='.$notaAluno.' placeholder="1-5" name="notaAluno" id="notaAluno" min="1" max="5" required> <br/>';
            echo '<label for="ano">Ano de conclusão da disciplina: </label><input class="inputAnoSemestre" value='.$ano.' type="number" placeholder="XXXX" name="ano" id="ano" min="1973" max="2099" required> <br/>';              
            echo '<label for="semestre">Semestre de conclusão da disciplina: </label><input value='.$semestre.' class="inputAnoSemestre" type="number" placeholder="1-2" name="semestre" id="semestre" min="1" max="2" required> <br/>';                		
            echo '<h2>Elogios para o professor (máximo 3):</h2>';
            echo '<div class="gradeElogiosCriticasContainer">';
            echo '<div class="gradeElogiosCriticas">';
                echo'<label for="checkElogioCarismatico"><input id="checkElogioCarismatico" name="checkElogio[]" class="checkElogio" type="checkbox" value="Carismático" onchange="checkQuantidadeElogios(`checkElogioCarismatico`)" ' . $elogiosChecked['Carismático'] . '>Carismático</label>';
                echo'<label for="checkElogioExplicacao"><input id="checkElogioExplicacao" name="checkElogio[]" class="checkElogio" type="checkbox" value="Explicação" onchange="checkQuantidadeElogios(`checkElogioExplicacao`)" ' . $elogiosChecked['Explicação'] . '>Explicação</label>';
                echo '<label for="checkElogioMaterial"><input id="checkElogioMaterial" name="checkElogio[]" class="checkElogio" type="checkbox" value="Material" onchange="checkQuantidadeElogios(`checkElogioMaterial`)" ' . $elogiosChecked['Material'] . '>Material</label>';
                echo '<label for="checkElogioOrganizacao"><input id="checkElogioOrganizacao" name="checkElogio[]" class="checkElogio" type="checkbox" value="Organização" onchange="checkQuantidadeElogios(`checkElogioOrganizacao`)" '. $elogiosChecked['Organização'] .'>Organização</label>';
                echo '<label for="checkElogioPontualidade"><input id="checkElogioPontualidade" name="checkElogio[]" class="checkElogio" type="checkbox" value="Pontualidade" onchange="checkQuantidadeElogios(`checkElogioPontualidade`)" ' . $elogiosChecked['Pontualidade'] .'>Pontualidade</label>';
                echo '<label for="checkElogioPrestativo"><input id="checkElogioPrestativo" name="checkElogio[]" class="checkElogio" type="checkbox" value="Prestativo" onchange="checkQuantidadeElogios(`checkElogioPrestativo`)" ' . $elogiosChecked['Prestativo'] .'>Prestativo</label>';
            echo '</div>';
            echo '</div>';
            echo '<p id="mensagemErroElogios"></p>';
            echo '<h2>Críticas/Áreas de melhoria para o professor (máximo 3):</h2>';
            echo '<div class="gradeElogiosCriticasContainer">';
            echo '<div class="gradeElogiosCriticas">';
                echo '<label for="checkCriticaComunicacao"><input id="checkCriticaComunicacao" name="checkCritica[]" class="checkCritica" type="checkbox" value="Comunicação" onchange="checkQuantidadeCriticas(`checkCriticaComunicacao`)"' . $criticasChecked['Comunicação'] . '>Comunicação</label>';
                echo '<label for="checkCriticaExplicacao"><input id="checkCriticaExplicacao" name="checkCritica[]" class="checkCritica" type="checkbox" value="Explicação" onchange="checkQuantidadeCriticas(`checkCriticaExplicacao`)" '. $criticasChecked['Explicação'] . '>Explicação</label>';
                echo '<label for="checkCriticaMaterial"><input id="checkCriticaMaterial" name="checkCritica[]" class="checkCritica" type="checkbox" value="Material" onchange="checkQuantidadeCriticas(`checkCriticaMaterial`)" '. $criticasChecked['Material'] . '>Material</label>';
                echo '<label for="checkCriticaMetodo"><input id="checkCriticaMetodo" name="checkCritica[]" class="checkCritica" type="checkbox" value="Método de avaliação" onchange="checkQuantidadeCriticas(`checkCriticaMetodo`)" '. $criticasChecked['Método de avaliação'] . '>Método de avaliação</label>';
                echo '<label for="checkCriticaOrganizacao"><input id="checkCriticaOrganizacao" name="checkCritica[]" class="checkCritica" type="checkbox" value="Organização" onchange="checkQuantidadeCriticas(`checkCriticaOrganizacao`)" ' . $criticasChecked['Organização'] . '>Organização</label>';
                echo '<label for="checkCriticaPontualidade"><input id="checkCriticaPontualidade" name="checkCritica[]" class="checkCritica" type="checkbox" value="Pontualidade" onchange="checkQuantidadeCriticas(`checkCriticaPontualidade`)" ' . $criticasChecked['Pontualidade'] .'>Pontualidade</label>';
            echo '</div>';
        echo '</div>';
        echo '<p id="mensagemErroCriticas"></p>';
            echo '<label for="descricao"> Descrição: </label><textarea rows="5" cols="30" id="descricao" name="descricao" placeholder="Comentário..." required maxlength="500" >'.$descricao.'</textarea> <br/>';
			echo '<button name="submit" onclick="return confirmarSubmit('."'Você realmente deseja excluir esse registro? Não será possível reverter sua ação!'".')" type="submit" class="button-delete" value="Excluir" /><span class="material-icons button-delete">delete</span>Excluir</button>';				
			echo '<button name="submit" onclick="return confirmarSubmit('."'Você realmente deseja cancelar a alteração? Não será possível reverter sua ação!'".')" type="submit" value="Cancelar" class="button-cancel"><span class="material-icons button-cancel">close</span>Cancelar</button>';
			echo '<button name="submit" type="submit" class="button-confirm" value="Alterar" /><span class="material-icons button-confirm">done</span>Confirmar</button>';
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
		function confirmarSubmit(mensagem){
			var confirmar=confirm(mensagem);
			return confirmar? true:false
		}

        function checkQuantidadeElogios(idUltimoElogio) {
            let mensagemErro = document.getElementById("mensagemErroElogios");

            let elogiosPossiveis = document.getElementsByClassName("checkElogio");
            let elogiosMarcados = [];
            for (let i = 0; i < elogiosPossiveis.length; i++) {
                if (elogiosPossiveis[i].checked) {
                    elogiosMarcados.push(elogiosPossiveis[i])
                }
            }
            if (elogiosMarcados.length > 3) {
                ultimoElogio = document.getElementById(idUltimoElogio);
                ultimoElogio.checked = false;
                mensagemErro.innerHTML = "Não é possível selecionar mais de 3 elogios!"
                setTimeout(function() {
                    mensagemErro.innerHTML = ""}, 4000 //Para fazer a mensagem desaparecer
                );
            } else {
                mensagemErro.innerHTML = "";
            }
        }

        function checkQuantidadeCriticas(idUltimaCritica) {
            let mensagemErro = document.getElementById("mensagemErroCriticas");

            let criticasPossiveis = document.getElementsByClassName("checkCritica");
            let criticasMarcadas = [];
            for (let i = 0; i < criticasPossiveis.length; i++) {
                if (criticasPossiveis[i].checked) {
                    criticasMarcadas.push(criticasPossiveis[i])
                }
            }
            if (criticasMarcadas.length > 3) {
                ultimaCritica = document.getElementById(idUltimaCritica);
                ultimaCritica.checked = false;
                mensagemErro.innerHTML = "Não é possível selecionar mais de 3 críticas!"
                setTimeout(function() {
                    mensagemErro.innerHTML = ""}, 4000 //Para fazer a mensagem desaparecer
                );
            } else {
                mensagemErro.innerHTML = "";
            }
        }
    </script>
    <div id="push"></div>
    <div id="footer"></div> 
</body>
</html>