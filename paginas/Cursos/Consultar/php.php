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
    if(strlen($nome) > 50){
        $nome = "";
    }
    $nome = "%".$nome."%";
    $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
    if(!is_numeric($id) || $id < 1 || $id > 99999999999){
        $id = "";
    }
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_CURSO WHERE Nome like :nome";
		$select = $conx->prepare($result);
		$select->bindParam(':nome',$nome);
		$select->execute();
        $variavelControle = 1;//nome
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] == 0 || $nome == "%%"){
                $variavelControle = 0;}}
        if(!$variavelControle){
            $result = "SELECT count(*) 'quantidade' FROM $db.$TB_CURSO WHERE idCurso like :idCurso";
            $select = $conx->prepare($result);
            $select->bindParam(':idCurso',$id);
            $select->execute();
            $variavelControle = 2;//id           
            foreach($select->fetchAll() as $linha_array){
                if($linha_array['quantidade'] == 0){
                    $variavelControle = 0;}}           
        }
        if($variavelControle){
            if($variavelControle == 1){//nome
                $result = "SELECT * FROM $db.$TB_CURSO WHERE Nome like :nome";
                $select = $conx->prepare($result);
                $select->execute(['nome' => $nome]);
                $_SESSION['queryCurso1'] = $select->fetchAll();
            }
            else{//id
                $result = "SELECT * FROM $db.$TB_CURSO WHERE idCurso=:idCurso";
                $select = $conx->prepare($result);
                $select->execute(['idCurso' => $id]);
                $_SESSION['queryCurso1'] = $select->fetchAll();
            }  
            $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';}
        else{
            if($nome=="%%" && $id==""){
                $result = "SELECT * FROM $db.$TB_CURSO";
                $select = $conx->prepare($result);
                $select->execute();
                $_SESSION['queryCurso1'] = $select->fetchAll();                
            }
            else{
                $_SESSION['mensagemErro'] = 'A consulta não retornou resultados';
            }
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