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
if($id != $_SESSION['idAlteracao']){
    $id = $_SESSION['idAlteracao'];
    unset($_SESSION['idAlteracao']);
}
$notaProfessor = filter_input(INPUT_POST,'notaProfessor',FILTER_SANITIZE_NUMBER_INT);
if(!is_numeric($notaProfessor) || $notaProfessor > 5 || $notaProfessor < 1){
    $notaProfessor = 3;
}
$notaDisciplina = filter_input(INPUT_POST, 'notaDisciplina',FILTER_SANITIZE_NUMBER_INT);
if(!is_numeric($notaDisciplina) || $notaDisciplina > 5 || $notaDisciplina < 1){
    $notaDisciplina = 3;
}
$descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
if(strlen($descricao) > 500){
    $descricao = 'descricao';
}
$aluno = $_SESSION['idUsuarioLogin'];
$result = "SELECT A1.idAluno FROM $db.$TB_ALUNO A1 inner join $db.$TB_USUARIO U1 ON U1.idUsuario = A1.Usuario_idUsuario WHERE U1.idUsuario = :idUsuario";
$select = $conx->prepare($result);
$select->bindParam(':idUsuario',$aluno);
$select->execute();
$variavelControle = 1;
$aluno = '';
foreach($select->fetchAll() as $linha_array){
    $aluno = $linha_array['idAluno'];
    break;
} 
if($send == 'Alterar'){
    try{     
        if($aluno != ''){
            $result = "UPDATE $db.$TB_CRITICA SET NotaProfessor=:notaProfessor,NotaDisciplina=:notaDisciplina,Descrição=:descricao,Data=now() WHERE idCritica = :idCritica and Aluno_idAluno = :idAluno";
            $insert = $conx->prepare($result);
            $insert->bindParam(':notaProfessor',$notaProfessor);
            $insert->bindParam(':notaDisciplina', $notaDisciplina);
            $insert->bindParam(':descricao',$descricao);
            $insert->bindParam(':idCritica',$id);
            $insert->bindParam(':idAluno',$aluno);
            $insert->execute();
            $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';}
        else{
            $_SESSION['mensagemErro'] = 'A critica não é sua então não pode ser alterada!';    
        }
        header("Location: ../index.php");}
    catch(PDOException $e) {
            $msgErr = "Erro na alteração:<br />".$e->getMessage();
            $_SESSION['mensagemErro'] = $msgErr;     
			header("Location: ../index.php");			
    }
}
else if($send == 'Excluir'){
    if($_SESSION['administradorLogin']){
        $aluno = "%%";
    }
    $result= "DELETE FROM $db.$TB_CRITICA WHERE idCritica = :idCritica and Aluno_idAluno like :idAluno";
    $delete = $conx->prepare($result);
    $delete->bindParam(':idCritica',$id);
    $delete->bindParam(':idAluno',$aluno);
    $delete->execute();
    $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';
    header("Location: ../index.php");
}
?>