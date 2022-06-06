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
if($send){
	$professor = filter_input(INPUT_POST,'professor',FILTER_SANITIZE_NUMBER_INT);
    if(!is_numeric($professor) || $professor > 99999999999 || $professor < 1){
        $professor = -1;
    }
    $disciplina = filter_input(INPUT_POST,'disciplina',FILTER_SANITIZE_NUMBER_INT);
    if(!is_numeric($disciplina) || $disciplina > 99999999999 || $disciplina < 1){
        $disciplina = -1;
    }
    $periodo = filter_input(INPUT_POST,'periodo',FILTER_SANITIZE_NUMBER_INT);
    if($periodo != 0 && $periodo != 1 && $periodo !=2){
        $periodo = 0;
    }
    $regexData = '/[0-9]{4}-[0-9]{2}-[0-9]{2}/';
    $dataInicial = filter_input(INPUT_POST,'dataInicial',FILTER_SANITIZE_STRING);
    if(!preg_match($regexData, $dataInicial) || strlen($sigla)>10){
        $dataInicial = '2022-03-08';
    }
    $dataFinal = filter_input(INPUT_POST,'dataFinal',FILTER_SANITIZE_STRING);
    if($dataFinal != null && (!preg_match($regexData, $dataInicial) || strlen($data)>10)){
        $dataFinal = '2100-01-01';
    }
	if($dataFinal == null){
		$dataFinal = '2100-01-01';
	}
    $diaSemana = filter_input(INPUT_POST,'diaSemana', FILTER_SANITIZE_NUMBER_INT);
    if(!is_numeric($diaSemana) || $diaSemana < 2 || $diaSemana > 7 ){
        $diaSemana = 2;
    }
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_USUARIO WHERE idUsuario = :professor";
		$select = $conx->prepare($result);
		$select->bindParam(':professor',$professor);
		$select->execute();
        $variavelControle = 1;
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 1){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Esse professor não está cadastrado!";}}

        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_DISCIPLINA WHERE idDisciplina = :disciplina";
		$select = $conx->prepare($result);
		$select->bindParam(':disciplina',$disciplina);
		$select->execute();
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 1){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Essa disciplina não está cadastrada!";}}

        if($variavelControle){
            $result = "SELECT idProfessor FROM $db.$TB_PROFESSOR WHERE Usuario_idUsuario = :professor";
            $select = $conx->prepare($result);
            $select->bindParam(':professor',$professor);
            $select->execute();
            foreach($select->fetchAll() as $linha_array){
                $professor = $linha_array['idProfessor'];}          
            $result = "INSERT INTO $db.$TB_PROFESSORDISCIPLINA (Professor_idProfessor, Disciplina_idDisciplina, Periodo, DataInicial,DataFinal, DiaSemana) VALUES (:idProfessor, :idDisciplina, :periodo, :dataInicial, :dataFinal, :diaSemana)";
            $insert = $conx->prepare($result);
            $insert->bindParam(':idProfessor',$professor);
            $insert->bindParam(':idDisciplina',$disciplina);
            $insert->bindParam(':periodo',$periodo);
            $insert->bindParam(':dataInicial',$dataInicial);
            $insert->bindParam(':dataFinal',$dataFinal);
            $insert->bindParam(':diaSemana',$diaSemana);
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