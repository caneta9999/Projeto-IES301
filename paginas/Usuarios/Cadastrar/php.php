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
function validaCPF($cpf){
	require_once('../validarcpf-master/validarcpf-master/class.CPF.php');
	$cpf = new CPF(); 
	return $cpf->validate($_POST['cpf']);
}
if($send){
	$login = filter_input(INPUT_POST,'login',FILTER_SANITIZE_STRING);
	$senha = filter_input(INPUT_POST,'senha',FILTER_SANITIZE_STRING);
	$nome = filter_input(INPUT_POST,'nome',FILTER_SANITIZE_STRING);
    $administrador = filter_input(INPUT_POST,'administrador',FILTER_SANITIZE_STRING);
    $cpf = filter_input(INPUT_POST,'cpf',FILTER_SANITIZE_STRING);
	$cpf = str_replace(".","",$cpf);
	$cpf = str_replace("-","",$cpf);
    $tipo = filter_input(INPUT_POST,'tipo', FILTER_SANITIZE_STRING);
    $curso = filter_input(INPUT_POST,'curso',FILTER_SANITIZE_STRING);
    $matricula = filter_input(INPUT_POST,'matricula',FILTER_SANITIZE_NUMBER_INT);
	$ativo = 1;

    $variavelControle = 1;
    
    if(strlen($login) > 100 || !filter_var($login, FILTER_VALIDATE_EMAIL)){
        $login = 'email@gmail.com';
    }

    if(strlen($senha) > 50 || strlen($senha) < 8){
        $senha = '12345678';
    }
    if(strlen($nome)<1 || strlen($nome) >100 || filter_var($nome, FILTER_SANITIZE_NUMBER_INT) != ''){
        $nome = 'Paulo';
    }
    if($administrador != true && $administrador != false){
        $administrador = 0;
    }
	$administrador = $administrador?1:0;
	if(!validaCPF($cpf)){
		$cpf = -10;
		$variavelControle = 0;
	}
    if($tipo != 'Professor' && $tipo !='Aluno' && $tipo!='Nenhum'){
        $tipo = 'Nenhum';
    }
    if($tipo == 'Aluno'){
        $tipo = 2;
    }else if($tipo == 'Professor'){
        $tipo = 1;
    }else{
        $tipo = 0;
    }
    if(strlen($curso) < 1 && strlen($curso) > 100){
        $variavelControle = 0;
        $_SESSION['mensagemErro'] = 'Curso inválido';
    }
    if((!is_numeric($matricula) || $matricula <1 || $matricula>99999999) && $tipo == 2){
        $matricula = 1;
    }
    try{
		if($cpf == -10){
			$_SESSION['mensagemErro'] = 'CPF inválido!';
		}
        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_USUARIO WHERE Cpf like :Cpf and Ativo = 1";
		$select = $conx->prepare($result);
		$select->bindParam(':Cpf',$cpf);
		$select->execute();
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 0){
                $variavelControle = 0;
                $_SESSION['mensagemErro'] = "Já há um usuário ativo com esse cpf cadastrado!";}}
        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_USUARIO WHERE ".'Login'." like :Login";
		$select = $conx->prepare($result);
		$select->bindParam(':Login',$login);
		$select->execute();
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 0){
                $variavelControle = 0;
                $_SESSION['mensagemErro'] = "Já há um usuário com esse login cadastrado!";}}
        if($variavelControle !=0){
            if($tipo != 2){
                $result = "INSERT INTO $db.$TB_USUARIO ".'(Login'.",Senha,Nome,Administrador,Cpf, Tipo, Ativo) VALUES (:Login,:Senha,:Nome,:Administrador,:Cpf,:Tipo,:Ativo)";
                $insert = $conx->prepare($result);
                $insert->bindParam(':Login',$login);
                $insert->bindParam(':Senha',$senha);
                $insert->bindParam(':Nome',$nome);
                $insert->bindParam(':Administrador',$administrador);
                $insert->bindParam(':Cpf',$cpf);
                $insert->bindParam(':Tipo',$tipo);
				$insert->bindParam(':Ativo',$ativo);
                $insert->execute();
                if($tipo == 1){
                    $usuario = $conx->lastInsertId();
                    $result = "INSERT INTO $db.$TB_PROFESSOR (Usuario_idUsuario) VALUES (:Usuario)";
                    $insert = $conx->prepare($result);
                    $insert->bindParam(':Usuario',$usuario);
                    $insert->execute();
                }
                $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';}            
            else{
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
                $result = "SELECT count(*) 'quantidade' FROM $db.$TB_ALUNO WHERE Matricula = :Matricula";
                $select = $conx->prepare($result);
                $select->bindParam(':Matricula',$matricula);
                $select->execute();
                foreach($select->fetchAll() as $linha_array){
                    if($linha_array['quantidade'] != 0){
                        $variavelControle = 0;
                        $_SESSION['mensagemErro'] = "Já há um usuário com essa matrícula cadastrada!";
                    }
                }
                $administrador = 0;
                if($variavelControle){
                    $result = "INSERT INTO $db.$TB_USUARIO ".'(Login'.",Senha,Nome,Administrador,Cpf, Tipo, Ativo) VALUES (:Login,:Senha,:Nome,:Administrador,:Cpf,:Tipo,:Ativo)";
                    $insert = $conx->prepare($result);
                    $insert->bindParam(':Login',$login);
                    $insert->bindParam(':Senha',$senha);
                    $insert->bindParam(':Nome',$nome);
                    $insert->bindParam(':Administrador',$administrador);
                    $insert->bindParam(':Cpf',$cpf);
                    $insert->bindParam(':Tipo',$tipo);
                    $insert->bindParam(':Ativo', $ativo);
                    $insert->execute();
           
                    $usuario = $conx->lastInsertId();
                    $result = "INSERT INTO $db.$TB_ALUNO (Matricula,Usuario_idUsuario,Curso_idCurso) VALUES (:Matricula,:Usuario,:Curso)";
                    $insert = $conx->prepare($result);
                    $insert->bindParam(':Matricula',$matricula);
                    $insert->bindParam(':Usuario',$usuario);
                    $insert->bindParam(':Curso',$idCurso);
                    $insert->execute();
                    $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';}                                
            }}	
            header("Location: ../index.php");
        }
    catch(PDOException $e) {
            $msgErr = "Erro na inclusão: <br />";
            $_SESSION['mensagemErro'] = $msgErr;     			
    }
}
?>