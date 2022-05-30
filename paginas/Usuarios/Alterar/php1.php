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
$send2 = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);//usuário vindo alterar a partir da tela de consulta
if($send || $send2){
	$id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
	if(!$id){
		$id = $_SESSION['idUsuarioLogin'];
	}
    else if(!is_numeric($id) || $id < 1 || $id > 99999999999){
        $id = -1;
    }
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_USUARIO WHERE idUsuario=:idUsuario";
		$select = $conx->prepare($result);
		$select->bindParam(':idUsuario',$id);
		$select->execute();
        $variavelControle = 1;
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 1){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Não há usuário com esse id!";}}
        if($variavelControle){    
            $result = "SELECT Tipo, Administrador FROM $db.$TB_USUARIO WHERE idUsuario=:idUsuario";
            $select = $conx->prepare($result);
            $select->execute(['idUsuario' => $id]);
            $tipo = 0;
            foreach($select->fetchAll() as $linha_array) {
                $tipo = $linha_array['Tipo'];
				$administrador = $linha_array['Administrador'];
            }
			if($administrador && $id != $_SESSION['idUsuarioLogin']){
				$_SESSION['mensagemErro'] = 'Administrador, que não seja você mesmo, não pode ser alterado!';
			}
			else{
				if($tipo == 2){
					$result = "SELECT U1.idUsuario,".'U1.Login'.",U1.Senha,U1.Nome,U1.Administrador,U1.Cpf,U1.Tipo,A1.Matricula,A1.Curso_idCurso,C1.Nome 'NomeCurso',U1.Ativo FROM $db.$TB_USUARIO U1 inner join $db.$TB_ALUNO A1 On U1.idUsuario = A1.Usuario_idUsuario inner join $db.$TB_CURSO C1 On A1.Curso_idCurso = C1.idCurso WHERE A1.Usuario_idUsuario=:usuario";
					$select = $conx->prepare($result);
					$select->execute(['usuario' => $id]);
					$_SESSION['queryUsuario3'] = $select->fetchAll();
				}else{
					$result = "SELECT * FROM $db.$TB_USUARIO WHERE idUsuario=:idUsuario";
					$select = $conx->prepare($result);
					$select->execute(['idUsuario' => $id]);
					$_SESSION['queryUsuario3'] = $select->fetchAll();
				}	
				$_SESSION['mensagemFinalizacao'] =  'Operação finalizada com sucesso!';
			}
		}
		header("Location: ./alterar.php");	
    }
    catch(PDOException $e) {
            $msgErr = "Erro na consulta:<br />";
            $_SESSION['mensagemErro'] = $msgErr;     
			header("Location: ../index.php");			
    }
}
?>