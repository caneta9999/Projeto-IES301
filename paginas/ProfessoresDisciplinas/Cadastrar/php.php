<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']) || $_SESSION['administradorLogin']!=1)
{
  header('location:../../Login/index.php');
}?>
<?php
require '../../../CamadaDados/conectar.php';
$tb = 'professordisciplina';
$tb2 = 'Usuario';
$tb3 = 'Disciplina';
$tb4 = 'Professor';
$send=filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
if($send){
	$professor = filter_input(INPUT_POST,'professor',FILTER_SANITIZE_NUMBER_INT);
    if(!is_numeric($professor) || $professor > 99999999999 || $professor < 1){
        $professor = -1;
    }
    $disciplina = filter_input(INPUT_POST,'disciplina',FILTER_SANITIZE_STRING);
    if(strlen($disciplina)>50){
        $disciplina = "";
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
    if($dataFinal != null && (!preg_match($regexData, $dataInicial) || strlen($sigla)>10)){
        $dataFinal = '2022-03-08';
    }
    $diaSemana = filter_input(INPUT_POST,'diaSemana', FILTER_SANITIZE_NUMBER_INT);
    if(!is_numeric($diaSemana) || $diaSemana < 2 || $diaSemana > 6 ){
        $diaSemana = 2;
    }
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$tb2 WHERE idUsuario = :professor";
		$select = $conx->prepare($result);
		$select->bindParam(':professor',$professor);
		$select->execute();
        $variavelControle = 1;
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 1){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Esse professor não está cadastrado!";}}

        $result = "SELECT count(*) 'quantidade' FROM $db.$tb3 WHERE nome = :nome";
		$select = $conx->prepare($result);
		$select->bindParam(':nome',$disciplina);
		$select->execute();
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 1){
                $variavelControle = 0;
				$_SESSION['mensagemErro'] = "Não há uma disciplina com esse nome cadastrado!";}}

        if($variavelControle){
            $result = "SELECT idDisciplina FROM $db.$tb3 WHERE nome = :nome";
            $select = $conx->prepare($result);
            $select->bindParam(':nome',$disciplina);
            $select->execute();
            $idDisciplina = 0;
            foreach($select->fetchAll() as $linha_array){
                $idDisciplina = $linha_array['idDisciplina'];}  
            $result = "SELECT idProfessor FROM $db.$tb4 WHERE Usuario_idUsuario = :professor";
            $select = $conx->prepare($result);
            $select->bindParam(':professor',$professor);
            $select->execute();
            foreach($select->fetchAll() as $linha_array){
                $professor = $linha_array['idProfessor'];}          
            $result = "INSERT INTO $db.$tb (Professor_idProfessor, Disciplina_idDisciplina, Periodo, DataInicial,DataFinal, DiaSemana) VALUES (:idProfessor, :idDisciplina, :periodo, :dataInicial, :dataFinal, :diaSemana)";
            $insert = $conx->prepare($result);
            $insert->bindParam(':idProfessor',$professor);
            $insert->bindParam(':idDisciplina',$idDisciplina);
            $insert->bindParam(':periodo',$periodo);
            $insert->bindParam(':dataInicial',$dataInicial);
            $insert->bindParam(':dataFinal',$dataFinal);
            $insert->bindParam(':diaSemana',$diaSemana);
            $insert->execute();
            $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';}
        header("Location: ../index.php");	
        }
    catch(PDOException $e) {
            $msgErr = "Erro na inclusão:<br />".$e->getMessage();
            $_SESSION['mensagemErro'] = $msgErr;     
			header("Location: ../index.php");			
    }
}
?>