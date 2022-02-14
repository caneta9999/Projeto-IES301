<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']) || $_SESSION['administradorLogin']!=1)
{
  header('location:../../Login/index.php');
}?>
<?php
require '../../../CamadaDados/conectar.php';
$tb = 'Usuario';
$tb2 = 'Professor';
$tb3 = 'Aluno';
$tb4 = 'Curso';
$send=filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING); 
if($send){
	$login = filter_input(INPUT_POST,'login',FILTER_SANITIZE_STRING);
	$senha = filter_input(INPUT_POST,'senha',FILTER_SANITIZE_STRING);
	$nome = filter_input(INPUT_POST,'nome',FILTER_SANITIZE_STRING);
    $administrador = filter_input(INPUT_POST,'administrador',FILTER_SANITIZE_STRING);
    $cpf = filter_input(INPUT_POST,'cpf',FILTER_SANITIZE_NUMBER_INT);
    $tipo = filter_input(INPUT_POST,'tipo', FILTER_SANITIZE_STRING);
    $curso = filter_input(INPUT_POST,'curso',FILTER_SANITIZE_STRING);
    $matricula = filter_input(INPUT_POST,'matricula',FILTER_SANITIZE_NUMBER_INT);

    $variavelControle = 1;
    
    if(strlen($login) > 100 || !filter_var($login, FILTER_VALIDATE_EMAIL)){
        $variavelControle = 0;
        $_SESSION['mensagemErro'] = 'Email inválido';
    }

    if(strlen($senha) > 50 || strlen($senha) < 8){
        $senha = '01234567';
    }
    if(strlen($nome)<1 || strlen($nome) >100){
        $nome = 'Paulo';
    }
    if($administrador != true && $administrador != false){
        $administrador = 0;
    }
    if($administrador == true){
        $administrador = 1;}
    else if($administrador == false){
        $administrador = 0;
    }
    if(!is_numeric($cpf) || $cpf < 1 || $cpf>99999999999){
        $variavelControle = 0;
        $_SESSION['mensagemErro'] = 'CPF inválido';
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
        $variavelControle = 0;
        $_SESSION['mensagemErro'] = 'Matricula inválida';
    }
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$tb WHERE Cpf like :Cpf";
		$select = $conx->prepare($result);
		$select->bindParam(':Cpf',$cpf);
		$select->execute();
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 0){
                $variavelControle = 0;
                $_SESSION['mensagemErro'] = "Já há um usuário com esse cpf cadastrado!";}}
        $result = "SELECT count(*) 'quantidade' FROM $db.$tb WHERE ".'Login'." like :Login";
		$select = $conx->prepare($result);
		$select->bindParam(':Login',$login);
		$select->execute();
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 0){
                $variavelControle = 0;
                $_SESSION['mensagemErro'] = "Já há um usuário com esse login cadastrado!";}}
        if($variavelControle !=0){
            if($tipo != 2){
                $result = "INSERT INTO $db.$tb ".'(Login'.",Senha,Nome,Administrador,Cpf, Tipo) VALUES (:Login,:Senha,:Nome,:Administrador,:Cpf,:Tipo)";
                $insert = $conx->prepare($result);
                $insert->bindParam(':Login',$login);
                $insert->bindParam(':Senha',$senha);
                $insert->bindParam(':Nome',$nome);
                $insert->bindParam(':Administrador',$administrador);
                $insert->bindParam(':Cpf',$cpf);
                $insert->bindParam(':Tipo',$tipo);
                $insert->execute();
                if($tipo == 1){
                    $usuario = $conx->lastInsertId();
                    $result = "INSERT INTO $db.$tb2 (Usuario_idUsuario) VALUES (:Usuario)";
                    $insert = $conx->prepare($result);
                    $insert->bindParam(':Usuario',$usuario);
                    $insert->execute();
                }
                $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';}            
            else{
                $result = "SELECT idCurso FROM $db.$tb4 WHERE Nome=:Nome";
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
                $result = "SELECT count(*) 'quantidade' FROM $db.$tb3 WHERE Matricula like :Matricula";
                $select = $conx->prepare($result);
                $select->bindParam(':Matricula',$matricula);
                $select->execute();
                foreach($select->fetchAll() as $linha_array){
                    if($linha_array['quantidade'] != 0){
                        $variavelControle = 0;
                        $_SESSION['mensagemErro'] = "Já há um usuário com essa matrícula cadastrada!";}}
                if($administrador == 1){
                    $administrador = 0;
                }
                if($variavelControle){
                    $result = "INSERT INTO $db.$tb ".'(Login'.",Senha,Nome,Administrador,Cpf, Tipo) VALUES (:Login,:Senha,:Nome,:Administrador,:Cpf,:Tipo)";
                    $insert = $conx->prepare($result);
                    $insert->bindParam(':Login',$login);
                    $insert->bindParam(':Senha',$senha);
                    $insert->bindParam(':Nome',$nome);
                    $insert->bindParam(':Administrador',$administrador);
                    $insert->bindParam(':Cpf',$cpf);
                    $insert->bindParam(':Tipo',$tipo);
                    $insert->execute();
                    $usuario = $conx->lastInsertId();
                    $result = "INSERT INTO $db.$tb3 (Matricula,Usuario_idUsuario,Curso_idCurso) VALUES (:Matricula,:Usuario,:Curso)";
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
            $msgErr = "Erro na inclusão:<br />";
            $_SESSION['mensagemErro'] = $msgErr;     			
    }
}
?>