<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']) || $_SESSION['administradorLogin']!=1)
{
  header('location:../../Login/index.php');
}?>
<?php
require '../../../CamadaDados/conectar.php';
$tb = 'cursodisciplina';
$send=filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
$id = filter_input(INPUT_POST, 'id',FILTER_SANITIZE_NUMBER_INT);
if($id != $_SESSION['idAlteracao']){
    $id = $_SESSION['idAlteracao'];
    unset($_SESSION['idAlteracao']);
}
$tipo = filter_input(INPUT_POST,'tipo',FILTER_SANITIZE_STRING);
if($tipo != 'Obrigatória' && $tipo != 'Escolha' && $tipo != 'Eletiva'){
    $tipo = 'Obrigatória';
}
if($tipo=='Obrigatória'){
    $tipo = 0;
}else if($tipo == 'Eletiva'){
    $tipo = 1;
}else{
    $tipo = 2;
}
$ativa = filter_input(INPUT_POST,'ativa',FILTER_SANITIZE_STRING);
if($ativa != true && $ativa != false){
    $ativa = 1;
}
if($ativa){
    $ativa = 1;
}else{
    $ativa = 0;
}
if($send == 'Alterar'){
    try{   
        $result = "UPDATE $db.$tb SET Tipo=:tipo,Ativa=:ativa WHERE CursoDisciplinaId = :id";
        $insert = $conx->prepare($result);
        $insert->bindParam(':id',$id);
        $insert->bindParam(':tipo', $tipo);
        $insert->bindParam(':ativa',$ativa);
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
    $result= "DELETE FROM $db.$tb WHERE CursoDisciplinaId = :id";
    $delete = $conx->prepare($result);
    $delete->bindParam(':id', $id);
    $delete->execute();
    $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';
    header("Location: ../index.php");
}
?>