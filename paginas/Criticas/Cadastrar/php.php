<?php
require '../../../CamadaDados/conectar.php';
$tb = 'critica';
$tb2 = 'aluno';
$tb3 = 'usuario';
$tb4 = 'professordisciplina';
$tb5 = 'disciplina';
$tb6 = 'cursodisciplina';
$send=filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
if(!isset($_SESSION['idUsuarioLogin']))
{
  header('location:../../Login/index.php');
}
//controle para ver se a disciplina é do curso do usuário mesmo
$disciplina = filter_input(INPUT_POST,'disciplina',FILTER_SANITIZE_NUMBER_INT);
if(!is_numeric($disciplina) || $disciplina > 99999999999 || $disciplina < 1){
    $disciplina = -1;
}
$idCurso = "";
if($_SESSION['tipoLogin'] == 2){
    $result = "SELECT A1.Curso_idCurso FROM $db.$tb2 A1 where A1.Usuario_idUsuario=:id";
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
$result = "SELECT PD1.idProfessorDisciplina FROM $db.$tb4 PD1 inner join $db.$tb5 D1 ON PD1.Disciplina_idDisciplina = D1.idDisciplina inner join $db.$tb6 CD1 ON CD1.Disciplina_idDisciplina = D1.idDisciplina where CD1.Curso_idCurso like :id";
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
if($_SESSION['tipoLogin']!=2){
    $_SESSION['mensagemErro'] = 'Precisa ser aluno para realizar uma critica a uma disciplina!';
    header('location: ../index.php');
    $send = '';
}
if($send && $variavelControleExterna!=0){
    $notaProfessor = filter_input(INPUT_POST,'notaProfessor',FILTER_SANITIZE_NUMBER_INT);
    if(!is_numeric($notaProfessor) || $notaProfessor > 5 || $notaProfessor < 1){
        $notaProfessor = 3;
    }
    $notaDisciplina = filter_input(INPUT_POST, 'notaDisciplina',FILTER_SANITIZE_NUMBER_INT);
    if(!is_numeric($notaDisciplina) || $notaDisciplina > 5 || $notaDisciplina < 1){
        $notaDisciplina = 3;
    }
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
    if(strlen($descricao) > 500){
        $descricao = 'descricao';
    }
    $aluno = $_SESSION['idUsuarioLogin'];
    $result = "SELECT A1.idAluno FROM $db.$tb2 A1 inner join $db.$tb3 U1 ON U1.idUsuario = A1.Usuario_idUsuario WHERE U1.idUsuario = :idUsuario";
    $select = $conx->prepare($result);
    $select->bindParam(':idUsuario',$aluno);
    $select->execute();
    $variavelControle = 1;
    foreach($select->fetchAll() as $linha_array){
        $aluno = $linha_array['idAluno'];
        break;
    }    
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$tb4 WHERE idProfessorDisciplina = :id";
		$select = $conx->prepare($result);
		$select->bindParam(':id',$disciplina);
		$select->execute();
        $variavelControle = 1;
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 1){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Essa disciplina não existe!";}}
        if($variavelControle){           
            $result = "INSERT INTO $db.$tb (Aluno_idAluno, NotaDisciplina, NotaProfessor, Descrição, ProfessorDisciplina_idProfessorDisciplina, Data) VALUES (:idAluno, :notaDisciplina, :notaProfessor, :descricao,:idDisciplina, now())";
            $insert = $conx->prepare($result);
            $insert->bindParam(':idAluno',$aluno);
            $insert->bindParam(':notaDisciplina',$notaDisciplina);
            $insert->bindParam(':notaProfessor',$notaProfessor);
            $insert->bindParam(':descricao',$descricao);
            $insert->bindParam(':idDisciplina',$disciplina);
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