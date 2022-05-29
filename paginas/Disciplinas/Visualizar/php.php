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
$send2 = filter_input(INPUT_POST,'codigo',FILTER_SANITIZE_NUMBER_INT);//usuário vindo visualizar a partir da tela inicial
if($send || $send2){
	$id = '';
	if($send2){
        $result = "SELECT idDisciplina FROM $db.$TB_DISCIPLINA WHERE Código=:codigo";
		$select = $conx->prepare($result);
		$select->bindParam(':codigo',$send2);
		$select->execute();	
		foreach($select->fetchAll() as $linha_array){
			$id = $linha_array['idDisciplina'];
			break;
		}	
	}
	else{
		$id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
		if(!is_numeric($id) || $id < 1 || $id > 99999999999){
			$id = -1;
		}
	}
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_DISCIPLINA WHERE idDisciplina=:idDisciplina";
		$select = $conx->prepare($result);
		$select->bindParam(':idDisciplina',$id);
		$select->execute();
        $variavelControle = 1;
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 1){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Não há disciplina com esse id!";}}
        if($variavelControle){    
            $result = "SELECT D1.Nome,D1.idDisciplina,D1.Sigla,D1.Código,D1.Tipo,D1.Ativa, D1.Descrição, C1.Nome 'NomeCurso' FROM $db.$TB_DISCIPLINA D1 inner join $db.$TB_CURSO C1 on D1.Curso_idCurso = C1.idCurso WHERE idDisciplina=:idDisciplina";
            $select = $conx->prepare($result);
            $select->execute(['idDisciplina' => $id]);
            $_SESSION['queryVisualizarDisciplina1'] = $select->fetchAll();
            $result = "SELECT U1.Nome, PD1.Periodo, PD1.DiaSemana FROM $db.$TB_PROFESSORDISCIPLINA PD1 inner join $db.$TB_PROFESSOR P1 on PD1.Professor_idProfessor = P1.idProfessor inner join $db.$TB_USUARIO U1 on P1.Usuario_idUsuario = U1.idUsuario inner join $db.$TB_DISCIPLINA D1 on PD1.Disciplina_idDisciplina = D1.idDisciplina where idDisciplina=:idDisciplina order by PD1.Periodo";
            $select = $conx->prepare($result);
            $select->execute(['idDisciplina' => $id]);
            $_SESSION['queryVisualizarDisciplina2'] = $select->fetchAll();
            $_SESSION['mensagemFinalizacao'] =  'Operação finalizada com sucesso!';}
        header("Location: ./visualizar.php");	
        }
    catch(PDOException $e) {
            $msgErr = "Erro na consulta:<br />";
            $_SESSION['mensagemErro'] = $msgErr;     
			header("Location: ../index.php");			
    }
}
?>