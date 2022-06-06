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
    $id = filter_input(INPUT_POST,'disciplina',FILTER_SANITIZE_NUMBER_INT);
    if(!is_numeric($id) || $id < 1 || $id > 99999999999){
        $id = "";
    }
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_DISCIPLINA WHERE idDisciplina=:Id";
		$select = $conx->prepare($result);
		$select->bindParam(':Id',$id);
		$select->execute();
        $variavelControle = 1;
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 1){
                $variavelControle = 0;}}
        if($variavelControle){
			$result = "SELECT PD1.idProfessorDisciplina, U1.Nome, D1.Nome 'DisciplinaNome', PD1.Periodo, PD1.dataInicial, PD1.dataFinal, PD1.diaSemana from $db.$TB_PROFESSORDISCIPLINA PD1 inner join $db.$TB_PROFESSOR P1 On PD1.Professor_idProfessor = P1.idProfessor inner join $db.$TB_USUARIO U1 On P1.Usuario_idUsuario = U1.idUsuario inner join $db.$TB_DISCIPLINA D1 On D1.idDisciplina = PD1.Disciplina_idDisciplina Where D1.idDisciplina = :Id";
			$select = $conx->prepare($result);
			$select->bindParam(':Id',$id);
			$select->execute();  
			$select = $select->fetchAll();
			if(count($select) > 0){
				$_SESSION['queryProfessorDisciplina1'] = $select;
				$_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!'; 			
			}
			else{
				$_SESSION['mensagemErro'] = 'Não há professores associados à disciplina!';
			}
        }else{
            $_SESSION['mensagemErro'] = 'Não foi possível achar a disciplina e seus professores!';
        }
        header("Location: ./consultar.php");	
    }
    catch(PDOException $e) {
            $msgErr = "Erro na consulta: <br />";
            $_SESSION['mensagemErro'] = $msgErr;     
			header("Location: ../index.php");			
    }
}
?>