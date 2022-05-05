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
    $result = "SELECT distinct PD1.idProfessorDisciplina, D1.Nome 'DisciplinaNome',U1.Nome 'ProfessorNome', PD1.Periodo, PD1.DiaSemana FROM $db.$TB_PROFESSORDISCIPLINA PD1 inner join $db.$TB_DISCIPLINA  D1 ON PD1.Disciplina_idDisciplina = D1.idDisciplina inner join $db.$TB_PROFESSOR  P1 On P1.idProfessor = PD1.Professor_idProfessor inner join $db.$TB_USUARIO U1 on P1.Usuario_idUsuario = U1.idUsuario inner join $db.$TB_CURSODISCIPLINA CD1 ON CD1.Disciplina_idDisciplina = D1.idDisciplina where CD1.Curso_idCurso like :id";
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
    <h1>Cadastrar crítica</h1>
    <button class="button btnVoltar"><a href="../index.php">Voltar</a></button><br/>
    <form action="php.php" method="POST">
        <?php
            function selectElogio($numeroSelect){
                echo '<label id=labelElogio'.$numeroSelect.' for=elogioSelect'.$numeroSelect.' > Elogio: </label>';
                echo '<select id=elogioSelect'.$numeroSelect.' onchange=mudaElogio'.$numeroSelect.'() >';
                    echo '<option value="Nenhum" selected>Nenhum</option>';
                    echo '<option value="Explicação">Explicação</option>';
                    echo '<option value="Material">Material</option>';
                    echo '<option value="Organização">Organização</option>';
                    echo '<option value="Pontualidade">Pontualidade</option>';
                    echo '<option value="Prestativo">Prestativo</option>';
                    echo '<option value="Carismático">Carismático</option>';
                    echo '</select><br/>';
                echo '<input type="hidden" id=elogio'.$numeroSelect.' name=elogio'.$numeroSelect.' value="Nenhum"/>';
            }
            function selectCritica($numeroSelect){
                echo '<label id=labelCritica'.$numeroSelect.' for=criticaSelect'.$numeroSelect.' > Possível melhoria: </label>';
                echo '<select id=criticaSelect'.$numeroSelect.' onchange=mudaCritica'.$numeroSelect.'() >';
                    echo '<option value="Nenhum" selected>Nenhum</option>';
                    echo '<option value="Explicação">Explicação</option>';
                    echo '<option value="Material">Material</option>';
                    echo '<option value="Organização">Organização</option>';
                    echo '<option value="Pontualidade">Pontualidade</option>';
                    echo '<option value="Comunicação">Comunicação</option>';
                    echo '<option value="Método de avaliação">Método de avaliação</option>';
                    echo '</select><br/>';
                echo '<input type="hidden" id=critica'.$numeroSelect.' name=critica'.$numeroSelect.' value="Nenhum"/>';
            }
        ?>
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
                    $diaSemana = 'Sábado';
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
            echo '</select>';
            echo '<br/>';
            foreach($_SESSION['queryProfessorDisciplinaCriticas1'] as $linha_array) {
                echo '<input type="hidden" id="disciplina" name="disciplina" value='."'$idPrimeiro'"."/>";
                break;
            }            
        ?>
        <label for="notaDisciplina">Nota para a disciplina: </label><input class="inputNota" type="number" placeholder="Nota para a disciplina" name="notaDisciplina" id="notaDisciplina" min="1" max="5" required> <br/>
        <label for="notaEvolucao">Nota para sua evolução: </label><input class="inputNota" type="number" placeholder="Nota para o quanto você evoluiu durante a disciplina" name="notaEvolucao" id="notaEvolucao" min="1" max="5" required> <br/>
        <label for="notaAluno">Nota para você: </label><input class="inputNota" type="number" placeholder="Nota para sua dedicação na disciplina" name="notaAluno" id="notaAluno" min="1" max="5" required> <br/>                
        <label for="ano">Ano de conclusão da disciplina: </label><input class="inputAnoSemestre" type="number" placeholder="Ano de conclusão" name="ano" id="ano" min="1973" max="2100" required> <br/>                
        <label for="semestre">Semestre de conclusão da disciplina: </label><input class="inputAnoSemestre" type="number" placeholder="Semestre de conclusão" name="semestre" id="semestre" min="1" max="2" required> <br/>                		
        <h2>Elogios para o professor (máximo 3):</h2>
        <?php
            selectElogio(1);
            selectElogio(2);
            selectElogio(3);
        ?>
        <h2>Críticas/Áreas de melhoria para o professor (máximo 3):</h2>
        <?php
            selectCritica(1);
            selectCritica(2);
            selectCritica(3);
        ?>
        <label for="descricao"> Comentário mais detalhado: </label><textarea rows="5" cols="30" id="descricao" name="descricao" placeholder="Comentário..." required maxlength="500" ></textarea> <br/>
        <input type="submit" name="submit" value="Enviar">
    </form>
    <div id="push"></div>
    <script>
        function mudaDisciplina(){
            document.getElementById('disciplina').value = document.getElementById('disciplinaSelect').value;
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
    <div id="footer"></div>    
</body>
</html>