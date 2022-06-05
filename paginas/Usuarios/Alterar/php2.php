<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']))
{
  header('location:../../Login/index.php');
}?>
<?php
	function validaCPF($cpf){
		require_once('../validarcpf-master/validarcpf-master/class.CPF.php');
		$cpf = new CPF(); 
		return $cpf->validate($_POST['cpf']);
	}
	require '../../../camadaDados/conectar.php';
	require '../../../camadaDados/tabelas.php';
	$send=filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
	$id = filter_input(INPUT_POST, 'id',FILTER_SANITIZE_NUMBER_INT);
	if($id != $_SESSION['idAlteracao2']){
		$id = $_SESSION['idAlteracao2'];
		unset($_SESSION['idAlteracao2']);
	}
	$senha = filter_input(INPUT_POST,'senha',FILTER_SANITIZE_STRING);
	$nome = filter_input(INPUT_POST,'nome',FILTER_SANITIZE_STRING);
	if($_SESSION['administradorLogin']){
		$cpf = filter_input(INPUT_POST,'cpf',FILTER_SANITIZE_STRING);
		$cpf = str_replace(".","",$cpf);
		$cpf = str_replace("-","",$cpf);
		$administrador = filter_input(INPUT_POST,'administrador',FILTER_SANITIZE_STRING);
		$login = filter_input(INPUT_POST,'login',FILTER_SANITIZE_STRING);
		$tipo = filter_input(INPUT_POST,'tipo', FILTER_SANITIZE_STRING);
		$ativo = filter_input(INPUT_POST,'ativo',FILTER_SANITIZE_STRING);
		if($tipo != $_SESSION['tipoAlteracao']){
			$tipo = $_SESSION['tipoAlteracao'];
			unset($_SESSION['tipoAlteracao']);
		}
		$curso = filter_input(INPUT_POST,'curso',FILTER_SANITIZE_STRING);
		$matricula = filter_input(INPUT_POST,'matricula',FILTER_SANITIZE_NUMBER_INT);
	}
	$variavelControle = 1;
	if(strlen($senha) > 50 || strlen($senha) < 8){
			$senha = '01234567';
	}
	if(strlen($nome)<1 || strlen($nome) >100 || filter_var($nome, FILTER_SANITIZE_NUMBER_INT) != ''){
			$nome = 'Paulo';
	}
	if($_SESSION['administradorLogin']){ 
		if(!validaCPF($cpf)){
			$cpf = -10;
			$variavelControle = 0;
		}
		if(strlen($login) > 100 || !filter_var($login, FILTER_VALIDATE_EMAIL)){
				$login = 'email@gmail.com';
		}
		if($administrador != true && $administrador != false){
				$administrador = 0;
		}
		$administrador = $administrador?1:0;
		if($ativo != true && $ativo != false){
				$ativo = 0;
		}
		$ativo = $ativo?1:0;
		if(strlen($curso) < 1 && strlen($curso) > 100){
				$variavelControle = 0;
				$_SESSION['mensagemErro'] = 'Curso inválido';
		}
		if((!is_numeric($matricula) || $matricula <1 || $matricula>99999999) && $tipo == 2){
				$matricula = 1;
		}
	}
	if($send == 'Cancelar'){
		$_SESSION['mensagemFinalizacao'] = 'Operação cancelada com sucesso!';	
		header("Location: ../index.php");
	}
	else if($send == 'Alterar'){
		try{
			if($_SESSION['administradorLogin']){ 
				if($cpf == -10){
					$_SESSION['mensagemErro'] = 'CPF inválido!';
				}
				$result = "SELECT count(*) 'quantidade' FROM $db.$TB_USUARIO WHERE Cpf like :Cpf and idUsuario != :Id and Ativo = 1";
				$select = $conx->prepare($result);
				$select->bindParam(':Cpf',$cpf);
				$select->bindParam(':Id',$id);
				$select->execute();
				foreach($select->fetchAll() as $linha_array){
					if($linha_array['quantidade'] != 0){
						$variavelControle = 0;
						$_SESSION['mensagemErro'] = "Já há um usuário ativo com esse cpf cadastrado!";}}
				$result = "SELECT count(*) 'quantidade' FROM $db.$TB_USUARIO WHERE ".'Login'." like :Login and idUsuario != :Id";
				
				$select = $conx->prepare($result);
				$select->bindParam(':Login',$login);
				$select->bindParam(':Id',$id);
				$select->execute();
				foreach($select->fetchAll() as $linha_array){
					if($linha_array['quantidade'] != 0){
						$variavelControle = 0;
						$_SESSION['mensagemErro'] = "Já há um usuário com esse login cadastrado!";}}}
			if($variavelControle !=0){ 
				if($tipo != 2){
					if($_SESSION['administradorLogin']){
						$result = "UPDATE $db.$TB_USUARIO SET".' Login=:Login'.",Senha=:Senha,Nome=:Nome,Administrador=:Administrador,Cpf=:Cpf,Ativo=:Ativo Where idUsuario=:Id";
						$insert = $conx->prepare($result);
						$insert->bindParam(':Login',$login);
						$insert->bindParam(':Senha',$senha);
						$insert->bindParam(':Nome',$nome);
						$insert->bindParam(':Administrador',$administrador);
						$insert->bindParam(':Cpf',$cpf);
						$insert->bindParam(':Id',$id);
						$insert->bindParam(':Ativo',$ativo);
						$insert->execute();
						$_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';}
					else{
						$result = "UPDATE $db.$TB_USUARIO SET Senha=:Senha,Nome=:Nome Where idUsuario=:Id";
						$insert = $conx->prepare($result);
						$insert->bindParam(':Senha',$senha);
						$insert->bindParam(':Nome',$nome);
						$insert->bindParam(':Id',$id);
						$insert->execute();
						$_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';
					}}
				else{
					if($_SESSION['administradorLogin']){
						$result = "SELECT idCurso FROM $db.$TB_CURSO WHERE Nome=:Nome";
						$select = $conx->prepare($result);
						$select->bindParam(':Nome',$curso);
						$select->execute();
						$idCurso = null;
						foreach($select->fetchAll() as $linha_array){
							$idCurso = $linha_array['idCurso'];}
						if(!$idCurso){
							$_SESSION['mensagemErro'] = 'Curso inexistente!';
							$variavelControle = 0;
						}             
						$result = "SELECT count(*) 'quantidade' FROM $db.$TB_ALUNO WHERE Matricula like :Matricula and Usuario_idUsuario != :Id";
						$select = $conx->prepare($result);
						$select->bindParam(':Matricula',$matricula);
						$select->bindParam(':Id',$id);
						$select->execute();
						foreach($select->fetchAll() as $linha_array){
							if($linha_array['quantidade'] != 0){
								$variavelControle = 0;
								$_SESSION['mensagemErro'] = "Já há um usuário com essa matrícula cadastrada!";}}
						$administrador = 0;
						if($variavelControle){
							$result = "UPDATE $db.$TB_USUARIO SET".' Login=:Login'.",Senha=:Senha,Nome=:Nome,Administrador=:Administrador,Cpf=:Cpf, Ativo=:Ativo Where idUsuario=:Id";
							$insert = $conx->prepare($result);
							$insert->bindParam(':Login',$login);
							$insert->bindParam(':Senha',$senha);
							$insert->bindParam(':Nome',$nome);
							$insert->bindParam(':Administrador',$administrador);
							$insert->bindParam(':Cpf',$cpf);          
							$insert->bindParam(':Id',$id);
							$insert->bindParam(':Ativo',$ativo);
							$insert->execute();
							$result = "UPDATE $db.$TB_ALUNO SET Matricula=:Matricula,Curso_idCurso=:Curso Where Usuario_idUsuario=:Usuario";
							$insert = $conx->prepare($result);
							$insert->bindParam(':Matricula',$matricula);
							$insert->bindParam(':Usuario',$id);
							$insert->bindParam(':Curso',$idCurso);
							$insert->execute();
							$_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';}
					}
					else{
						$result = "UPDATE $db.$TB_USUARIO SET Senha=:Senha,Nome=:Nome Where idUsuario=:Id";
						$insert = $conx->prepare($result);
						$insert->bindParam(':Senha',$senha);
						$insert->bindParam(':Nome',$nome);
						$insert->bindParam(':Id',$id);
						$insert->execute();
						$_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';						
					}
				}
				}
			header("Location: ../index.php");	
		}
		catch(PDOException $e) {
			$msgErr = "Erro na alteração:<br />";
			$_SESSION['mensagemErro'] = $msgErr;     
			header("Location: ../index.php");			
		}
	}
	else if($send == 'Excluir' && $_SESSION['administradorLogin']){
		if($id!=1){
			if($tipo == 0){
				$result= "DELETE FROM $db.$TB_USUARIO WHERE idUsuario=:idUsuario";
				$delete = $conx->prepare($result);
				$delete->bindParam(':idUsuario', $id);
				$delete->execute();
			}else if($tipo == 1){
				$result = "SELECT idProfessor FROM $db.$TB_PROFESSOR WHERE Usuario_idUsuario=:idUsuario";
				$select = $conx->prepare($result);
				$select->bindParam(':idUsuario',$id);
				$select->execute();
				$idProfessor = 0;
				foreach($select->fetchAll() as $linha_array){
					$idProfessor = $linha_array['idProfessor'];}
				$result= "Select idProfessorDisciplina FROM $db.$TB_PROFESSORDISCIPLINA WHERE Professor_idProfessor=:idProfessor";
				$select = $conx->prepare($result);
				$select->bindParam(':idProfessor', $idProfessor);
				$select->execute();
				$disciplinas = $select->fetchAll();
				foreach($disciplinas as $linha_array) {
					$disciplina = $linha_array['idProfessorDisciplina'];
					$result= "DELETE FROM $db.$TB_CRITICA WHERE ProfessorDisciplina_idProfessorDisciplina=:idProfessorDisciplina";
					$delete = $conx->prepare($result);
					$delete->bindParam(':idProfessorDisciplina', $disciplina);
					$delete->execute();         
				}
				$result= "DELETE FROM $db.$TB_PROFESSORDISCIPLINA WHERE Professor_idProfessor=:idProfessor";
				$delete = $conx->prepare($result);
				$delete->bindParam(':idProfessor', $idProfessor);
				$delete->execute();        
				$result= "DELETE FROM $db.$TB_PROFESSOR WHERE Usuario_idUsuario=:idUsuario";
				$delete = $conx->prepare($result);
				$delete->bindParam(':idUsuario', $id);
				$delete->execute();
				$result= "DELETE FROM $db.$TB_USUARIO WHERE idUsuario=:idUsuario";
				$delete = $conx->prepare($result);
				$delete->bindParam(':idUsuario', $id);
				$delete->execute();                
			}else if($tipo == 2){
				$aluno = $_SESSION['idUsuarioLogin'];
				$result = "SELECT A1.idAluno FROM $db.$TB_ALUNO A1 inner join $db.$TB_USUARIO U1 ON U1.idUsuario = A1.Usuario_idUsuario WHERE U1.idUsuario = :idUsuario";
				$select = $conx->prepare($result);
				$select->bindParam(':idUsuario',$id);
				$select->execute();
				$aluno = '';
				foreach($select->fetchAll() as $linha_array){
					$aluno = $linha_array['idAluno'];
					break;
				} 
				$result= "DELETE FROM $db.$TB_CRITICA WHERE Aluno_idAluno=:id";
				$delete = $conx->prepare($result);
				$delete->bindParam(':id', $aluno);
				$delete->execute();        
				$result= "DELETE FROM $db.$TB_ALUNO WHERE Usuario_idUsuario=:idUsuario";
				$delete = $conx->prepare($result);
				$delete->bindParam(':idUsuario', $id);
				$delete->execute();
				$result= "DELETE FROM $db.$TB_USUARIO WHERE idUsuario=:idUsuario";
				$delete = $conx->prepare($result);
				$delete->bindParam(':idUsuario', $id);
				$delete->execute();                
			}
		}
		$_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';
		header("Location: ../index.php");
	}
?>