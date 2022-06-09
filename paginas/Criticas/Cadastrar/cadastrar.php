<?php
session_start();
if (!isset($_SESSION['idUsuarioLogin'])) {
    header('location:../../Login/index.php');
} ?>
<?php
require '../../../camadaDados/conectar.php';
require '../../../camadaDados/tabelas.php';
$idCurso = "%%";
if ($_SESSION['tipoLogin'] == 2) {
    $result = "SELECT A1.Curso_idCurso FROM $db.$TB_ALUNO A1 where A1.Usuario_idUsuario=:id";
    $select = $conx->prepare($result);
    $select->bindParam(':id', $_SESSION['idUsuarioLogin']);
    $select->execute();
    foreach ($select->fetchAll() as $linha_array) {
        $idCurso = $linha_array['Curso_idCurso'];
    }
}
$result = "SELECT distinct D1.Código, D1.Sigla, PD1.idProfessorDisciplina, D1.Nome 'DisciplinaNome',U1.Nome 'ProfessorNome', PD1.Periodo, PD1.DiaSemana FROM $db.$TB_PROFESSORDISCIPLINA PD1 inner join $db.$TB_DISCIPLINA  D1 ON PD1.Disciplina_idDisciplina = D1.idDisciplina inner join $db.$TB_PROFESSOR  P1 On P1.idProfessor = PD1.Professor_idProfessor inner join $db.$TB_USUARIO U1 on P1.Usuario_idUsuario = U1.idUsuario where D1.Curso_idCurso like :id";
$select = $conx->prepare($result);
$select->bindParam(':id', $idCurso);
$select->execute();
$_SESSION['queryProfessorDisciplinaCriticas1'] = $select->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../../../css/bootstrap-4.6.1-dist/bootstrap-4.6.1-dist/css/bootstrap.css">
	<link rel ="stylesheet" href="../../../css/bootstrap-select-1.13.14/bootstrap-select-1.13.14/dist/css/bootstrap-select.min.css"/>
    <script src="../../../js/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../../../css/css.css" />

    <script type="module" src="../../../js/componentes.js"></script>

    <title>Projeto IES301</title>
</head>

