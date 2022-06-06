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
$id = filter_input(INPUT_POST, 'id',FILTER_SANITIZE_NUMBER_INT);
if($id != $_SESSION['idAlteracao5']){
    $id = $_SESSION['idAlteracao5'];
    unset($_SESSION['idAlteracao5']);
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
if($send == 'Cancelar'){
	$_SESSION['mensagemFinalizacao'] = 'Operação cancelada com sucesso!';	
	header("Location: ../index.php");
}
else if($send == 'Alterar'){
    try{   
        $result = "UPDATE $db.$TB_PROFESSORDISCIPLINA SET Periodo=:periodo,DataInicial=:dataInicial, DataFinal=:dataFinal, DiaSemana=:diaSemana WHERE idProfessorDisciplina = :id";
        $insert = $conx->prepare($result);
        $insert->bindParam(':id',$id);
        $insert->bindParam(':periodo',$periodo);
        $insert->bindParam(':dataInicial', $dataInicial);
        $insert->bindParam(':dataFinal',$dataFinal);
        $insert->bindParam(':diaSemana',$diaSemana);
        $insert->execute();
        $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';
        header("Location: ../index.php");}
    catch(PDOException $e) {
            $msgErr = "Erro na alteração:<br />";
            $_SESSION['mensagemErro'] = $msgErr;     
			header("Location: ../index.php");			
    }
}
else if($send == 'Excluir'){
    $result= "DELETE FROM $db.$TB_CRITICA WHERE ProfessorDisciplina_idProfessorDisciplina = :id";
    $delete = $conx->prepare($result);
    $delete->bindParam(':id', $id);
    $delete->execute();
    $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';
    $result= "DELETE FROM $db.$TB_PROFESSORDISCIPLINA WHERE idProfessorDisciplina = :id";
    $delete = $conx->prepare($result);
    $delete->bindParam(':id', $id);
    $delete->execute();
    $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';
    header("Location: ../index.php");
}
?>