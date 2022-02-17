<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']))
{
  header('location:../../Login/index.php');
}?>
<?php
    require '../../../camadaDados/conectar.php';
    require '../../../camadaDados/tabelas.php';
    $idCurso = "";
    if($_SESSION['tipoLogin'] == 2){
        $result = "SELECT A1.Curso_idCurso FROM $db.$TB_ALUNO A1 where A1.Usuario_idUsuario=:id";
        $select = $conx->prepare($result);
        $select->bindParam(':id',$_SESSION['idUsuarioLogin']);
        $select->execute();
        foreach($select->fetchAll() as $linha_array){
            $idCurso = $linha_array['Curso_idCurso'];
        }
    }
    else{
        $idCurso = "%%";
    }
    $result = "SELECT PD1.idProfessorDisciplina, D1.Nome 'DisciplinaNome',U1.Nome 'ProfessorNome', PD1.Periodo, PD1.DiaSemana FROM $db.$TB_PROFESSORDISCIPLINA PD1 inner join $db.$TB_DISCIPLINA  D1 ON PD1.Disciplina_idDisciplina = D1.idDisciplina inner join $db.$TB_PROFESSOR  P1 On P1.idProfessor = PD1.Professor_idProfessor inner join $db.$TB_USUARIO U1 on P1.Usuario_idUsuario = U1.idUsuario inner join $db.$TB_CURSODISCIPLINA CD1 ON CD1.Disciplina_idDisciplina = D1.idDisciplina where CD1.Curso_idCurso like :id";
    $select = $conx->prepare($result);
    $select->bindParam(':id',$idCurso);
    $select->execute();
    $_SESSION['queryProfessorDisciplinaCriticas1'] = $select->fetchAll();
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
    <h1>Cadastrar critica</h1>
    <button class="button btnVoltar"><a href="../index.php">Voltar</a></button><br/>
    <form action="php.php" method="POST">
        <?php
            echo '<label id="labelDisciplina" for="disciplinaSelect"> Disciplina: </label>';
            echo '<select id="disciplinaSelect" onchange="mudaDisciplina()">';
            $idPrimeiro = 0;
            foreach($_SESSION['queryProfessorDisciplinaCriticas1'] as $linha_array) {
                $disciplina = $linha_array['DisciplinaNome'];
                $professor = $linha_array['ProfessorNome'];
                $id = $linha_array['idProfessorDisciplina'];
                if($idPrimeiro == 0){
                    $idPrimeiro = $id;
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
            } 
            foreach($_SESSION['queryProfessorDisciplinaCriticas1'] as $linha_array) {
                echo '<input type="hidden" id="disciplina" name="disciplina" value='."'$idPrimeiro'"."/>";
                break;
            }            
            echo '</select>';
            echo '<br/>';
        ?>
        <label for="notaProfessor">Nota para o professor: </label><input type="number" name="notaProfessor" id="notaProfessor" min="1" max="5" required> <br/>
        <label for="notaDisciplina">Nota para a disciplina: </label><input type="number" name="notaDisciplina" id="notaDisciplina" min="1" max="5" required> <br/>
        <label for="descricao"> Descrição: </label><textarea rows="5" cols="30" id="descricao" name="descricao" placeholder="Defina sua critica" required maxlength="500" ></textarea> <br/>
        <input type="submit" name="submit" value="Enviar">
    </form>
    <script>
        function mudaDisciplina(){
            document.getElementById('disciplina').value = document.getElementById('disciplinaSelect').value;
        }
    </script>
    <div id="footer"></div>    
</body>
</html>