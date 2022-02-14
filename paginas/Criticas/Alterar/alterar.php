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
    <h1>Alterar critica</h1>
    <button class="button btnVoltar"><a href="../index.php">Voltar</a></button><br/>
    <form action="php1.php" method="POST">
        <label for="id">Id: </label><input id="id" name="id" type="number" placeholder="Digite o id" min="1" max="99999999999" required> <br/>
        <input type="submit" name="submit" value="Enviar">
    </form>
    <hr/>
    <?php
        if(isset($_SESSION['queryCritica3'])){
            json_encode($_SESSION['queryCritica3']);
            $idCritica = -1;
            $nome = "";
            $notaDisciplina = 3;
            $notaProfessor = 3;
            $descricao = '';
            $idProfessorDisciplina=0;
            foreach($_SESSION['queryCritica3'] as $linha_array){
                $idCritica = $linha_array['idCritica'];
                $nome = $linha_array['Nome'];
                $notaDisciplina = $linha_array['NotaDisciplina'];
                $notaProfessor = $linha_array['NotaProfessor'];
                $descricao = $linha_array['Descrição'];
                $idProfessorDisciplina = $linha_array['ProfessorDisciplina_idProfessorDisciplina'];
                $_SESSION['idAlteracao'] = $idCritica;
            }
            require '../../../CamadaDados/conectar.php';
            $tb = 'professordisciplina';
            $tb2 = 'Disciplina';
            $tb3 = 'Professor';
            $tb4 = 'Usuario';
            $tb5 = 'cursodisciplina';
            $tb6 = 'Aluno';           
            $result = "SELECT PD1.idProfessorDisciplina, D1.Nome 'DisciplinaNome',U1.Nome 'ProfessorNome', PD1.Periodo, PD1.DiaSemana FROM $db.$tb PD1 inner join $db.$tb2 D1 ON PD1.Disciplina_idDisciplina = D1.idDisciplina inner join $db.$tb3 P1 On P1.idProfessor = PD1.Professor_idProfessor inner join $db.$tb4 U1 on P1.Usuario_idUsuario = U1.idUsuario inner join $db.$tb5 CD1 ON CD1.Disciplina_idDisciplina = D1.idDisciplina where PD1.idProfessorDisciplina like :id";
            $select = $conx->prepare($result);
            $select->bindParam(':id',$idProfessorDisciplina);
            $select->execute();
            echo '<form method="POST" action="php2.php">';
            echo '<label for="id">Id:</label> <input value='.$idCritica.' id="id" name="id" type="number" placeholder="Id do curso" min="1" max="99999999999" required readonly="readonly"/> <br/>';
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
                $diaSemana = $linha_array['DiaSemana'];}
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
            $disciplina = $disciplina." - ".$professor." - ".$periodo." - ".$diaSemana;
            echo '<label for="disciplina">Disciplina:</label><input type="text" id="disciplina" readonly="readonly" name="disciplina" value='."'$disciplina'"."/>";         
            echo '<br/>';
            echo '<label for="notaProfessor">Nota para o professor: </label><input type="number" value='.$notaProfessor.' name="notaProfessor" id="notaProfessor" min="1" max="5" required> <br/>';
            echo '<label for="notaDisciplina">Nota para a disciplina: </label><input type="number" value='.$notaDisciplina.' name="notaDisciplina" id="notaDisciplina" min="1" max="5" required> <br/>';
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
    </script>
    <div id="footer"></div>    
</body>
</html>