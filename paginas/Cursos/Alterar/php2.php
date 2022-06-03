<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']) || $_SESSION['administradorLogin']!=1)
{
  header('location:../../Login/index.php');
}?>
<?php
require '../../../camadaDados/conectar.php';
require '../../../camadaDados/tabelas.php';
$send=filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
$id = filter_input(INPUT_POST, 'id',FILTER_SANITIZE_NUMBER_INT);
if($id != $_SESSION['idAlteracao']){
    $id = $_SESSION['idAlteracao'];
    unset($_SESSION['idAlteracao']);
}
if($send == 'Cancelar'){
	$_SESSION['mensagemFinalizacao'] = 'Operação cancelada com sucesso!';	
	header("Location: ../index.php");
}
else if($send == 'Alterar'){
	$nome = filter_input(INPUT_POST,'nome',FILTER_SANITIZE_STRING);
    if(strlen($nome)<1 || strlen($nome) > 50){
        $nome = "CursoSemNome".rand(0,1000);
    }
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_CURSO WHERE nome like :nome and idCurso != :Id";
		$select = $conx->prepare($result);
		$select->bindParam(':nome',$nome);
        $select->bindParam(':Id',$id);
		$select->execute();
        $variavelControle = 1;
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 0){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Já há um curso com esse nome!";}}
        if($variavelControle){    
            $result = "UPDATE $db.$TB_CURSO SET nome=:nome WHERE idCurso = :idCurso";
            $insert = $conx->prepare($result);
            $insert->bindParam(':idCurso',$id);
            $insert->bindParam(':nome',$nome);
            $insert->execute();
            $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';}
        header("Location: ../index.php");	
        }
    catch(PDOException $e) {
            $msgErr = "Erro na inclusão:<br />";
            $_SESSION['mensagemErro'] = $msgErr;     
			header("Location: ../index.php");			
    }
}
else if($send == 'Excluir'){
    $result= "Select Usuario_idUsuario FROM $db.$TB_ALUNO WHERE Curso_idCurso=:idCurso";
    $select = $conx->prepare($result);
    $select->bindParam(':idCurso', $id);
    $select->execute();
    $usuarios = $select->fetchAll();
	$result= "Select idDisciplina FROM $db.$TB_DISCIPLINA WHERE Curso_idCurso=:idCurso";
    $select = $conx->prepare($result);
    $select->bindParam(':idCurso', $id);
    $select->execute();
    $disciplinas = $select->fetchAll();
	if(count($usuarios) == 0 && count($disciplinas) == 0){
		$result= "DELETE FROM $db.$TB_ALUNO WHERE Curso_idCurso=:idCurso";
		$delete = $conx->prepare($result);
		$delete->bindParam(':idCurso', $id);
		$delete->execute();  

		foreach($usuarios as $linha_array) {
			$usuario = $linha_array['Usuario_idUsuario'];
			$result= "DELETE FROM $db.$TB_USUARIO WHERE idUsuario=:usuario";
			$delete = $conx->prepare($result);
			$delete->bindParam(':usuario', $usuario);
			$delete->execute();         
		}  

		$result= "DELETE FROM $db.$TB_CURSO WHERE idCurso=:idCurso";
		$delete = $conx->prepare($result);
		$delete->bindParam(':idCurso', $id);
		$delete->execute();
		$_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';}
	else{
		if(count($usuarios) != 0){
			$_SESSION['mensagemErro'] = 'Há usuários cadastrados no curso!';}
		else{
			$_SESSION['mensagemErro'] = 'Há disciplinas cadastradas no curso!';
		}
	}
    header("Location: ../index.php");
}
?>