<?php
require '../../../camadaDados/conectar.php';
require '../../../camadaDados/tabelas.php';
$send=filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
if(!isset($_SESSION['idUsuarioLogin']))
{
  header('location:../../Login/index.php');
}
//função para validar elogio
function elogioValidar($elogio){
    if(in_array($elogio, ['Nenhum','Explicação','Material','Organização','Pontualidade','Prestativo','Carismático'])){
        return $elogio;
    }else{
        return 'Nenhum';
    }
}
//função para validar critica
function criticaValidar($critica){
    if(in_array($critica,['Nenhum','Explicação','Material','Organização','Pontualidade','Comunicação','Método de avaliação'])){
        return $critica;
    }else{
        return 'Nenhum';
    }
}
//controle para ver se a disciplina é do curso do usuário mesmo
$disciplina = filter_input(INPUT_POST,'disciplina',FILTER_SANITIZE_NUMBER_INT);
if(!is_numeric($disciplina) || $disciplina > 99999999999 || $disciplina < 1){
    $disciplina = -1;
}
$idCurso = "%%";
if($_SESSION['tipoLogin'] == 2){
    $result = "SELECT A1.Curso_idCurso FROM $db.$TB_ALUNO A1 where A1.Usuario_idUsuario=:id";
    $select = $conx->prepare($result);
    $select->bindParam(':id',$_SESSION['idUsuarioLogin']);
    $select->execute();
    foreach($select->fetchAll() as $linha_array){
        $idCurso = $linha_array['Curso_idCurso'];
    }
}
$result = "SELECT PD1.idProfessorDisciplina FROM $db.$TB_PROFESSORDISCIPLINA PD1 inner join $db.$TB_DISCIPLINA D1 ON PD1.Disciplina_idDisciplina = D1.idDisciplina where D1.Curso_idCurso like :id";
$select = $conx->prepare($result);
$select->bindParam(':id',$idCurso);
$select->execute();
$variavelControleExterna = 0;
$_SESSION['mensagemErro'] = "";
foreach($select->fetchAll() as $linha_array){
    if($linha_array['idProfessorDisciplina'] == $disciplina){
        $variavelControleExterna = 1;
        break;
    }}
