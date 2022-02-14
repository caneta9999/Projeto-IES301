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
$tb5 = 'professordisciplina';
$tb6 = 'Critica';
$send=filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
$id = filter_input(INPUT_POST, 'id',FILTER_SANITIZE_NUMBER_INT);
if($id != $_SESSION['idAlteracao2']){
    $id = $_SESSION['idAlteracao2'];
    unset($_SESSION['idAlteracao2']);
}
$login = filter_input(INPUT_POST,'login',FILTER_SANITIZE_STRING);
$senha = filter_input(INPUT_POST,'senha',FILTER_SANITIZE_STRING);
$nome = filter_input(INPUT_POST,'nome',FILTER_SANITIZE_STRING);
$administrador = filter_input(INPUT_POST,'administrador',FILTER_SANITIZE_STRING);
$cpf = filter_input(INPUT_POST,'cpf',FILTER_SANITIZE_NUMBER_INT);
$tipo = filter_input(INPUT_POST,'tipo', FILTER_SANITIZE_STRING);
if($tipo != $_SESSION['tipoAlteracao']){
    $tipo = $_SESSION['tipoAlteracao'];
    unset($_SESSION['tipoAlteracao']);
}
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
if(strlen($curso) < 1 && strlen($curso) > 100){
        $variavelControle = 0;
        $_SESSION['mensagemErro'] = 'Curso inválido';
}
if((!is_numeric($matricula) || $matricula <1 || $matricula>99999999) && $tipo == 2){
        $variavelControle = 0;
        $_SESSION['mensagemErro'] = 'Matricula inválida';
}
if($send == 'Alterar'){
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$tb WHERE Cpf like :Cpf and idUsuario != :Id";
		$select = $conx->prepare($result);
		$select->bindParam(':Cpf',$cpf);
        $select->bindParam(':Id',$id);
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
                $result = "UPDATE $db.$tb SET".' Login=:Login'.",Senha=:Senha,Nome=:Nome,Administrador=:Administrador,Cpf=:Cpf Where idUsuario=:Id";
                $insert = $conx->prepare($result);
                $insert->bindParam(':Login',$login);
                $insert->bindParam(':Senha',$senha);
                $insert->bindParam(':Nome',$nome);
                $insert->bindParam(':Administrador',$administrador);
                $insert->bindParam(':Cpf',$cpf);
                $insert->bindParam(':Id',$id);
                $insert->execute();
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
                $result = "SELECT count(*) 'quantidade' FROM $db.$tb3 WHERE Matricula like :Matricula and Usuario_idUsuario != :Id";
                $select = $conx->prepare($result);
                $select->bindParam(':Matricula',$matricula);
                $select->bindParam(':Id',$id);
                $select->execute();
                foreach($select->fetchAll() as $linha_array){
                    if($linha_array['quantidade'] != 0){
                        $variavelControle = 0;
                        $_SESSION['mensagemErro'] = "Já há um usuário com essa matrícula cadastrada!";}}
                if($administrador == 1){
                    $administrador = 0;
                }
                $result = "UPDATE $db.$tb SET".' Login=:Login'.",Senha=:Senha,Nome=:Nome,Administrador=:Administrador,Cpf=:Cpf Where idUsuario=:Id";
                $insert = $conx->prepare($result);
                $insert->bindParam(':Login',$login);
                $insert->bindParam(':Senha',$senha);
                $insert->bindParam(':Nome',$nome);
                $insert->bindParam(':Administrador',$administrador);
                $insert->bindParam(':Cpf',$cpf);          
                $insert->bindParam(':Id',$id);
                $insert->execute();
                $result = "UPDATE $db.$tb3 SET Matricula=:Matricula,Curso_idCurso=:Curso Where Usuario_idUsuario=:Usuario";
                $insert = $conx->prepare($result);
                $insert->bindParam(':Matricula',$matricula);
                $insert->bindParam(':Usuario',$id);
                $insert->bindParam(':Curso',$idCurso);
                $insert->execute();
                $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';                
            }}
        header("Location: ../index.php");	
        }
    catch(PDOException $e) {
            $msgErr = "Erro na alteração:<br />".$e->getMessage();
            $_SESSION['mensagemErro'] = $msgErr;     
			header("Location: ../index.php");			
    }
}
else if($send == 'Excluir'){
    if($tipo == 0){
        $result= "DELETE FROM $db.$tb WHERE idUsuario=:idUsuario";
        $delete = $conx->prepare($result);
        $delete->bindParam(':idUsuario', $id);
        $delete->execute();
    }else if($tipo == 1){
        $result = "SELECT idProfessor FROM $db.$tb2 WHERE Usuario_idUsuario=:idUsuario";
        $select = $conx->prepare($result);
        $select->bindParam(':idUsuario',$id);
        $select->execute();
        $idProfessor = 0;
        foreach($select->fetchAll() as $linha_array){
            $idProfessor = $linha_array['idProfessor'];}
        $result= "DELETE FROM $db.$tb5 WHERE Professor_idProfessor=:idProfessor";
        $delete = $conx->prepare($result);
        $delete->bindParam(':idProfessor', $idProfessor);
        $delete->execute();        
        $result= "DELETE FROM $db.$tb2 WHERE Usuario_idUsuario=:idUsuario";
        $delete = $conx->prepare($result);
        $delete->bindParam(':idUsuario', $id);
        $delete->execute();
        $result= "DELETE FROM $db.$tb WHERE idUsuario=:idUsuario";
        $delete = $conx->prepare($result);
        $delete->bindParam(':idUsuario', $id);
        $delete->execute();                
    }else if($tipo == 2){
        $aluno = $_SESSION['idUsuarioLogin'];
        $result = "SELECT A1.idAluno FROM $db.$tb3 A1 inner join $db.$tb U1 ON U1.idUsuario = A1.Usuario_idUsuario WHERE U1.idUsuario = :idUsuario";
        $select = $conx->prepare($result);
        $select->bindParam(':idUsuario',$id);
        $select->execute();
        $variavelControle = 1;
        $aluno = '';
        foreach($select->fetchAll() as $linha_array){
            $aluno = $linha_array['idAluno'];
            break;
        } 
        $result= "DELETE FROM $db.$tb6 WHERE Aluno_idAluno=:id";
        $delete = $conx->prepare($result);
        $delete->bindParam(':id', $aluno);
        $delete->execute();        
        $result= "DELETE FROM $db.$tb3 WHERE Usuario_idUsuario=:idUsuario";
        $delete = $conx->prepare($result);
        $delete->bindParam(':idUsuario', $id);
        $delete->execute();
        $result= "DELETE FROM $db.$tb WHERE idUsuario=:idUsuario";
        $delete = $conx->prepare($result);
        $delete->bindParam(':idUsuario', $id);
        $delete->execute();                
    }
    $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';
    header("Location: ../index.php");
}
?>