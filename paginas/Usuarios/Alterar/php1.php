<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']) || $_SESSION['administradorLogin']!=1)
{
  header('location:../../Login/index.php');
}?>
<?php
require '../../../CamadaDados/conectar.php';
$tb = 'Usuario';
$tb2 = 'Aluno';
$tb3 = 'Curso';
$send=filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
if($send){
	$id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
    if(!is_numeric($id) || $id < 1 || $id > 99999999999){
        $id = -1;
    }
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$tb WHERE idUsuario=:idUsuario";
		$select = $conx->prepare($result);
		$select->bindParam(':idUsuario',$id);
		$select->execute();
        $variavelControle = 1;
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 1){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Não há usuário com esse id!";}}
        if($variavelControle){    
            $result = "SELECT * FROM $db.$tb WHERE idUsuario=:idUsuario";
            $select = $conx->prepare($result);
            $select->execute(['idUsuario' => $id]);
            $tipo = 0;
            foreach($select->fetchAll() as $linha_array) {
                $tipo = $linha_array['Tipo'];
            }
            if($tipo == 2){
                $result = "SELECT U1.idUsuario,".'U1.Login'.",U1.Senha,U1.Nome,U1.Administrador,U1.Cpf,U1.Tipo,A1.Matricula,A1.Curso_idCurso,C1.Nome 'NomeCurso' FROM $db.$tb U1 inner join $db.$tb2 A1 On U1.idUsuario = A1.Usuario_idUsuario inner join $db.$tb3 C1 On A1.Curso_idCurso = C1.idCurso WHERE A1.Usuario_idUsuario=:usuario";
                $select = $conx->prepare($result);
                $select->execute(['usuario' => $id]);
                $_SESSION['queryUsuario3'] = $select->fetchAll();
            }else{
                $result = "SELECT * FROM $db.$tb WHERE idUsuario=:idUsuario";
                $select = $conx->prepare($result);
                $select->execute(['idUsuario' => $id]);
                $_SESSION['queryUsuario3'] = $select->fetchAll();
            }
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