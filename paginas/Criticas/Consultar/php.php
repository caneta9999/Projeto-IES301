<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']))
{
  header('location:../../Login/index.php');
}?>
<?php
require '../../../CamadaDados/conectar.php';
$tb = 'critica';
$tb2 = 'Aluno';
$tb3 = 'usuario';
$send=filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
if($send == 'Ver as críticas do aluno'){
    $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
    if(!is_numeric($id) || $id < 1 || $id > 99999999999){
        $id = "";
    }
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$tb WHERE Aluno_idAluno = :id";
		$select = $conx->prepare($result);
		$select->bindParam(':id',$id);
		$select->execute();
        $variavelControle = 1;
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] < 1){
                $variavelControle = 0;}}
        if($variavelControle){
            $result = "SELECT C1.idCritica, U1.Nome, C1.NotaDisciplina, C1.NotaProfessor, C1.Descrição, C1.ProfessorDisciplina_idProfessorDisciplina, C1.Data FROM $db.$tb C1 inner join $db.$tb2 A1 ON  C1.Aluno_idAluno = A1.idAluno inner join $db.$tb3 U1 ON A1.Usuario_idUsuario = U1.idUsuario Where A1.idAluno = :id";
            $select = $conx->prepare($result);
            $select->bindParam(':id',$id);
            $select->execute();   
            $_SESSION['queryCritica1'] = $select->fetchAll();
            $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';        
        }else{
            $_SESSION['mensagemErro'] = 'Não foi possível achar as críticas do aluno!';
        }
        header("Location: ./consultar.php");	
    }
    catch(PDOException $e) {
            $msgErr = "Erro na consulta:<br />".$e->getMessage();
            $_SESSION['mensagemErro'] = $msgErr;     
			header("Location: ../index.php");			
    }
}else if($send == 'Ver suas críticas'){
    $result = "SELECT idAluno FROM $db.$tb2 where Usuario_idUsuario = :id";
    $select = $conx->prepare($result);
    $select->bindParam(':id',$_SESSION['idUsuarioLogin']);
    $select->execute();
    $id = "";
    foreach($select->fetchAll() as $linha_array){
        $id = $linha_array['idAluno'];
    }    
    $result = "SELECT count(*) 'quantidade' FROM $db.$tb WHERE Aluno_idAluno = :id";
    $select = $conx->prepare($result);
    $select->bindParam(':id',$id);
    $select->execute();
    $variavelControle = 1;
    foreach($select->fetchAll() as $linha_array){
        if($linha_array['quantidade'] < 1){
            $variavelControle = 0;}}
    if($variavelControle){
        $result = "SELECT C1.idCritica, U1.Nome, C1.NotaDisciplina, C1.NotaProfessor, C1.Descrição, C1.ProfessorDisciplina_idProfessorDisciplina, C1.Data FROM $db.$tb C1 inner join $db.$tb2 A1 ON  C1.Aluno_idAluno = A1.idAluno inner join $db.$tb3 U1 ON A1.Usuario_idUsuario = U1.idUsuario Where A1.idAluno = :id";
        $select = $conx->prepare($result);
        $select->bindParam(':id',$id);
        $select->execute();   
        $_SESSION['queryCritica1'] = $select->fetchAll();
        $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';        
    }else{
        $_SESSION['mensagemErro'] = 'Não foi possível achar as suas críticas!';
    }
    header("Location: ./consultar.php");
}
?>