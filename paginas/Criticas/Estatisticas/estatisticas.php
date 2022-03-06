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
            echo "<script>var estatistiscasId = -1</script>";
            $labelMediaNotaDisciplinaGeral = [];
            $labelExplicadoMediaNotaDisciplinaGeral = 0;
            $valoresMediaNotaDisciplinaGeral = [];
            $labelMediaNotaEvolucaoGeral = [];
            $labelExplicadoMediaNotaEvolucaoGeral = 0;
            $valoresMediaNotaEvolucaoGeral = [];
            $labelMediaNotaAlunoGeral = [];
            $labelExplicadoMediaNotaAlunoGeral = 0;
            $valoresMediaNotaAlunoGeral = [];
            $valoresMediaDisciplinaAnoSemestreDisciplina = [];
            $valoresMediaEvolucaoAnoSemestreDisciplina = [];
            $valoresMediaAlunoAnoSemestreDisciplina = [];
            $labelsAnoSemestreMediasDisciplina = [];
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
                $result="SELECT AVG(NotaDisciplina) 'MediaDisciplina',AVG(NotaEvolucao) 'MediaEvolucao',AVG(NotaAluno) 'MediaAluno' FROM $db.$TB_CRITICA WHERE ProfessorDisciplina_idProfessorDisciplina = :id";
                $select=$conx->prepare($result);
                $select->bindParam(':id',$_SESSION['estatisticasId']);
                $select->execute();
                $mediaDisciplina = 0;
                $mediaEvolucao = 0;
                $mediaAluno = 0;
                foreach($select->fetchAll() as $linha_array){
                    $mediaDisciplina = number_format($linha_array['MediaDisciplina'], 2, '.', ' ');
                    $mediaEvolucao = number_format($linha_array['MediaEvolucao'], 2, '.', ' ');
                    $mediaAluno = number_format($linha_array['MediaAluno'], 2, '.', ' ');
                }
                echo "<div class='grid' id='gridEstatisticasMediasDisciplina'>";
                echo "<div id='mediaDisciplinaDisciplina'>";
                echo "<h2>Média da disciplina</h2>";
                echo "<b>".$mediaDisciplina."</b>";
                echo "</div>";
                echo "<div id='mediaEvolucaoDisciplina'>";
                echo "<h2>Média de evolução dos alunos</h2>";
                echo "<b>".$mediaEvolucao."</b>";
                echo "</div>";
                echo "<div id='mediaAlunoDisciplina'>";
                echo "<h2>Média de auto-avaliação dos alunos</h2>";
                echo "<b>".$mediaAluno."</b>";
                echo "</div>";
                echo "</div>";
                echo "<div class='grid' id='gridEstatisticasContagemDisciplina'>";
                $result="SELECT Elogios, Criticas FROM $db.$TB_CRITICA WHERE ProfessorDisciplina_idProfessorDisciplina = :id";
                $select=$conx->prepare($result);
                $select->bindParam(':id',$_SESSION['estatisticasId']);
                $select->execute();
                $elogios = [];
                $criticas = [];
                foreach($select->fetchAll() as $linha_array){
                    array_push($elogios, $linha_array['Elogios']);
                    array_push($criticas, $linha_array['Criticas']);
                }
                echo "<div id='contagemElogios'>";
                echo "<h2>Contagem de elogios</h2>";
                $elogiosContagem = [];
                foreach($elogios as $conjuntoElogios){
                    foreach(explode("-",$conjuntoElogios) as $elogio){
                        if($elogio == 'Nenhum'){}
                        else{
                            if(!array_key_exists($elogio,$elogiosContagem)){
                                $elogiosContagem[$elogio] = 1;
                            }else{
                                $elogiosContagem[$elogio] += 1;}}
                    }
                }
                echo "<ul>";
                foreach($elogiosContagem as $elogio=>$contagem){
                    echo "<li>".$elogio.": ".$contagem."</li>";
                }
                echo "</ul>";
                echo "</div>";
                echo "<div id='contagemCriticas'>";
                echo "<h2>Contagem de críticas</h2>";
                $criticasContagem = [];
                foreach($criticas as $conjuntoCriticas){
                    foreach(explode("-",$conjuntoCriticas) as $critica){
                        if($critica == 'Nenhum'){}
                        else{
                            if(!array_key_exists($critica,$criticasContagem)){
                                $criticasContagem[$critica] = 1;
                            }else{
                                $criticasContagem[$critica] += 1;}}
                    }
                }
                echo "<ul>";
                foreach($criticasContagem as $critica=>$contagem){
                    echo "<li>".$critica.": ".$contagem."</li>";
                }
                echo "</div>";
                echo "</div>";
                echo "<div class='grid' id='gridEstatisticasUltimaLinhaDisciplina'>";
                echo "<div id='ultimasCriticas'>";
                echo "<h2>Últimas críticas</h2>";
                $result="SELECT Descrição FROM $db.$TB_CRITICA WHERE ProfessorDisciplina_idProfessorDisciplina = :id and Descrição != '' Order by Data desc limit 3 ";
                $select=$conx->prepare($result);
                $select->bindParam(':id',$_SESSION['estatisticasId']);
                $select->execute();
                foreach($select->fetchAll() as $linha_array){
                    echo "→ ".$linha_array['Descrição'];
                    echo "<br/><br/>";
                }                
                echo "</div>";
                echo "<div id='graficosMediasDisciplina'>";
                echo "<h2>Médias por Ano e Semestre</h2>";
                $result = "SELECT AnoSemestre,AVG(NotaDisciplina) 'MediaDisciplina',AVG(NotaEvolucao) 'MediaEvolucao',AVG(NotaAluno) 'MediaAluno' FROM $db.$TB_CRITICA C1 Where ProfessorDisciplina_idProfessorDisciplina = :id Group by AnoSemestre order by SUBSTRING(C1.AnoSemestre,1 ,4) Desc,SUBSTRING(C1.AnoSemestre,5 ,1) Desc Limit 30";                
                $select=$conx->prepare($result);
                $select->bindParam(':id',$_SESSION['estatisticasId']);
                $select->execute();
                foreach($select->fetchAll() as $linha_array){
                    array_push($valoresMediaDisciplinaAnoSemestreDisciplina, $linha_array['MediaDisciplina'] );
                    array_push($valoresMediaEvolucaoAnoSemestreDisciplina, $linha_array['MediaEvolucao'] );
                    array_push($valoresMediaAlunoAnoSemestreDisciplina, $linha_array['MediaAluno'] );
                    array_push($labelsAnoSemestreMediasDisciplina,substr($linha_array['AnoSemestre'], 0, 4)."-".substr($linha_array['AnoSemestre'], 4, 1));
                }
                    echo '<div class="containerChartMediasDisciplina" style="height:200px; width:400px">';
                        echo '<canvas id="chartMediasDisciplina" width="100" height="100"></canvas>';
                    echo '</div>';
                echo "<script>estatisticasId = 1</script>";
                echo "</div>";
                echo "</div>";
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

                $result = "Select AVG(NotaDisciplina) 'MediaNotaDisciplina',ProfessorDisciplina_idProfessorDisciplina from $db.$TB_CRITICA group by ProfessorDisciplina_idProfessorDisciplina order by AVG(NotaDisciplina) desc Limit 8;";
                $select = $conx->prepare($result);
                $select->execute();
                foreach($select->fetchAll() as $linha_array){
                    array_push($labelMediaNotaDisciplinaGeral , $linha_array['ProfessorDisciplina_idProfessorDisciplina']);
                    array_push($valoresMediaNotaDisciplinaGeral, $linha_array['MediaNotaDisciplina']);
                }
                $in = implode(',', array_fill(0, count($labelMediaNotaDisciplinaGeral ), '?'));
                $result = "SELECT PD1.idProfessorDisciplina, D1.Nome 'DisciplinaNome',U1.Nome 'ProfessorNome', PD1.Periodo, PD1.DiaSemana FROM $db.$TB_PROFESSORDISCIPLINA PD1 inner join $db.$TB_DISCIPLINA D1 ON PD1.Disciplina_idDisciplina = D1.idDisciplina inner join $db.$TB_PROFESSOR P1 On P1.idProfessor = PD1.Professor_idProfessor inner join $db.$TB_USUARIO U1 on P1.Usuario_idUsuario = U1.idUsuario inner join $db.$TB_CURSODISCIPLINA CD1 ON CD1.Disciplina_idDisciplina = D1.idDisciplina where PD1.idProfessorDisciplina IN(".$in.") order by PD1.idProfessorDisciplina";
                $select = $conx->prepare($result);
                foreach ($labelMediaNotaDisciplinaGeral as $indice => $id){
                    $select->bindValue(($indice+1), $id);}
                $select->execute();
                $labelExplicadoMediaNotaDisciplinaGeral = [];
                foreach($select->fetchAll() as $linha_array){
                    $disciplina = $linha_array['DisciplinaNome'];
                    $professor = $linha_array['ProfessorNome'];
                    $id = $linha_array['idProfessorDisciplina'];
                    $periodo = periodo($linha_array['Periodo']);
                    $diaSemana = diaSemana($linha_array['DiaSemana']);
                    array_push($labelExplicadoMediaNotaDisciplinaGeral,$id." - ".$disciplina." - ".$professor." - ".$periodo." - ".$diaSemana);
                }

                $result = "Select AVG(NotaEvolucao) 'MediaNotaEvolucao',ProfessorDisciplina_idProfessorDisciplina from $db.$TB_CRITICA group by ProfessorDisciplina_idProfessorDisciplina order by AVG(NotaEvolucao) desc Limit 8;";
                $select = $conx->prepare($result);
                $select->execute();
                foreach($select->fetchAll() as $linha_array){
                    array_push($labelMediaNotaEvolucaoGeral , $linha_array['ProfessorDisciplina_idProfessorDisciplina']);
                    array_push($valoresMediaNotaEvolucaoGeral, $linha_array['MediaNotaEvolucao']);
                }
                $in = implode(',', array_fill(0, count($labelMediaNotaEvolucaoGeral ), '?'));
                $result = "SELECT PD1.idProfessorDisciplina, D1.Nome 'DisciplinaNome',U1.Nome 'ProfessorNome', PD1.Periodo, PD1.DiaSemana FROM $db.$TB_PROFESSORDISCIPLINA PD1 inner join $db.$TB_DISCIPLINA D1 ON PD1.Disciplina_idDisciplina = D1.idDisciplina inner join $db.$TB_PROFESSOR P1 On P1.idProfessor = PD1.Professor_idProfessor inner join $db.$TB_USUARIO U1 on P1.Usuario_idUsuario = U1.idUsuario inner join $db.$TB_CURSODISCIPLINA CD1 ON CD1.Disciplina_idDisciplina = D1.idDisciplina where PD1.idProfessorDisciplina IN(".$in.") order by PD1.idProfessorDisciplina";
                $select = $conx->prepare($result);
                foreach ($labelMediaNotaEvolucaoGeral as $indice => $id){
                    $select->bindValue(($indice+1), $id);}
                $select->execute();
                $labelExplicadoMediaNotaEvolucaoGeral = [];
                foreach($select->fetchAll() as $linha_array){
                    $disciplina = $linha_array['DisciplinaNome'];
                    $professor = $linha_array['ProfessorNome'];
                    $id = $linha_array['idProfessorDisciplina'];
                    $periodo = periodo($linha_array['Periodo']);
                    $diaSemana = diaSemana($linha_array['DiaSemana']);
                    array_push($labelExplicadoMediaNotaEvolucaoGeral,$id." - ".$disciplina." - ".$professor." - ".$periodo." - ".$diaSemana);
                }

                $result = "Select AVG(NotaAluno) 'MediaNotaAluno',ProfessorDisciplina_idProfessorDisciplina from $db.$TB_CRITICA group by ProfessorDisciplina_idProfessorDisciplina order by AVG(NotaAluno) desc Limit 8;";
                $select = $conx->prepare($result);
                $select->execute();
                foreach($select->fetchAll() as $linha_array){
                    array_push($labelMediaNotaAlunoGeral , $linha_array['ProfessorDisciplina_idProfessorDisciplina']);
                    array_push($valoresMediaNotaAlunoGeral, $linha_array['MediaNotaAluno']);
                }
                $in = implode(',', array_fill(0, count($labelMediaNotaAlunoGeral ), '?'));
                $result = "SELECT PD1.idProfessorDisciplina, D1.Nome 'DisciplinaNome',U1.Nome 'ProfessorNome', PD1.Periodo, PD1.DiaSemana FROM $db.$TB_PROFESSORDISCIPLINA PD1 inner join $db.$TB_DISCIPLINA D1 ON PD1.Disciplina_idDisciplina = D1.idDisciplina inner join $db.$TB_PROFESSOR P1 On P1.idProfessor = PD1.Professor_idProfessor inner join $db.$TB_USUARIO U1 on P1.Usuario_idUsuario = U1.idUsuario inner join $db.$TB_CURSODISCIPLINA CD1 ON CD1.Disciplina_idDisciplina = D1.idDisciplina where PD1.idProfessorDisciplina IN(".$in.") order by PD1.idProfessorDisciplina";
                $select = $conx->prepare($result);
                foreach ($labelMediaNotaAlunoGeral as $indice => $id){
                    $select->bindValue(($indice+1), $id);}
                $select->execute();
                $labelExplicadoMediaNotaAlunoGeral = [];
                foreach($select->fetchAll() as $linha_array){
                    $disciplina = $linha_array['DisciplinaNome'];
                    $professor = $linha_array['ProfessorNome'];
                    $id = $linha_array['idProfessorDisciplina'];
                    $periodo = periodo($linha_array['Periodo']);
                    $diaSemana = diaSemana($linha_array['DiaSemana']);
                    array_push($labelExplicadoMediaNotaAlunoGeral,$id." - ".$disciplina." - ".$professor." - ".$periodo." - ".$diaSemana);
                }
                unset($_SESSION['estatisticasId']);
            }
        ?>
    </form>
    <script src="../../../js/chart.js/dist/chart.js"></script>
        <div id="divGraficosGeral">
            <h2>Melhores médias de disciplina</h2>
            <div class='grid' id="graficoMediaDisciplinaGeral">
                <div id="graficoMediaDisciplinaGeralGrafico">
                    <div class="containerChartMediaNotaDisciplinaGeral" style="height:200px; width:400px">
                        <canvas id="chartMediaNotaDisciplinaGeral" width="100" height="100"></canvas>
                    </div>
                </div>
                <?php
                    if($labelExplicadoMediaNotaDisciplinaGeral != 0){
                        echo "<ul id=labelExplicadoMediaNotaDisciplinaGeral>";
                        foreach($labelExplicadoMediaNotaDisciplinaGeral as $label){
                            echo "<li>".$label."</li>";
                        }
                        echo "</ul>";
                    }
                ?>
            </div>
            <div id="push2"></div>

            <h2>Melhores médias de evolução</h2>
            <div class='grid' id="graficoMediaEvolucaoGeral">
                <div id="graficoMediaEvolucaoGeralGrafico">
                    <div class="containerchartMediaNotaEvolucaoGeral" style="height:200px; width:400px">
                        <canvas id="chartMediaNotaEvolucaoGeral" width="100" height="100"></canvas>
                    </div>
                </div>
                <?php
                    if($labelExplicadoMediaNotaEvolucaoGeral != 0){
                        echo "<ul id=labelExplicadoMediaNotaEvolucaoGeral>";
                        foreach($labelExplicadoMediaNotaEvolucaoGeral as $label){
                            echo "<li>".$label."</li>";
                        }
                        echo "</ul>";
                    }
                ?>
            </div>
            <div id="push2"></div>

            <h2>Melhores médias de auto-avaliação dos alunos</h2>
            <div class='grid' id="graficoMediaAlunoGeral">
                <div id="graficoMediaAlunoGeralGrafico">
                    <div class="containerchartMediaNotaAlunoGeral" style="height:200px; width:400px">
                        <canvas id="chartMediaNotaAlunoGeral" width="100" height="100"></canvas>
                    </div>
                </div>
                <?php
                    if($labelExplicadoMediaNotaAlunoGeral != 0){
                        echo "<ul id=labelExplicadoMediaNotaAlunoGeral>";
                        foreach($labelExplicadoMediaNotaAlunoGeral as $label){
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
		function gerarCores(tamanho){
            var cores = [];
            for(var i=0;i<tamanho;i++){
                cores.push(`rgba(${Math.random() * 256},${Math.random() * 256},${Math.random() * 256},1)`)
            }
            return cores;
		}
        try{
            if(estatisticasId == 1){
                var labels = (<?php echo json_encode($labelsAnoSemestreMediasDisciplina); ?>).reverse();
                var dataMediaDisciplina = (<?php echo json_encode($valoresMediaDisciplinaAnoSemestreDisciplina); ?>).reverse();
                var dataMediaEvolucao = (<?php echo json_encode($valoresMediaEvolucaoAnoSemestreDisciplina); ?>).reverse();
                var dataMediaAluno = (<?php echo json_encode($valoresMediaAlunoAnoSemestreDisciplina); ?>).reverse();
                var cores = gerarCores(3);
                const ctxMediasDisciplina = document.getElementById('chartMediasDisciplina');
                const chartMediasDisciplina = new Chart(ctxMediasDisciplina, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Média da disciplina',
                                data: dataMediaDisciplina,
                                backgroundColor: cores[0],
                                borderColor: cores[0],
                                borderWidth: 1
                            },
                            {
                                label: 'Média de evolução',
                                data: dataMediaEvolucao,
                                backgroundColor: cores[1],
                                borderColor: cores[1],
                                borderWidth: 1
                            },
                            {
                                label: 'Média de auto-avaliação dos alunos',
                                data: dataMediaAluno,
                                backgroundColor: cores[2],
                                borderColor: cores[2],
                                borderWidth: 1
                            }]}})
            }
            else if(estatisticasId == 0){
                divGraficosGeral.style.display = 'block';
                var labels = <?php echo json_encode($labelMediaNotaDisciplinaGeral); ?>;
                var data = <?php echo json_encode($valoresMediaNotaDisciplinaGeral); ?>;
                const ctxMediaNotaDisciplinaGeral = document.getElementById('chartMediaNotaDisciplinaGeral');
                const chartMediaNotaDisciplinaGeral = new Chart(ctxMediaNotaDisciplinaGeral, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Média da disciplina',
                            data: data,
                            backgroundColor: gerarCores(labels.length),
                            borderWidth: 1}]}})
                var labels = <?php echo json_encode($labelMediaNotaEvolucaoGeral); ?>;
                var data = <?php echo json_encode($valoresMediaNotaEvolucaoGeral); ?>;
                const ctxMediaNotaEvolucaoGeral = document.getElementById('chartMediaNotaEvolucaoGeral');
                const chartMediaNotaEvolucaoGeral = new Chart(ctxMediaNotaEvolucaoGeral, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Média da evolução dos alunos',
                            data: data,
                            backgroundColor: gerarCores(labels.length),
                            borderWidth: 1}]}})               
                var labels = <?php echo json_encode($labelMediaNotaAlunoGeral); ?>;
                var data = <?php echo json_encode($valoresMediaNotaAlunoGeral); ?>;
                const ctxMediaNotaAlunoGeral = document.getElementById('chartMediaNotaAlunoGeral');
                const chartMediaNotaAlunoGeral = new Chart(ctxMediaNotaAlunoGeral, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Média da auto-avaliação dos alunos',
                            data: data,
                            backgroundColor: gerarCores(labels.length),
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