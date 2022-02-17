<?php
session_start();
require '../../camadaDados/conectar.php';
require '../../camadaDados/tabelas.php';
$send=filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
if($send){
	$login = filter_input(INPUT_POST,'login',FILTER_SANITIZE_STRING);
	$senha = filter_input(INPUT_POST,'senha',FILTER_SANITIZE_STRING);
    try{
		if(strlen($login)<1 || strlen($login) > 100){
			$login = "";
		}
		if(strlen($senha)<8 || strlen($senha)>50){
			$senha = "";
		}
		$result = "SELECT idUsuario,Senha,Tipo,Administrador FROM $db.$TB_USUARIO WHERE"." Login"."=:login";
		$select = $conx->prepare($result);
		$select->execute([':login'=>$login]);
		$select = $select->fetchAll();
		if(!$select){
				unset($_SESSION['idUsuarioLogin']);
                unset($_SESSION['tipoLogin']);
                unset($_SESSION['administradorLogin']);
                unset($_SESSION['idCursoLogin']);
				$_SESSION['mensagemErro'] = 'Login inexistente!';
				header("Location: ./index.php");}
		else{
		    foreach($select as $linha_array) {
				if((strcmp($linha_array['Senha'], $senha))==0){
					unset ($_SESSION['mensagemErro']);
					$_SESSION['idUsuarioLogin'] = $linha_array['idUsuario'];
                    $_SESSION['tipoLogin'] = $linha_array['Tipo'];
                    $_SESSION['administradorLogin'] = $linha_array['Administrador'];
                    if($_SESSION['tipoLogin'] == 2){
                        $result = "SELECT Curso_idCurso FROM $db.$TB_ALUNO WHERE Usuario_idUsuario=:Usuario";
                        $select = $conx->prepare($result);
                        $select->execute([':Usuario'=>$_SESSION['idUsuarioLogin']]);
                        $select = $select->fetchAll();
                        foreach($select as $linha_array){
                            $_SESSION['idCursoLogin'] = $select['Curso_idCurso'];
                        }
                    }
					header("Location: ../index.php");	
				}else{
                    unset($_SESSION['idUsuarioLogin']);
                    unset($_SESSION['tipoLogin']);
                    unset($_SESSION['administradorLogin']);
                    unset($_SESSION['idCursoLogin']);
					$_SESSION['mensagemErro'] = 'Senha errada!';
					header("Location: ./index.php");}}}
		}
    catch(PDOException $e) {
            $mensagemErro = "Erro no login: " . $e . "<br />";
            $_SESSION['mensagemErro'] = $mensagemErro;      
			header("Location: ./index.php");			
    }
}else{
	$_SESSION['msgLogin'] = "<p>Mensagem de login não enviada</p>";
	header("Location: ./index.php");	
}
?>