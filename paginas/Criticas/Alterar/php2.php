<?php
session_start();
$variavelControle = 1;
if (!isset($_SESSION['idUsuarioLogin'])) {
    header('location:../../Login/index.php');
}
require '../../../camadaDados/conectar.php';
require '../../../camadaDados/tabelas.php';
//função para validar elogio
function elogioValidar($elogio)
{
    if (in_array($elogio, ['Nenhum', 'Explicação', 'Material', 'Organização', 'Pontualidade', 'Prestativo', 'Carismático'])) {
        return $elogio;
    } else {
        return 'Nenhum';
    }
}
//função para validar critica
function criticaValidar($critica)
{
    if (in_array($critica, ['Nenhum', 'Explicação', 'Material', 'Organização', 'Pontualidade', 'Comunicação', 'Método de avaliação'])) {
        return $critica;
    } else {
        return 'Nenhum';
    }
}
//receber id da critica
$send = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_STRING);
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
if ($id != $_SESSION['idAlteracao6']) {
    $id = $_SESSION['idAlteracao6'];
    unset($_SESSION['idAlteracao6']);
}
//receber outros parâmetros
$notaAluno = filter_input(INPUT_POST, 'notaAluno', FILTER_SANITIZE_NUMBER_INT);
if (!is_numeric($notaAluno) || $notaAluno > 5 || $notaAluno < 1) {
    $notaAluno = 3;
}
$notaEvolucao = filter_input(INPUT_POST, 'notaEvolucao', FILTER_SANITIZE_NUMBER_INT);
if (!is_numeric($notaEvolucao) || $notaEvolucao > 5 || $notaEvolucao < 1) {
    $notaEvolucao = 3;
}
$notaDisciplina = filter_input(INPUT_POST, 'notaDisciplina', FILTER_SANITIZE_NUMBER_INT);
if (!is_numeric($notaDisciplina) || $notaDisciplina > 5 || $notaDisciplina < 1) {
    $notaDisciplina = 3;
}
$descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
if (strlen($descricao) > 500) {
    $descricao = 'descricao';
}
$ano = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_NUMBER_INT);
if (!is_numeric($ano) || $ano > 2099 || $ano < 1973) {
    $ano = 1973;
}
$semestre = filter_input(INPUT_POST, 'semestre', FILTER_SANITIZE_NUMBER_INT);
if (!is_numeric($semestre) || $semestre > 2 || $semestre < 1) {
    $semestre = 1;
}
$anoSemestre = $ano . "" . $semestre;

$elogios = $_POST['checkElogio'];

if ($elogios !== null) {
    $elogiosInvalidos = false;

    //Verifica se há algum elogio estranho
    for ($i = 0; $i < count($elogios); $i++) {
        if (strcmp(elogioValidar($elogios[$i]), "Nenhum") == 0) {
            $elogiosInvalidos = true;
            break;
        }
    }

    //Verifica se há mais de 3 elogios
    if (count($elogios) > 3) {
        $elogiosInvalidos = true;
    }

    //Verifica se há elogios repetidos
    if (count($elogios) !== count(array_unique($elogios))) {
        $elogiosInvalidos = true;
    }

    if ($elogiosInvalidos) {
        $_SESSION['mensagemErro'] = 'Ocorreu um erro ao alterar os elogios.';
        header('location: ../index.php');
        $variavelControle = 0;
        exit();
    }
}

if ($elogios === null) {
    $elogios = [];
}

while (count($elogios) < 3) {
    array_push($elogios, "Nenhum");
}

$criticas = $_POST['checkCritica'];

if ($criticas !== null) {
    $criticasInvalidas = false;
    //Verifica se há alguma crítica estranha
    for ($i = 0; $i < count($criticas); $i++) {
        if (strcmp(criticaValidar($criticas[$i]), "Nenhum") == 0) {
            $criticasInvalidas = true;
            break;
        }
    }

    //Verifica se há mais de 3 críticas
    if (count($criticas) > 3) {
        $criticasInvalidas = true;
    }

    //Verifica se há críticas repetidos
    if (count($criticas) !== count(array_unique($criticas))) {
        $criticasInvalidas = true;
    }

    if ($criticasInvalidas) {
        $_SESSION['mensagemErro'] = 'Ocorreu um erro ao alterar as críticas/pontos de melhoria.';
        header('location: ../index.php');
        $variavelControle = 0;
        exit();
    }
}

if ($criticas === null) {
    $criticas = [];
}

while (count($criticas) < 3) {
    array_push($criticas, "Nenhum");
}


