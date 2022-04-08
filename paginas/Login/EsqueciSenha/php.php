<?php
session_start();
$send=filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
if($send){
	$_SESSION['mensagemFinalizacao'] = 'Verifique seu email para obter a nova senha!';
}else{
	$_SESSION['mensagemErro'] = 'Falha no envio';	
}
header("Location: ../index.php");
?>