<?php
	session_start();
	if(!isset($_SESSION['idUsuarioLogin']) || $_SESSION['administradorLogin']!=1)
	{
	  header('location:../../Login/index.php');
	}
?>
<?php
	require '../../../camadaDados/conectar.php';
	require '../../../camadaDados/tabelas.php';
	$send=filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
	$id = filter_input(INPUT_POST, 'id',FILTER_SANITIZE_NUMBER_INT);
	if($id != $_SESSION['idAlteracao3']){
		$id = $_SESSION['idAlteracao3'];
		unset($_SESSION['idAlteracao3']);
	}
	$nome = filter_input(INPUT_POST,'nome',FILTER_SANITIZE_STRING);
	if(strlen($nome)<1 || strlen($nome) > 50){
		$nome = "DisciplinaSemNome".rand(0,1000);
	}
	$descricao = filter_input(INPUT_POST,'descricao',FILTER_SANITIZE_STRING);
    if(strlen($descricao)<1) {
        $descricao = 'Disciplina...';
    }
    $descricaoLongaDemais = false;
    if(strlen($descricao) > 21600) {
        $descricao = 'Descrição longa demais!';
        $descricaoLongaDemais = true; 
    }
	$codigo = filter_input(INPUT_POST,'codigo', FILTER_SANITIZE_NUMBER_INT);
	if(!is_numeric($codigo) || $codigo < 1 || $codigo > 9999){
		$codigo = rand(0,1000);
	}
	$sigla = filter_input(INPUT_POST,'sigla',FILTER_SANITIZE_STRING);
	if(strlen($sigla)>6 && strlen($sigla)<1){
		$sigla = "AAA000";
	}
	$tipo = filter_input(INPUT_POST,'tipo',FILTER_SANITIZE_NUMBER_INT);
	if(!is_numeric($tipo) || $tipo < 0 || $tipo > 2){
		$tipo = 0;
	}
	$ativa = filter_input(INPUT_POST,'ativa',FILTER_SANITIZE_STRING);
	if($ativa != true && $ativa != false){
		$ativa = 1;
	}
	if($ativa){
		$ativa = 1;
	}else{
		$ativa = 0;
	}
	$variavelControle = 1;
	if($send == 'Cancelar'){
		$_SESSION['mensagemFinalizacao'] = 'Operação cancelada com sucesso!';	
		header("Location: ../index.php");
	}
	else if($send == 'Alterar'){
		try{

			$result = "SELECT count(*) 'quantidade' FROM $db.$TB_DISCIPLINA WHERE Código=:codigo and idDisciplina!=:id";
			$select = $conx->prepare($result);
			$select->bindParam(':codigo',$codigo);
			$select->bindParam(':id',$id);
			$select->execute();
			foreach($select->fetchAll() as $linha_array){
				if($linha_array['quantidade'] != 0){
					$variavelControle = 0;
					$_SESSION['mensagemErro'] = "Já há uma disciplina com esse código cadastrada!";}}
			if ($descricaoLongaDemais) {
				$variavelControle = 0;
				$_SESSION['mensagemErro'] = "A descrição inserida é longa demais!";
			}

			if($variavelControle){    
				$result = "UPDATE $db.$TB_DISCIPLINA SET Nome=:nome, Descrição=:descricao, Código=:codigo,Sigla=:sigla,Tipo=:tipo,Ativa=:ativa WHERE idDisciplina = :id";
				$insert = $conx->prepare($result);
				$insert->bindParam(':nome',$nome);
				$insert->bindParam(':descricao',$descricao);
				$insert->bindParam(':codigo',$codigo);
				$insert->bindParam(':sigla',$sigla);
				$insert->bindParam(':id',$id);
				$insert->bindParam(':tipo',$tipo);
				$insert->bindParam(':ativa',$ativa);
				$insert->execute();
				$_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';}
			header("Location: ../index.php");	
			}
		catch(PDOException $e) {
				$msgErr = "Erro na alteração:<br />";
				$_SESSION['mensagemErro'] = $msgErr;     
				header("Location: ../index.php");			
		}
	}
	else if($send == 'Excluir'){		
		$result= "Select idProfessorDisciplina FROM $db.$TB_PROFESSORDISCIPLINA WHERE Disciplina_idDisciplina=:idDisciplina";
		$select = $conx->prepare($result);
		$select->bindParam(':idDisciplina', $id);
		$select->execute();
		$disciplinas = $select->fetchAll();
		foreach($disciplinas as $linha_array) {
			$disciplina = $linha_array['idProfessorDisciplina'];
			$result= "DELETE FROM $db.$TB_CRITICA WHERE ProfessorDisciplina_idProfessorDisciplina=:idProfessorDisciplina";
			$delete = $conx->prepare($result);
			$delete->bindParam(':idProfessorDisciplina', $disciplina);
			$delete->execute();         
		}
		
		$result= "DELETE FROM $db.$TB_PROFESSORDISCIPLINA WHERE Disciplina_idDisciplina=:idDisciplina";
		$delete = $conx->prepare($result);
		$delete->bindParam(':idDisciplina', $id);
		$delete->execute();

		$result= "DELETE FROM $db.$TB_DISCIPLINA WHERE idDisciplina=:idDisciplina";
		$delete = $conx->prepare($result);
		$delete->bindParam(':idDisciplina', $id);
		$delete->execute();
		
		$_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';
		header("Location: ../index.php");
	}
?>