//montar a string de elogios
$elogiosString = $elogios[0] . "-" . $elogios[1] . "-" . $elogios[2];
//montar a string de críticas
$criticasString = $criticas[0] . "-" . $criticas[1] . "-" . $criticas[2];
//pegar id do aluno
$aluno = $_SESSION['idUsuarioLogin'];
$result = "SELECT A1.idAluno FROM $db.$TB_ALUNO A1 inner join $db.$TB_USUARIO U1 ON U1.idUsuario = A1.Usuario_idUsuario WHERE U1.idUsuario = :idUsuario";
$select = $conx->prepare($result);
$select->bindParam(':idUsuario', $aluno);
$select->execute();
$aluno = '';
foreach ($select->fetchAll() as $linha_array) {
    $aluno = $linha_array['idAluno'];
    break;
}
//pegar id da disciplina cadastrada
$disciplina = 0;
$result = "SELECT ProfessorDisciplina_idProfessorDisciplina FROM $db.$TB_CRITICA where idCritica = :idCritica";
$select = $conx->prepare($result);
$select->bindParam(':idCritica', $id);
$select->execute();
foreach ($select->fetchAll() as $linha_array) {
    $disciplina = $linha_array['ProfessorDisciplina_idProfessorDisciplina'];
    break;
}
//controle para ver se a data é válida para a disciplina
$anoQuery = $ano;
if ($semestre == 1) {
    $anoQuery = $anoQuery . "-07-15";
} else {
    $anoQuery = $anoQuery . "-12-31";
}
$result = "SELECT Count(*) 'Quantidade' FROM $db.$TB_PROFESSORDISCIPLINA where idProfessorDisciplina = :idDisciplina and DataInicial <= :data1 and (dataFinal >= :data2 or dataFinal='2100-01-01')";
$select = $conx->prepare($result);
$select->bindParam(':idDisciplina', $disciplina);
$select->bindParam(':data1', $anoQuery);
$select->bindParam(':data2', $anoQuery);
$select->execute();
foreach ($select->fetchAll() as $linha_array) {
    if ($linha_array['Quantidade'] != 1) {
        $variavelControle = 0;
        $_SESSION['mensagemErro'] = 'Data inválida para a disciplina!';
    }
    break;
}
if ($_SESSION['tipoLogin'] != 2 and $send == 'Alterar') {
    $_SESSION['mensagemErro'] = 'Precisa ser aluno para alterar a crítica!';
    $variavelControle = 0;
}
if ($send == 'Cancelar') {
    $_SESSION['mensagemFinalizacao'] = 'Operação cancelada com sucesso!';
    header("Location: ../index.php");
} else if ($send == 'Alterar') {
    try {
        if ($aluno != '' && $variavelControle) {
            $result = "UPDATE $db.$TB_CRITICA SET NotaAluno=:notaAluno,NotaEvolucao=:notaEvolucao,NotaDisciplina=:notaDisciplina,Descrição=:descricao,Data=now(),AnoSemestre=:anoSemestre,Elogios=:elogios,Criticas=:criticas WHERE idCritica = :idCritica and Aluno_idAluno = :idAluno";
            $insert = $conx->prepare($result);
            $insert->bindParam(':notaAluno', $notaAluno);
            $insert->bindParam(':notaEvolucao', $notaEvolucao);
            $insert->bindParam(':notaDisciplina', $notaDisciplina);
            $insert->bindParam(':descricao', $descricao);
            $insert->bindParam(':idCritica', $id);
            $insert->bindParam(':idAluno', $aluno);
            $insert->bindParam(':anoSemestre', $anoSemestre);
            $insert->bindParam(':elogios', $elogiosString);
            $insert->bindParam(':criticas', $criticasString);
            $insert->execute();
            $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';
        }
        header("Location: ../index.php");
    } catch (PDOException $e) {
        $msgErr = "Erro na alteração:<br />";
        $_SESSION['mensagemErro'] = $msgErr;
        header("Location: ../index.php");
    }
} else if ($send == 'Excluir') {
    if ($_SESSION['administradorLogin']) {
        $aluno = "%%";
    }
    $result = "DELETE FROM $db.$TB_CRITICA WHERE idCritica = :idCritica and Aluno_idAluno like :idAluno";
    $delete = $conx->prepare($result);
    $delete->bindParam(':idCritica', $id);
    $delete->bindParam(':idAluno', $aluno);
    $delete->execute();
    $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';
    header("Location: ../index.php");
}
