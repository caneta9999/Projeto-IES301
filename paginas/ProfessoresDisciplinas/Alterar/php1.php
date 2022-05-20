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
$send2 = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);//usuário vindo alterar a partir da tela de consulta
if($send || $send2){
	$id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
    if(!is_numeric($id) || $id < 1 || $id > 99999999999){
        $id = -1;
    }
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_PROFESSORDISCIPLINA WHERE idProfessorDisciplina=:id";
		$select = $conx->prepare($result);
		$select->bindParam(':id',$id);
		$select->execute();
        $variavelControle = 1;
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 1){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Não há professor vinculado a uma disciplina com esse id!";}}
        if($variavelControle){    
            $result = "SELECT PD1.idProfessorDisciplina, U1.Nome, D1.Nome 'DisciplinaNome', PD1.Periodo, PD1.dataInicial, PD1.dataFinal, PD1.diaSemana from $db.$TB_PROFESSORDISCIPLINA PD1 inner join $db.$TB_PROFESSOR P1 On PD1.Professor_idProfessor = P1.idProfessor inner join $db.$TB_USUARIO U1 On P1.Usuario_idUsuario = U1.idUsuario inner join $db.$TB_DISCIPLINA D1 On D1.idDisciplina = PD1.Disciplina_idDisciplina Where PD1.idProfessorDisciplina like :id";
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