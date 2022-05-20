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
$send2 = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);//usuário vindo alterar a partir da tela de consulta de disciplina
$send3 = filter_input(INPUT_POST,'id2',FILTER_SANITIZE_NUMBER_INT);//usuário vindo alterar a partir da tela de critica de usuário
if($send || $send2 || $send3){
    $variavelControle = 1;
	$id = 0;
	if($send || $send2){
		$id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);}
	else{
		$id = filter_input(INPUT_POST,'id2',FILTER_SANITIZE_NUMBER_INT);
	}
    if(!is_numeric($id) || $id < 1 || $id > 99999999999){
        $id = -1;
    }
    $result = "SELECT A1.idAluno FROM $db.$TB_ALUNO A1 where A1.Usuario_idUsuario=:id";
    $select = $conx->prepare($result);
    $select->bindParam(':id',$_SESSION['idUsuarioLogin']);
    $select->execute();
    foreach($select->fetchAll() as $linha_array){
        $idAluno = $linha_array['idAluno'];
    }
    if($_SESSION['tipoLogin'] != 2 && $_SESSION['administradorLogin']){
        $idAluno = "%%";
    }
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_CRITICA WHERE idCritica=:idCritica and Aluno_idAluno like :idAluno";
		$select = $conx->prepare($result);
		$select->bindParam(':idCritica',$id);
        $select->bindParam(':idAluno',$idAluno);
		$select->execute();
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 1){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Não há critica cadastrada com esse id!";}}
        if($variavelControle){    
            $result = "SELECT C1.idCritica, U1.Nome, C1.NotaDisciplina, C1.NotaAluno, C1.NotaEvolucao, C1.Descrição, C1.ProfessorDisciplina_idProfessorDisciplina, C1.AnoSemestre, C1.Elogios, C1.Criticas FROM $db.$TB_CRITICA C1 inner join $db.$TB_ALUNO A1 ON  C1.Aluno_idAluno = A1.idAluno inner join $db.$TB_USUARIO U1 ON A1.Usuario_idUsuario = U1.idUsuario Where C1.idCritica = :id";
            $select = $conx->prepare($result);
            $select->bindParam(':id',$id);
            $select->execute();   
            $_SESSION['queryCritica3'] = $select->fetchAll();
            $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!'; }
        header("Location: ./alterar.php");	
        }
    catch(PDOException $e) {
            $msgErr = "Erro na consulta:<br />";
            $_SESSION['mensagemErro'] = $msgErr;     
			header("Location: ../index.php");			
    }
}
?>