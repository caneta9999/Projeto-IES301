<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']) || $_SESSION['administradorLogin']!=1)
{
  header('location:../../Login/index.php');
}?>
<?php
require '../../../CamadaDados/conectar.php';
$tb = 'professordisciplina';
$tb3 = 'Disciplina';
$tb2 = 'Professor';
$tb4 = 'Usuario';
$send=filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
if($send){
	$id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
    if(!is_numeric($id) || $id < 1 || $id > 99999999999){
        $id = -1;
    }
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$tb WHERE idProfessorDisciplina=:id";
		$select = $conx->prepare($result);
		$select->bindParam(':id',$id);
		$select->execute();
        $variavelControle = 1;
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 1){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Não há professor vinculado a uma disciplina com esse id!";}}
        if($variavelControle){    
            $result = "SELECT PD1.idProfessorDisciplina, U1.Nome, D1.Nome 'DisciplinaNome', PD1.Periodo, PD1.dataInicial, PD1.dataFinal, PD1.diaSemana from $db.$tb PD1 inner join $db.$tb2 P1 On PD1.Professor_idProfessor = P1.idProfessor inner join $db.$tb4 U1 On P1.Usuario_idUsuario = U1.idUsuario inner join $db.$tb3 D1 On D1.idDisciplina = PD1.Disciplina_idDisciplina Where PD1.idProfessorDisciplina like :id";
            $select = $conx->prepare($result);
            $select->execute(['id' => $id]);
            $_SESSION['queryProfessorDisciplina2'] = $select->fetchAll();
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