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
if($send){
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
    if(strlen($sigla)>6 || strlen($sigla)<1){
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
	$curso = filter_input(INPUT_POST,'curso',FILTER_SANITIZE_NUMBER_INT);
	if(!is_numeric($curso) || $curso < 1 || $curso > 99999999999){
		$curso = 1;
	}
	$variavelControle = 1;

    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_DISCIPLINA WHERE Código=:codigo";
		$select = $conx->prepare($result);
		$select->bindParam(':codigo',$codigo);
		$select->execute();
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 0){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Já há uma disciplina com esse código cadastrada!";}}
        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_CURSO WHERE idCurso=:curso";
		$select = $conx->prepare($result);
		$select->bindParam(':curso',$curso);
		$select->execute();
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 1){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Não identificamos um curso com esse id!";}}
        if ($descricaoLongaDemais) {
            $variavelControle = 0;
            $_SESSION['mensagemErro'] = "A descrição inserida é longa demais!";
        }

        if($variavelControle){    
            $result = "INSERT INTO $db.$TB_DISCIPLINA (Nome,Descrição,Código,Sigla,Curso_idCurso,Tipo,Ativa) VALUES (:nome,:descricao,:codigo,:sigla,:idCurso,:tipo,:ativa)";
            $insert = $conx->prepare($result);
            $insert->bindParam(':nome',$nome);
            $insert->bindParam(':descricao',$descricao);
            $insert->bindParam(':codigo',$codigo);
            $insert->bindParam(':sigla',$sigla);
			$insert->bindParam(':tipo',$tipo);
            $insert->bindParam(':ativa',$ativa);
			$insert->bindParam(':idCurso',$curso);
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
?>