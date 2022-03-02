<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']) || !$_SESSION['administradorLogin'])
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
    $_SESSION['queryProfessorDisciplinaCriticas3'] = $select->fetchAll();
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
    <h1>Estatisticas</h1>
    <button class="button btnVoltar"><a href="../index.php">Voltar</a></button><br/>
    <form action="php.php" method="POST">
      <?php
       function diaSemana($diaSemana){
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
          return $diaSemana;}
        function periodo($periodo){
            if($periodo == 0){
                $periodo = 'Manhã';
            }else if($periodo == 1){
                $periodo = 'Tarde';
            }else{
                $periodo = 'Noite';
            }
            return $periodo;}
      ?>
      <?php
            echo "<script>var estatistiscasId = 1</script>";
            $labelMediaNotaDisciplina = [];
            $labelExplicadoMediaNotaDisciplina = 0;
            $valoresMediaNotaDisciplina = [];
            $labelMediaNotaEvolucao = [];
            $labelExplicadoMediaNotaEvolucao = 0;
            $valoresMediaNotaEvolucao = [];
            $labelMediaNotaAluno = [];
            $labelExplicadoMediaNotaAluno = 0;
            $valoresMediaNotaAluno = [];
            if(!isset($_SESSION['estatisticasId'])){
                echo '<label id="labelDisciplina" for="disciplinaSelect"> Disciplina: </label>';
                echo '<select id="disciplinaSelect" onchange="mudaDisciplina()">';
                $primeiroId = 0;
                foreach($_SESSION['queryProfessorDisciplinaCriticas3'] as $linha_array) {
                    $disciplina = $linha_array['DisciplinaNome'];
                    $professor = $linha_array['ProfessorNome'];
                    $id = $linha_array['idProfessorDisciplina'];
                    if($primeiroId ==0){
                    $primeiroId = $id;
                    }
                    $periodo = periodo($linha_array['Periodo']);
                    $diaSemana = diaSemana($linha_array['DiaSemana']);
                    echo '<option value='."'$id'".">".$disciplina." - ".$professor." - ".$periodo." - ".$diaSemana."</option>";
                    $_SESSION['nomeDisciplinaProfessor'] = $disciplina." - ".$professor." - ".$periodo." - ".$diaSemana;
                } 
                foreach($_SESSION['queryProfessorDisciplinaCriticas3'] as $linha_array) {
                    echo '<input type="hidden" id="disciplina" name="disciplina" value='."'$primeiroId'"."/>";
                    break;
                }            
                echo '</select>';
                echo '<br/>';
                echo '<input type="submit" name="submit" value="Consultar disciplina"><br/>';
                echo '<input type="submit" name="submit" value="Consultar dados gerais">';
            }
            else if($_SESSION['estatisticasId'] != 0){
                echo "<div class='grid' id='gridEstatisticasMediasDisciplina'>";
                echo "<div id='mediaDisciplinaDisciplina'>";
                echo "<h2>Média da disciplina</h2>";
                $result="SELECT AVG(NotaDisciplina) 'MediaDisciplina' FROM $db.$TB_CRITICA WHERE ProfessorDisciplina_idProfessorDisciplina = :id";
                $select=$conx->prepare($result);
                $select->bindParam(':id',$_SESSION['estatisticasId']);
                $select->execute();
                foreach($select->fetchAll() as $linha_array){
                    $media = number_format($linha_array['MediaDisciplina'], 2, '.', ' ');
                    echo "<b>".$media."</b>";
                }
                echo "</div>";
                echo "<div id='mediaEvolucaoDisciplina'>";
                echo "<h2>Média de evolução dos alunos</h2>";
                $result="SELECT AVG(NotaEvolucao) 'MediaEvolucao' FROM $db.$TB_CRITICA WHERE ProfessorDisciplina_idProfessorDisciplina = :id";
                $select=$conx->prepare($result);
                $select->bindParam(':id',$_SESSION['estatisticasId']);
                $select->execute();
                foreach($select->fetchAll() as $linha_array){
                    $media = number_format($linha_array['MediaEvolucao'], 2, '.', ' ');
                    echo "<b>".$media."</b>";
                }
                echo "</div>";
                echo "<div id='mediaAlunoDisciplina'>";
                echo "<h2>Média de auto-avaliação dos alunos</h2>";
                $result="SELECT AVG(NotaAluno) 'MediaAluno' FROM $db.$TB_CRITICA WHERE ProfessorDisciplina_idProfessorDisciplina = :id";
                $select=$conx->prepare($result);
                $select->bindParam(':id',$_SESSION['estatisticasId']);
                $select->execute();
                foreach($select->fetchAll() as $linha_array){
                    $media = number_format($linha_array['MediaAluno'], 2, '.', ' ');
                    echo "<b>".$media."</b>";
                }
                echo "</div>";
                echo "</div>";
                echo "<h2>Palavras-chaves mais usadas para descrever a disciplina</h2>";
                require '../../../camadaDados/python_path.php';
    		    $palavrasChave = shell_exec($python." ".$pythonFile." ".$_SESSION['estatisticasId']);
    		    $palavrasChave = utf8_encode($palavrasChave);
                $palavrasChave = explode(" ", $palavrasChave);
                foreach($palavrasChave as $palavra){
					echo $palavra."<br/>";
                }
                unset($_SESSION['estatisticasId']);



            }else if($_SESSION['estatisticasId'] == 0){
                echo "<div class='grid' id='gridEstatisticasMediasGeral'>";
                echo "<div id='mediaDisciplinaGeral'>";
                echo "<h2>Média geral das disciplinas</h2>";
                $result="SELECT AVG(NotaDisciplina) 'MediaDisciplina' FROM $db.$TB_CRITICA";
                $select=$conx->prepare($result);
                $select->execute();
                foreach($select->fetchAll() as $linha_array){
                    $media = number_format($linha_array['MediaDisciplina'], 2, '.', ' ');
                    echo "<b>".$media."</b>";
                }
                echo "</div>";
                echo "<div id='mediaEvolucaoGeral'>";
                echo "<h2>Média geral de evolução dos alunos</h2>";
                $result="SELECT AVG(NotaEvolucao) 'MediaEvolucao' FROM $db.$TB_CRITICA";
                $select=$conx->prepare($result);
                $select->execute();
                foreach($select->fetchAll() as $linha_array){
                    $media = number_format($linha_array['MediaEvolucao'], 2, '.', ' ');
                    echo "<b>".$media."</b>";
                }
                echo "</div>";
                echo "<div id='mediaAlunoGeral'>";
                echo "<h2>Média geral de auto-avaliação dos alunos</h2>";
                $result="SELECT AVG(NotaAluno) 'MediaAluno' FROM $db.$TB_CRITICA";
                $select=$conx->prepare($result);
                $select->execute();
                foreach($select->fetchAll() as $linha_array){
                    $media = number_format($linha_array['MediaAluno'], 2, '.', ' ');
                    echo "<b>".$media."</b>";
                }
                echo "</div>";
                echo "</div>";

                echo "<script>estatisticasId = 0</script>";

                $result = "Select AVG(NotaDisciplina) 'MediaNotaDisciplina',ProfessorDisciplina_idProfessorDisciplina from $db.$TB_CRITICA group by ProfessorDisciplina_idProfessorDisciplina order by AVG(NotaDisciplina) desc Limit 10;";
                $select = $conx->prepare($result);
                $select->execute();
                foreach($select->fetchAll() as $linha_array){
                    array_push($labelMediaNotaDisciplina , $linha_array['ProfessorDisciplina_idProfessorDisciplina']);
                    array_push($valoresMediaNotaDisciplina, $linha_array['MediaNotaDisciplina']);
                }
                $in = implode(',', array_fill(0, count($labelMediaNotaDisciplina ), '?'));
                $result = "SELECT PD1.idProfessorDisciplina, D1.Nome 'DisciplinaNome',U1.Nome 'ProfessorNome', PD1.Periodo, PD1.DiaSemana FROM $db.$TB_PROFESSORDISCIPLINA PD1 inner join $db.$TB_DISCIPLINA D1 ON PD1.Disciplina_idDisciplina = D1.idDisciplina inner join $db.$TB_PROFESSOR P1 On P1.idProfessor = PD1.Professor_idProfessor inner join $db.$TB_USUARIO U1 on P1.Usuario_idUsuario = U1.idUsuario inner join $db.$TB_CURSODISCIPLINA CD1 ON CD1.Disciplina_idDisciplina = D1.idDisciplina where PD1.idProfessorDisciplina IN(".$in.") order by PD1.idProfessorDisciplina";
                $select = $conx->prepare($result);
                foreach ($labelMediaNotaDisciplina as $indice => $id){
                    $select->bindValue(($indice+1), $id);}
                $select->execute();
                $labelExplicadoMediaNotaDisciplina = [];
                foreach($select->fetchAll() as $linha_array){
                    $disciplina = $linha_array['DisciplinaNome'];
                    $professor = $linha_array['ProfessorNome'];
                    $id = $linha_array['idProfessorDisciplina'];
                    $periodo = periodo($linha_array['Periodo']);
                    $diaSemana = diaSemana($linha_array['DiaSemana']);
                    array_push($labelExplicadoMediaNotaDisciplina,$id." - ".$disciplina." - ".$professor." - ".$periodo." - ".$diaSemana);
                }

                $result = "Select AVG(NotaEvolucao) 'MediaNotaEvolucao',ProfessorDisciplina_idProfessorDisciplina from $db.$TB_CRITICA group by ProfessorDisciplina_idProfessorDisciplina order by AVG(NotaEvolucao) desc Limit 10;";
                $select = $conx->prepare($result);
                $select->execute();
                foreach($select->fetchAll() as $linha_array){
                    array_push($labelMediaNotaEvolucao , $linha_array['ProfessorDisciplina_idProfessorDisciplina']);
                    array_push($valoresMediaNotaEvolucao, $linha_array['MediaNotaEvolucao']);
                }
                $in = implode(',', array_fill(0, count($labelMediaNotaEvolucao ), '?'));
                $result = "SELECT PD1.idProfessorDisciplina, D1.Nome 'DisciplinaNome',U1.Nome 'ProfessorNome', PD1.Periodo, PD1.DiaSemana FROM $db.$TB_PROFESSORDISCIPLINA PD1 inner join $db.$TB_DISCIPLINA D1 ON PD1.Disciplina_idDisciplina = D1.idDisciplina inner join $db.$TB_PROFESSOR P1 On P1.idProfessor = PD1.Professor_idProfessor inner join $db.$TB_USUARIO U1 on P1.Usuario_idUsuario = U1.idUsuario inner join $db.$TB_CURSODISCIPLINA CD1 ON CD1.Disciplina_idDisciplina = D1.idDisciplina where PD1.idProfessorDisciplina IN(".$in.") order by PD1.idProfessorDisciplina";
                $select = $conx->prepare($result);
                foreach ($labelMediaNotaEvolucao as $indice => $id){
                    $select->bindValue(($indice+1), $id);}
                $select->execute();
                $labelExplicadoMediaNotaEvolucao = [];
                foreach($select->fetchAll() as $linha_array){
                    $disciplina = $linha_array['DisciplinaNome'];
                    $professor = $linha_array['ProfessorNome'];
                    $id = $linha_array['idProfessorDisciplina'];
                    $periodo = periodo($linha_array['Periodo']);
                    $diaSemana = diaSemana($linha_array['DiaSemana']);
                    array_push($labelExplicadoMediaNotaEvolucao,$id." - ".$disciplina." - ".$professor." - ".$periodo." - ".$diaSemana);
                }

                $result = "Select AVG(NotaAluno) 'MediaNotaAluno',ProfessorDisciplina_idProfessorDisciplina from $db.$TB_CRITICA group by ProfessorDisciplina_idProfessorDisciplina order by AVG(NotaAluno) desc Limit 10;";
                $select = $conx->prepare($result);
                $select->execute();
                foreach($select->fetchAll() as $linha_array){
                    array_push($labelMediaNotaAluno , $linha_array['ProfessorDisciplina_idProfessorDisciplina']);
                    array_push($valoresMediaNotaAluno, $linha_array['MediaNotaAluno']);
                }
                $in = implode(',', array_fill(0, count($labelMediaNotaAluno ), '?'));
                $result = "SELECT PD1.idProfessorDisciplina, D1.Nome 'DisciplinaNome',U1.Nome 'ProfessorNome', PD1.Periodo, PD1.DiaSemana FROM $db.$TB_PROFESSORDISCIPLINA PD1 inner join $db.$TB_DISCIPLINA D1 ON PD1.Disciplina_idDisciplina = D1.idDisciplina inner join $db.$TB_PROFESSOR P1 On P1.idProfessor = PD1.Professor_idProfessor inner join $db.$TB_USUARIO U1 on P1.Usuario_idUsuario = U1.idUsuario inner join $db.$TB_CURSODISCIPLINA CD1 ON CD1.Disciplina_idDisciplina = D1.idDisciplina where PD1.idProfessorDisciplina IN(".$in.") order by PD1.idProfessorDisciplina";
                $select = $conx->prepare($result);
                foreach ($labelMediaNotaAluno as $indice => $id){
                    $select->bindValue(($indice+1), $id);}
                $select->execute();
                $labelExplicadoMediaNotaAluno = [];
                foreach($select->fetchAll() as $linha_array){
                    $disciplina = $linha_array['DisciplinaNome'];
                    $professor = $linha_array['ProfessorNome'];
                    $id = $linha_array['idProfessorDisciplina'];
                    $periodo = periodo($linha_array['Periodo']);
                    $diaSemana = diaSemana($linha_array['DiaSemana']);
                    array_push($labelExplicadoMediaNotaAluno,$id." - ".$disciplina." - ".$professor." - ".$periodo." - ".$diaSemana);
                }
                unset($_SESSION['estatisticasId']);
            }
        ?>
    </form>
    <script src="../../../js/chart.js/dist/chart.js"></script>
        <div id="divGraficosGeral">
            <h2>Média disciplina</h2>
            <div class='grid' id="graficoMediaDisciplinaGeral">
                <div id="graficoMediaDisciplinaGeralGrafico">
                    <div class="containerChartMediaNotaDisciplina" style="height:200px; width:400px">
                        <canvas id="chartMediaNotaDisciplina" width="100" height="100"></canvas>
                    </div>
                </div>
                <?php
                    if($labelExplicadoMediaNotaDisciplina != 0){
                        echo "<ul id=labelExplicadoMediaNotaDisciplina>";
                        foreach($labelExplicadoMediaNotaDisciplina as $label){
                            echo "<li>".$label."</li>";
                        }
                        echo "</ul>";
                    }
                ?>
            </div>
            <div id="push2"></div>

            <h2>Média evolução</h2>
            <div class='grid' id="graficoMediaEvolucaoGeral">
                <div id="graficoMediaEvolucaoGeralGrafico">
                    <div class="containerChartMediaNotaEvolucao" style="height:200px; width:400px">
                        <canvas id="chartMediaNotaEvolucao" width="100" height="100"></canvas>
                    </div>
                </div>
                <?php
                    if($labelExplicadoMediaNotaEvolucao != 0){
                        echo "<ul id=labelExplicadoMediaNotaEvolucao>";
                        foreach($labelExplicadoMediaNotaEvolucao as $label){
                            echo "<li>".$label."</li>";
                        }
                        echo "</ul>";
                    }
                ?>
            </div>
            <div id="push2"></div>

            <h2>Média de auto-avaliação dos alunos</h2>
            <div class='grid' id="graficoMediaAlunoGeral">
                <div id="graficoMediaAlunoGeralGrafico">
                    <div class="containerChartMediaNotaAluno" style="height:200px; width:400px">
                        <canvas id="chartMediaNotaAluno" width="100" height="100"></canvas>
                    </div>
                </div>
                <?php
                    if($labelExplicadoMediaNotaAluno != 0){
                        echo "<ul id=labelExplicadoMediaNotaAluno>";
                        foreach($labelExplicadoMediaNotaAluno as $label){
                            echo "<li>".$label."</li>";
                        }
                        echo "</ul>";
                    }
                ?>
            </div>
        </div>
    <?php
        echo "<script>var divGraficosGeral = document.getElementById('divGraficosGeral')</script>";
        echo "<script>divGraficosGeral.style.display = 'none'</script>";
    ?>
    <script type="text/javascript">
        try{
            if(estatisticasId == 0){
                divGraficosGeral.style.display = 'block';
                var labels = <?php echo json_encode($labelMediaNotaDisciplina); ?>;
                var data = <?php echo json_encode($valoresMediaNotaDisciplina); ?>;
                const ctxMediaNotaDisciplina = document.getElementById('chartMediaNotaDisciplina');
                const chartMediaNotaDisciplina = new Chart(ctxMediaNotaDisciplina, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Média da disciplina',
                            data: data,
                            borderWidth: 1}]}})
                var labels = <?php echo json_encode($labelMediaNotaEvolucao); ?>;
                var data = <?php echo json_encode($valoresMediaNotaEvolucao); ?>;
                const ctxMediaNotaEvolucao = document.getElementById('chartMediaNotaEvolucao');
                const chartMediaNotaEvolucao = new Chart(ctxMediaNotaEvolucao, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Média da evolução dos alunos',
                            data: data,
                            borderWidth: 1}]}})               
                            var labels = <?php echo json_encode($labelMediaNotaAluno); ?>;
                var data = <?php echo json_encode($valoresMediaNotaAluno); ?>;
                const ctxMediaNotaAluno = document.getElementById('chartMediaNotaAluno');
                const chartMediaNotaAluno = new Chart(ctxMediaNotaAluno, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Média da auto-avaliação dos alunos',
                            data: data,
                            borderWidth: 1}]}})  
                }}             
        catch{}
    </script>
    <script>
        function mudaDisciplina(){
            document.getElementById('disciplina').value = document.getElementById('disciplinaSelect').value;
        }
    </script>
    <div id="push"></div>
    <div id="footer"></div>    
</body>
</html>