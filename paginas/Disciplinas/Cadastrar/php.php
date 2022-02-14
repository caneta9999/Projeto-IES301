<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']) || $_SESSION['administradorLogin']!=1)
{
  header('location:../../Login/index.php');
}?>
<?php
require '../../../CamadaDados/conectar.php';
$tb = 'Disciplina';
$send=filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
if($send){
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
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$tb WHERE Nome=:nome";
		$select = $conx->prepare($result);
		$select->bindParam(':nome',$nome);
		$select->execute();
        $variavelControle = 1;
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 0){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Já há uma disciplina com esse nome cadastrado!";}}
            
        $result = "SELECT count(*) 'quantidade' FROM $db.$tb WHERE Sigla=:sigla";
		$select = $conx->prepare($result);
		$select->bindParam(':sigla',$sigla);
		$select->execute();
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 0){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Já há uma disciplina com essa sigla cadastrada!";}}

        $result = "SELECT count(*) 'quantidade' FROM $db.$tb WHERE Código=:codigo";
		$select = $conx->prepare($result);
		$select->bindParam(':codigo',$codigo);
		$select->execute();
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 0){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Já há uma disciplina com esse código cadastrada!";}}
        if($variavelControle){    
            $result = "INSERT INTO $db.$tb (Nome,Descrição,Código,Sigla) VALUES (:nome,:descricao,:codigo,:sigla)";
            $insert = $conx->prepare($result);
            $insert->bindParam(':nome',$nome);
            $insert->bindParam(':descricao',$descricao);
            $insert->bindParam(':codigo',$codigo);
            $insert->bindParam(':sigla',$sigla);
            $insert->execute();
            $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';}
        header("Location: ../index.php");	
        }
    catch(PDOException $e) {
            $msgErr = "Erro na inclusão:<br />". $e->getMessage();
            $_SESSION['mensagemErro'] = $msgErr;     
			header("Location: ../index.php");			
    }
}
?>