<body>
    <?php
    if ($_SESSION['administradorLogin']) {
        echo "<div id='menu' class='menu-adm'></div>";
    } else {
        echo "<div id='menu'></div>";
    }
    ?>
    <div id="navbar"></div>
    <h1>Cadastrar crítica</h1>
    <button class="button btnVoltar button-go-return"><span class="material-icons button-go-return">reply</span><a class="button-go-return" href="../index.php">Voltar</a></button><br />
    <form action="php.php" method="POST" class="form-critica">
        <?php
        echo '<label id="labelDisciplina" for="disciplinaSelect"> Disciplina: ';
        echo '<select id="disciplinaSelect" class="selectpicker" data-size="10" data-live-search="true" onchange="mudaDisciplina()">';
        $idPrimeiro = 0;
        foreach ($_SESSION['queryProfessorDisciplinaCriticas1'] as $linha_array) {
            $codigo = $linha_array['Código'];
            $disciplina = $linha_array['DisciplinaNome'];
            $professor = $linha_array['ProfessorNome'];
            $id = $linha_array['idProfessorDisciplina'];
			$sigla = $linha_array['Sigla'];
            if ($idPrimeiro == 0) {
                $idPrimeiro = $id;
            }
            $periodo = $linha_array['Periodo'];
            $diaSemana = $linha_array['DiaSemana'];
            if ($diaSemana == 2) {
                $diaSemana = 'Segunda-feira';
            } else if ($diaSemana == 3) {
                $diaSemana = 'Terça-feira';
            } else if ($diaSemana == 4) {
                $diaSemana = 'Quarta-feira';
            } else if ($diaSemana == 5) {
                $diaSemana = 'Quinta-feira';
            } else if ($diaSemana == 6) {
                $diaSemana = 'Sexta-feira';
            } else {
                $diaSemana = 'Sábado';
            }
            if ($periodo == 0) {
                $periodo = 'Manhã';
            } else if ($periodo == 1) {
                $periodo = 'Tarde';
            } else {
                $periodo = 'Noite';
            }
            echo '<option value='."'$id'".">"."{$disciplina} ({$sigla} : {$codigo}) - {$professor} ({$periodo})"."</option>";
        }
        echo '</select>';
        echo '</label>';
        foreach ($_SESSION['queryProfessorDisciplinaCriticas1'] as $linha_array) {
            echo '<input type="hidden" id="disciplina" name="disciplina" value=' . "'$idPrimeiro'" . "/>";
            break;
        }
        ?>
        <section id="section-notas-ano-semestre">
            <label for="notaDisciplina">Nota para a disciplina: </label><input class="inputNota" type="number" placeholder="1-5" name="notaDisciplina" id="notaDisciplina" min="1" max="5" required>
            <label for="notaEvolucao">Nota para sua evolução: </label><input class="inputNota" type="number" placeholder="1-5" name="notaEvolucao" id="notaEvolucao" min="1" max="5" required>
            <label for="notaAluno">Nota para você: </label><input class="inputNota" type="number" placeholder="1-5" name="notaAluno" id="notaAluno" min="1" max="5" required>
            <label for="ano">Ano de conclusão da disciplina: </label><input class="inputAno" type="number" placeholder="XXXX" name="ano" id="ano" min="1973" max="2099" required> 
            <label for="semestre">Semestre de conclusão da disciplina: </label><input class="inputSemestre" type="number" placeholder="1-2" name="semestre" id="semestre" min="1" max="2" required>
        </section>
       
        <h2>Elogios para o professor (máximo 3):</h2>
        <div class="gradeElogiosCriticasContainer">
            <div class="gradeElogiosCriticas">
                <label for="checkElogioCarismatico"><input id="checkElogioCarismatico" name="checkElogio[]" class="checkElogio" type="checkbox" value="Carismático" onchange="checkQuantidadeElogios('checkElogioCarismatico')">Carismático</label>
                <label for="checkElogioExplicacao"><input id="checkElogioExplicacao" name="checkElogio[]" class="checkElogio" type="checkbox" value="Explicação" onchange="checkQuantidadeElogios('checkElogioExplicacao')">Explicação</label>
                <label for="checkElogioMaterial"><input id="checkElogioMaterial" name="checkElogio[]" class="checkElogio" type="checkbox" value="Material" onchange="checkQuantidadeElogios('checkElogioMaterial')">Material</label>
                <label for="checkElogioOrganizacao"><input id="checkElogioOrganizacao" name="checkElogio[]" class="checkElogio" type="checkbox" value="Organização" onchange="checkQuantidadeElogios('checkElogioOrganizacao')">Organização</label>
                <label for="checkElogioPontualidade"><input id="checkElogioPontualidade" name="checkElogio[]" class="checkElogio" type="checkbox" value="Pontualidade" onchange="checkQuantidadeElogios('checkElogioPontualidade')">Pontualidade</label>
                <label for="checkElogioPrestativo"><input id="checkElogioPrestativo" name="checkElogio[]" class="checkElogio" type="checkbox" value="Prestativo" onchange="checkQuantidadeElogios('checkElogioPrestativo')">Prestativo</label>
            </div>
        </div>
        <p id="mensagemErroElogios"></p>
        
        <h2 style="margin-top: 0">Críticas/Áreas de melhoria para o professor (máximo 3):</h2>
        <div class="gradeElogiosCriticasContainer">
            <div class="gradeElogiosCriticas">
                <label for="checkCriticaComunicacao"><input id="checkCriticaComunicacao" name="checkCritica[]" class="checkCritica" type="checkbox" value="Comunicação" onchange="checkQuantidadeCriticas('checkCriticaComunicacao')">Comunicação</label>
                <label for="checkCriticaExplicacao"><input id="checkCriticaExplicacao" name="checkCritica[]" class="checkCritica" type="checkbox" value="Explicação" onchange="checkQuantidadeCriticas('checkCriticaExplicacao')">Explicação</label>
                <label for="checkCriticaMaterial"><input id="checkCriticaMaterial" name="checkCritica[]" class="checkCritica" type="checkbox" value="Material" onchange="checkQuantidadeCriticas('checkCriticaMaterial')">Material</label>
                <label for="checkCriticaMetodo"><input id="checkCriticaMetodo" name="checkCritica[]" class="checkCritica" type="checkbox" value="Método de avaliação" onchange="checkQuantidadeCriticas('checkCriticaMetodo')">Método de avaliação</label>
                <label for="checkCriticaOrganizacao"><input id="checkCriticaOrganizacao" name="checkCritica[]" class="checkCritica" type="checkbox" value="Organização" onchange="checkQuantidadeCriticas('checkCriticaOrganizacao')">Organização</label>
                <label for="checkCriticaPontualidade"><input id="checkCriticaPontualidade" name="checkCritica[]" class="checkCritica" type="checkbox" value="Pontualidade" onchange="checkQuantidadeCriticas('checkCriticaPontualidade')">Pontualidade</label>
            </div>
        </div>
        <p id="mensagemErroCriticas"></p>
        
        <label for="descricao"> Comentário mais detalhado: </label><textarea rows="5" id="descricao" name="descricao" placeholder="Comentário..." required maxlength="500"></textarea>
        <button type="submit" name="submit" class="button-create" value="Enviar"><span class="material-icons button-create">add_circle</span>Cadastrar</button>
    </form>
    <div id="push"></div>
    <script>
        function mudaDisciplina() {
            document.getElementById('disciplina').value = document.getElementById('disciplinaSelect').value;
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
<script src="../../../js/node_modules/popper.js/dist/umd/popper.js"></script>
<script src="../../../css/bootstrap-4.6.1-dist/bootstrap-4.6.1-dist/js/bootstrap.min.js"></script>
<script src="../../../css/bootstrap-select-1.13.14/bootstrap-select-1.13.14/dist/js/bootstrap-select.min.js"></script>	
</body>	
</html>