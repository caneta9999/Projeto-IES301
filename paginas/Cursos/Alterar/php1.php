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
$send2 = filter_input(INPUT_POST,'id2',FILTER_SANITIZE_NUMBER_INT);//usuário vindo alterar a partir da tela de consulta
if($send || $send2){
	$id = 0;
	if($send){
		$id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);}
	else{
		$id = filter_input(INPUT_POST,'id2',FILTER_SANITIZE_NUMBER_INT);
	}
    if(!is_numeric($id) || $id < 1 || $id > 99999999999){
        $id = -1;
    }
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_CURSO WHERE idCurso=:idCurso";
		$select = $conx->prepare($result);
		$select->bindParam(':idCurso',$id);
		$select->execute();
        $variavelControle = 1;
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 1){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Não há curso com esse id!";}}
        if($variavelControle){    
            $result = "SELECT * FROM $db.$TB_CURSO WHERE idCurso=:idCurso";
            $select = $conx->prepare($result);
            $select->execute(['idCurso' => $id]);
            $_SESSION['queryCurso2'] = $select->fetchAll();
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