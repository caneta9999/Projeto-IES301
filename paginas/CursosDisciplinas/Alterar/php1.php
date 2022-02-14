<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']) || $_SESSION['administradorLogin']!=1)
{
  header('location:../../Login/index.php');
}?>
<?php
require '../../../CamadaDados/conectar.php';
$tb = 'cursodisciplina';
$tb3 = 'Disciplina';
$tb2 = 'Curso';
$send=filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
if($send){
	$id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
    if(!is_numeric($id) || $id < 1 || $id > 99999999999){
        $id = -1;
    }
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$tb WHERE CursoDisciplinaId=:id";
		$select = $conx->prepare($result);
		$select->bindParam(':id',$id);
		$select->execute();
        $variavelControle = 1;
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 1){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Não há disciplina vinculada a um curso com esse id!";}}
        if($variavelControle){    
            $result = "SELECT CD1.CursoDisciplinaId,C1.nome 'CursoNome', D1.nome, CD1.tipo, CD1.ativa FROM $db.$tb CD1 inner join $db.$tb2 C1 ON CD1.Curso_idCurso = C1.idCurso inner join $db.$tb3 D1 ON CD1.Disciplina_idDisciplina = D1.idDisciplina WHERE CursoDisciplinaId=:id";
            $select = $conx->prepare($result);
            $select->execute(['id' => $id]);
            $_SESSION['queryCursoDisciplina2'] = $select->fetchAll();
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