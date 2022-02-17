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
$nome = filter_input(INPUT_POST,'nome',FILTER_SANITIZE_STRING);
if(strlen($nome)<1 || strlen($nome) > 50){
    $nome = "DisciplinaSemNome".rand(0,1000);
}
$descricao = filter_input(INPUT_POST,'descricao',FILTER_SANITIZE_STRING);
if(strlen($descricao)<1 || strlen($descricao)>500){
    $descricao = 'Disciplina...';
}
$codigo = filter_input(INPUT_POST,'codigo', FILTER_SANITIZE_NUMBER_INT);
if(!is_numeric($codigo) || $codigo < 1 || $codigo > 9999){
    $codigo = rand(0,1000);
}
$regexSigla = '/[a-zA-Z][a-zA-Z][a-zA-Z]\d\d\d/';
$sigla = filter_input(INPUT_POST,'sigla',FILTER_SANITIZE_STRING);
if(!preg_match($regexSigla, $sigla) || strlen($sigla)>6){
    $sigla = "AAA000";
}
if($send == 'Alterar'){
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_DISCIPLINA WHERE Nome=:nome and idDisciplina!=:id";
		$select = $conx->prepare($result);
		$select->bindParam(':nome',$nome);
        $select->bindParam(':id',$id);
		$select->execute();
        $variavelControle = 1;
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 0){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Já há uma disciplina com esse nome cadastrado!";}}
            
        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_DISCIPLINA WHERE Sigla=:sigla and idDisciplina!=:id";
		$select = $conx->prepare($result);
		$select->bindParam(':sigla',$sigla);
        $select->bindParam(':id',$id);
		$select->execute();
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 0){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Já há uma disciplina com essa sigla cadastrada!";}}

        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_DISCIPLINA WHERE Código=:codigo and idDisciplina!=:id";
		$select = $conx->prepare($result);
		$select->bindParam(':codigo',$codigo);
        $select->bindParam(':id',$id);
		$select->execute();
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 0){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Já há uma disciplina com esse código cadastrada!";}}

        if($variavelControle){    
            $result = "UPDATE $db.$TB_DISCIPLINA SET Nome=:nome, Descrição=:descricao, Código=:codigo,Sigla=:sigla WHERE idDisciplina = :id";
            $insert = $conx->prepare($result);
            $insert->bindParam(':nome',$nome);
            $insert->bindParam(':descricao',$descricao);
            $insert->bindParam(':codigo',$codigo);
            $insert->bindParam(':sigla',$sigla);
            $insert->bindParam(':id',$id);
            $insert->execute();
            $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';}
        header("Location: ../index.php");	
        }
    catch(PDOException $e) {
            $msgErr = "Erro na alteração:<br />".$e->getMessage();
            $_SESSION['mensagemErro'] = $msgErr;     
			header("Location: ../index.php");			
    }
}
else if($send == 'Excluir'){
    $result= "DELETE FROM $db.$TB_CURSODISCIPLINA WHERE Disciplina_idDisciplina=:idDisciplina";
    $delete = $conx->prepare($result);
    $delete->bindParam(':idDisciplina', $id);
    $delete->execute();

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