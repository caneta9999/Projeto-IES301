<?php
require '../../../camadaDados/conectar.php';
require '../../../camadaDados/tabelas.php';
$send = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_STRING);
if (!isset($_SESSION['idUsuarioLogin'])) {
    header('location:../../Login/index.php');
}
//função para validar elogio
function elogioValidar($elogio)
{
    if (in_array($elogio, ['Explicação', 'Material', 'Organização', 'Pontualidade', 'Prestativo', 'Carismático'])) {
        return $elogio;
    } else {
        return 'Nenhum';
    }
}
//função para validar critica
function criticaValidar($critica)
{
    if (in_array($critica, ['Explicação', 'Material', 'Organização', 'Pontualidade', 'Comunicação', 'Método de avaliação'])) {
        return $critica;
    } else {
        return 'Nenhum';
    }
}
//controle para ver se a disciplina é do curso do usuário mesmo
$disciplina = filter_input(INPUT_POST, 'disciplina', FILTER_SANITIZE_NUMBER_INT);
if (!is_numeric($disciplina) || $disciplina > 99999999999 || $disciplina < 1) {
    $disciplina = -1;
}
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
$result = "SELECT PD1.idProfessorDisciplina FROM $db.$TB_PROFESSORDISCIPLINA PD1 inner join $db.$TB_DISCIPLINA D1 ON PD1.Disciplina_idDisciplina = D1.idDisciplina where D1.Curso_idCurso like :id";
$select = $conx->prepare($result);
$select->bindParam(':id', $idCurso);
$select->execute();
$variavelControleExterna = 0;
$_SESSION['mensagemErro'] = "";
foreach ($select->fetchAll() as $linha_array) {
    if ($linha_array['idProfessorDisciplina'] == $disciplina) {
        $variavelControleExterna = 1;
        break;
    }
}
if ($variavelControleExterna == 0) {
    $_SESSION['mensagemErro'] = 'Curso não encontrado';
    header('location: ../index.php');
}
//não aluno cadastrando critica
if ($_SESSION['tipoLogin'] != 2) {
    $_SESSION['mensagemErro'] = 'Precisa ser aluno para realizar uma critica a uma disciplina!';
    header('location: ../index.php');
    $send = '';
}
if ($send && $variavelControleExterna != 0) {
    $variavelControle = 1;
    //receber variáveis e validar
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
            $_SESSION['mensagemErro'] = 'Ocorreu um erro ao cadastrar os elogios.';
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
            $_SESSION['mensagemErro'] = 'Ocorreu um erro ao cadastrar as críticas/pontos de melhoria.';
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
    foreach ($select->fetchAll() as $linha_array) {
        $aluno = $linha_array['idAluno'];
        break;
    }
    //controle para ver se o aluno já não fez uma crítica sobre a disciplina
    $result = "SELECT Count(*) 'Quantidade' FROM $db.$TB_CRITICA where Aluno_idAluno = :idAluno and ProfessorDisciplina_idProfessorDisciplina = :idDisciplina";
    $select = $conx->prepare($result);
    $select->bindParam(':idAluno', $aluno);
    $select->bindParam(':idDisciplina', $disciplina);
    $select->execute();
    foreach ($select->fetchAll() as $linha_array) {
        if ($linha_array['Quantidade'] != 0) {
            $variavelControle = 0;
            $_SESSION['mensagemErro'] = "Você já cadastrou uma crítica a essa disciplina! Se quiser mudar a crítica, vá em alterar a crítica!";
            break;
        }
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
    try {
        //verificar se a disciplina é válida
        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_PROFESSORDISCIPLINA WHERE idProfessorDisciplina = :id";
        $select = $conx->prepare($result);
        $select->bindParam(':id', $disciplina);
        $select->execute();
        foreach ($select->fetchAll() as $linha_array) {
            if ($linha_array['quantidade'] != 1) {
                $variavelControle = 0;
                $_SESSION['mensagemErro'] = "Essa disciplina não existe!";
            }
        }
        if ($variavelControle) {
            //cadastrar
            $result = "INSERT INTO $db.$TB_CRITICA (Aluno_idAluno, NotaDisciplina, NotaAluno, NotaEvolucao, Descrição, ProfessorDisciplina_idProfessorDisciplina, Data, AnoSemestre, Elogios, Criticas) VALUES (:idAluno, :notaDisciplina, :notaAluno, :notaEvolucao, :descricao,:idDisciplina, now(), :anoSemestre, :elogios, :criticas)";
            $insert = $conx->prepare($result);
            $insert->bindParam(':idAluno', $aluno);
            $insert->bindParam(':notaDisciplina', $notaDisciplina);
            $insert->bindParam(':notaAluno', $notaAluno);
            $insert->bindParam(':notaEvolucao', $notaEvolucao);
            $insert->bindParam(':descricao', $descricao);
            $insert->bindParam(':idDisciplina', $disciplina);
            $insert->bindParam(':anoSemestre', $anoSemestre);
            $insert->bindParam(':elogios', $elogiosString);
            $insert->bindParam(':criticas', $criticasString);
            $insert->execute();
            $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';
        }
        header("Location: ../index.php");
    } catch (PDOException $e) {
        $msgErr = "Erro na inclusão:<br />";
        $_SESSION['mensagemErro'] = $msgErr;
        header("Location: ../index.php");
    }
}
