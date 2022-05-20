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
$send2 = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);//usuário vindo alterar a partir da tela de consulta
if($send || $send2){
	$id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
    if(!is_numeric($id) || $id < 1 || $id > 99999999999){
        $id = -1;
    }
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_DISCIPLINA WHERE idDisciplina=:idDisciplina";
		$select = $conx->prepare($result);
		$select->bindParam(':idDisciplina',$id);
		$select->execute();
        $variavelControle = 1;
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 1){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Não há disciplina com esse id!";}}
        if($variavelControle){    
            $result = "SELECT D1.Nome,D1.idDisciplina,D1.Sigla,D1.Código,D1.Tipo,D1.Ativa, D1.Descrição, C1.Nome 'NomeCurso' FROM $db.$TB_DISCIPLINA D1 inner join $db.$TB_CURSO C1 on D1.Curso_idCurso = C1.idCurso WHERE idDisciplina=:idDisciplina";
            $select = $conx->prepare($result);
            $select->execute(['idDisciplina' => $id]);
            $_SESSION['queryDisciplina2'] = $select->fetchAll();
            $_SESSION['mensagemFinalizacao'] =  'Operação finalizada com sucesso!';}
        header("Location: ./alterar.php");	
        }
    catch(PDOException $e) {
            $msgErr = "Erro na consulta:<br />";
            $_SESSION['mensagemErro'] = $msgErr;     
			header("Location: ../index.php");			
    }
}
?>