if($variavelControleExterna==0){
    $_SESSION['mensagemErro'] = 'Curso não encontrado';
    header('location: ../index.php');
}
//não aluno cadastrando critica
if($_SESSION['tipoLogin']!=2){
    $_SESSION['mensagemErro'] = 'Precisa ser aluno para realizar uma critica a uma disciplina!';
    header('location: ../index.php');
    $send = '';
}
if($send && $variavelControleExterna!=0){
	$variavelControle = 1;
    //receber variáveis e validar
    $notaAluno = filter_input(INPUT_POST,'notaAluno',FILTER_SANITIZE_NUMBER_INT);
    if(!is_numeric($notaAluno) || $notaAluno > 5 || $notaAluno < 1){
        $notaAluno = 3;
    }
    $notaEvolucao = filter_input(INPUT_POST,'notaEvolucao',FILTER_SANITIZE_NUMBER_INT);
    if(!is_numeric($notaEvolucao) || $notaEvolucao > 5 || $notaEvolucao < 1){
        $notaEvolucao = 3;
    }
    $notaDisciplina = filter_input(INPUT_POST, 'notaDisciplina',FILTER_SANITIZE_NUMBER_INT);
    if(!is_numeric($notaDisciplina) || $notaDisciplina > 5 || $notaDisciplina < 1){
        $notaDisciplina = 3;
    }
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
    if(strlen($descricao) > 500){
        $descricao = 'descricao';
    }
    $ano = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_NUMBER_INT);
    if(!is_numeric($ano) || $ano > 2100 || $ano < 1973){
        $ano = 1973;
    }
    $semestre = filter_input(INPUT_POST, 'semestre', FILTER_SANITIZE_NUMBER_INT);
    if(!is_numeric($semestre) || $semestre > 2 || $semestre < 1){
        $semestre = 1;
    }
    $anoSemestre = $ano."".$semestre; 
    $elogio1 = elogioValidar(filter_input(INPUT_POST, 'elogio1', FILTER_SANITIZE_STRING));
    $elogio2 = elogioValidar(filter_input(INPUT_POST, 'elogio2', FILTER_SANITIZE_STRING));
    $elogio3 = elogioValidar(filter_input(INPUT_POST, 'elogio3', FILTER_SANITIZE_STRING));
    $critica1 = criticaValidar(filter_input(INPUT_POST, 'critica1', FILTER_SANITIZE_STRING));
    $critica2 = criticaValidar(filter_input(INPUT_POST, 'critica2', FILTER_SANITIZE_STRING));
    $critica3 = criticaValidar(filter_input(INPUT_POST, 'critica3', FILTER_SANITIZE_STRING));
    //verificar se não há elogios iguais
    if($elogio1 != 'Nenhum' && $elogio1 == $elogio2){
        $elogio2 = 'Nenhum';
    }
    if($elogio1 != 'Nenhum' && $elogio1 == $elogio3){
        $elogio3 = 'Nenhum';
    }
    if($elogio2 != 'Nenhum' && $elogio2 == $elogio3){
        $elogio3 = 'Nenhum';
    } 
    //verificar se não há criticas iguais
    if($critica1 != 'Nenhum' && $critica1 == $critica2){
        $critica2 = 'Nenhum';
    }
    if($critica1 != 'Nenhum' && $critica1 == $critica3){
        $critica3 = 'Nenhum';
    }
    if($critica2 != 'Nenhum' && $critica2 == $critica3){
        $critica3 = 'Nenhum';
    }
    //montar a string de elogios
    $elogios = $elogio1."-".$elogio2."-".$elogio3;
    //montar a string de críticas
    $criticas = $critica1."-".$critica2."-".$critica3;    
    //pegar id do aluno     
    $aluno = $_SESSION['idUsuarioLogin'];
    $result = "SELECT A1.idAluno FROM $db.$TB_ALUNO A1 inner join $db.$TB_USUARIO U1 ON U1.idUsuario = A1.Usuario_idUsuario WHERE U1.idUsuario = :idUsuario";
    $select = $conx->prepare($result);
    $select->bindParam(':idUsuario',$aluno);
    $select->execute();
    foreach($select->fetchAll() as $linha_array){
        $aluno = $linha_array['idAluno'];
        break;
    }
    //controle para ver se o aluno já não fez uma crítica sobre a disciplina
    $result = "SELECT Count(*) 'Quantidade' FROM $db.$TB_CRITICA where Aluno_idAluno = :idAluno and ProfessorDisciplina_idProfessorDisciplina = :idDisciplina";
    $select = $conx->prepare($result);
    $select->bindParam(':idAluno',$aluno);
    $select->bindParam(':idDisciplina',$disciplina);
    $select->execute();
    foreach($select->fetchAll() as $linha_array){
        if($linha_array['Quantidade'] != 0){
            $variavelControle= 0;
            $_SESSION['mensagemErro'] = "Você já cadastrou uma crítica a essa disciplina! Se quiser mudar a crítica, vá em alterar a crítica!";
            break;
    }}
    //controle para ver se a data é válida para a disciplina
    $anoQuery = $ano;
    if($semestre == 1){
        $anoQuery = $anoQuery."-07-15";
    }
    else{
        $anoQuery = $anoQuery."-12-31";
    }
    $result = "SELECT Count(*) 'Quantidade' FROM $db.$TB_PROFESSORDISCIPLINA where idProfessorDisciplina = :idDisciplina and DataInicial <= :data1 and (dataFinal >= :data2 or dataFinal='0000-00-00')";
    $select = $conx->prepare($result);
    $select->bindParam(':idDisciplina',$disciplina);
    $select->bindParam(':data1',$anoQuery);
    $select->bindParam(':data2',$anoQuery);
    $select->execute();
    foreach($select->fetchAll() as $linha_array){
            if($linha_array['Quantidade'] != 1){
                $variavelControle = 0;
                $_SESSION['mensagemErro'] = 'Data inválida para a disciplina!';}
            break;
    }       
    try{
        //verificar se a disciplina é válida
        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_PROFESSORDISCIPLINA WHERE idProfessorDisciplina = :id";
		$select = $conx->prepare($result);
		$select->bindParam(':id',$disciplina);
		$select->execute();
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 1){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Essa disciplina não existe!";}}
        if($variavelControle){           
            //cadastrar
            $result = "INSERT INTO $db.$TB_CRITICA (Aluno_idAluno, NotaDisciplina, NotaAluno, NotaEvolucao, Descrição, ProfessorDisciplina_idProfessorDisciplina, Data, AnoSemestre, Elogios, Criticas) VALUES (:idAluno, :notaDisciplina, :notaAluno, :notaEvolucao, :descricao,:idDisciplina, now(), :anoSemestre, :elogios, :criticas)";
            $insert = $conx->prepare($result);
            $insert->bindParam(':idAluno',$aluno);
            $insert->bindParam(':notaDisciplina',$notaDisciplina);
            $insert->bindParam(':notaAluno',$notaAluno);
            $insert->bindParam(':notaEvolucao',$notaEvolucao);
            $insert->bindParam(':descricao',$descricao);
            $insert->bindParam(':idDisciplina',$disciplina);
            $insert->bindParam(':anoSemestre',$anoSemestre);
            $insert->bindParam(':elogios',$elogios);
            $insert->bindParam(':criticas',$criticas);
            $insert->execute();
            $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';}
        header("Location: ../index.php");	
        }
    catch(PDOException $e) {
            $msgErr = "Erro na inclusão:<br />".$e->getMessage();
            $_SESSION['mensagemErro'] = $msgErr;     
			header("Location: ../index.php");			
    }
}
?>