<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']) || $_SESSION['administradorLogin']!=1)
{
  header('location:../../Login/index.php');
}?>
<?php
require '../../../CamadaDados/conectar.php';
$tb = 'Curso';
$send=filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
if($send){
	$nome = filter_input(INPUT_POST,'nome',FILTER_SANITIZE_STRING);
    try{
        if(strlen($nome)<1 || strlen($nome) > 50){
            $nome = "CursoSemNome".rand(0,1000);
        }
        $result = "SELECT count(*) 'quantidade' FROM $db.$tb WHERE nome like :nome";
		$select = $conx->prepare($result);
		$select->bindParam(':nome',$nome);
		$select->execute();
        $variavelControle = 1;
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 0){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Já há um curso com esse nome cadastrado!";}}
        if($variavelControle){    
            $result = "INSERT INTO $db.$tb (Nome) VALUES (:nome)";
            $insert = $conx->prepare($result);
            $insert->bindParam(':nome',$nome);
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