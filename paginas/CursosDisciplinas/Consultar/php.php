<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']))
{
  header('location:../../Login/index.php');
}?>
<?php
require '../../../camadaDados/conectar.php';
require '../../../camadaDados/tabelas.php';
$send=filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
if($send){
	$nome = filter_input(INPUT_POST,'nome',FILTER_SANITIZE_STRING);
    if(strlen($nome) > 100){
        $nome = "";
    }
    $nome = "%".$nome."%";
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_CURSO WHERE Nome like :nome";
		$select = $conx->prepare($result);
		$select->bindParam(':nome',$nome);
		$select->execute();
        $variavelControle = 1;//nome
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 1){
                $variavelControle = 0;}}
        if($variavelControle){
            $result = "SELECT CD1.CursoDisciplinaId,C1.nome 'CursoNome', D1.nome, CD1.tipo, CD1.ativa FROM $db.$TB_CURSODISCIPLINA CD1 inner join $db.$TB_DISCIPLINA D1 ON CD1.Disciplina_idDisciplina=D1.idDisciplina inner join $db.$TB_CURSO C1 ON CD1.Curso_idCurso=C1.idCurso WHERE C1.Nome like :nome";
            $select = $conx->prepare($result);
            $select->bindParam(':nome',$nome);
            $select->execute();   
            $_SESSION['queryCursoDisciplina1'] = $select->fetchAll();
            $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';        
        }else{
            $_SESSION['mensagemErro'] = 'Não foi possível achar o curso e suas disciplinas!';
        }
        header("Location: ./consultar.php");	
    }
    catch(PDOException $e) {
            $msgErr = "Erro na consulta:<br />";
            $_SESSION['mensagemErro'] = $msgErr;     
			header("Location: ../index.php");			
    }
}
?>