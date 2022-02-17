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
	$curso = filter_input(INPUT_POST,'curso',FILTER_SANITIZE_STRING);
    if(strlen($curso) > 50){
        $curso = "";
    }
    $disciplina = filter_input(INPUT_POST,'disciplina',FILTER_SANITIZE_STRING);
    if(strlen($disciplina)>50){
        $disciplina = "";
    }
    $tipo = filter_input(INPUT_POST,'tipo',FILTER_SANITIZE_STRING);
    if($tipo != 'Obrigatória' && $tipo != 'Escolha' && $tipo != 'Eletiva'){
        $tipo = 'Obrigatória';
    }
    if($tipo=='Obrigatória'){
        $tipo = 0;
    }else if($tipo == 'Eletiva'){
        $tipo = 1;
    }else{
        $tipo = 2;
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
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_CURSO WHERE Nome = :nome";
		$select = $conx->prepare($result);
		$select->bindParam(':nome',$curso);
		$select->execute();
        $variavelControle = 1;
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 1){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Não há um curso com esse nome cadastrado!";}}

        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_DISCIPLINA WHERE nome = :nome";
		$select = $conx->prepare($result);
		$select->bindParam(':nome',$disciplina);
		$select->execute();
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 1){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Não há uma disciplina com esse nome cadastrado!";}}
        if($variavelControle){
            $result = "SELECT idCurso FROM $db.$TB_CURSO WHERE nome = :nome";
            $select = $conx->prepare($result);
            $select->bindParam(':nome',$curso);
            $select->execute();
            $idCurso = 0;
            foreach($select->fetchAll() as $linha_array){
                    $idCurso = $linha_array['idCurso'];}
            $result = "SELECT idDisciplina FROM $db.$TB_DISCIPLINA WHERE nome = :nome";
            $select = $conx->prepare($result);
            $select->bindParam(':nome',$disciplina);
            $select->execute();
            $idDisciplina = 0;
            foreach($select->fetchAll() as $linha_array){
                $idDisciplina = $linha_array['idDisciplina'];}            
            $result = "INSERT INTO $db.$TB_CURSODISCIPLINA (Curso_idCurso, Disciplina_idDisciplina, Tipo, Ativa) VALUES (:idCurso, :idDisciplina, :tipo, :ativa)";
            $insert = $conx->prepare($result);
            $insert->bindParam(':idCurso',$idCurso);
            $insert->bindParam(':idDisciplina',$idDisciplina);
            $insert->bindParam(':tipo',$tipo);
            $insert->bindParam(':ativa',$ativa);
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