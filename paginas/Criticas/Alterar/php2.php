<?php
session_start();
$variavelControle = 1;
if(!isset($_SESSION['idUsuarioLogin']))
{
  header('location:../../Login/index.php');
}
require '../../../camadaDados/conectar.php';
require '../../../camadaDados/tabelas.php';
//função para validar elogio
function elogioValidar($elogio){
    if(in_array($elogio, ['Nenhum','Explicação','Material','Organização','Pontualidade','Prestativo','Carismático'])){
        return $elogio;
    }else{
        return 'Nenhum';
    }
}
//função para validar critica
function criticaValidar($critica){
    if(in_array($critica,['Nenhum','Explicação','Material','Organização','Pontualidade','Comunicação','Método de avaliação'])){
        return $critica;
    }else{
        return 'Nenhum';
    }
}
//receber id da critica
$send=filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
$id = filter_input(INPUT_POST, 'id',FILTER_SANITIZE_NUMBER_INT);
if($id != $_SESSION['idAlteracao6']){
    $id = $_SESSION['idAlteracao6'];
    unset($_SESSION['idAlteracao6']);
}
//receber outros parâmetros
$notaAluno = filter_input(INPUT_POST,'notaAluno',FILTER_SANITIZE_NUMBER_INT);
if(!is_numeric($notaAluno) || $notaAluno > 5 || $notaAluno < 1){
    $notaAluno = 3;
}
$notaEvolucao = filter_input(INPUT_POST,'notaEvolucao',FILTER_SANITIZE_NUMBER_INT);
if(!is_numeric($notaEvolucao) || $notaEvolucao > 5 || $notaEvolucao < 1){
    $notaEvolucao = 3;
}
$notaDisciplina = filter_input(INPUT_POST, 'notaDisciplina',FILTER_SANITIZE_NUMBER_INT);
if(!is_numeric($notaDisciplina) || $notaDisciplina > 5 || $notaDisciplina < 1){
    $notaDisciplina = 3;
}
$descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
if(strlen($descricao) > 500){
    $descricao = 'descricao';
}
$ano = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_NUMBER_INT);
if(!is_numeric($ano) || $ano > 2100 || $ano < 1973){
    $ano = 1973;
}
$semestre = filter_input(INPUT_POST, 'semestre', FILTER_SANITIZE_NUMBER_INT);
if(!is_numeric($semestre) || $semestre > 2 || $semestre < 1){
    $semestre = 1;
}
$anoSemestre = $ano."".$semestre; 
$elogio1 = elogioValidar(filter_input(INPUT_POST, 'elogio1', FILTER_SANITIZE_STRING));
$elogio2 = elogioValidar(filter_input(INPUT_POST, 'elogio2', FILTER_SANITIZE_STRING));
$elogio3 = elogioValidar(filter_input(INPUT_POST, 'elogio3', FILTER_SANITIZE_STRING));
$critica1 = criticaValidar(filter_input(INPUT_POST, 'critica1', FILTER_SANITIZE_STRING));
$critica2 = criticaValidar(filter_input(INPUT_POST, 'critica2', FILTER_SANITIZE_STRING));
$critica3 = criticaValidar(filter_input(INPUT_POST, 'critica3', FILTER_SANITIZE_STRING));
//verificar se não há elogios iguais
if($elogio1 != 'Nenhum' && $elogio1 == $elogio2){
    $elogio2 = 'Nenhum';
}
if($elogio1 != 'Nenhum' && $elogio1 == $elogio3){
    $elogio3 = 'Nenhum';
}
if($elogio2 != 'Nenhum' && $elogio2 == $elogio3){
    $elogio3 = 'Nenhum';
} 
//verificar se não há criticas iguais
if($critica1 != 'Nenhum' && $critica1 == $critica2){
    $critica2 = 'Nenhum';
}
if($critica1 != 'Nenhum' && $critica1 == $critica3){
    $critica3 = 'Nenhum';
}
if($critica2 != 'Nenhum' && $critica2 == $critica3){
    $critica3 = 'Nenhum';
}
//montar a string de elogios
$elogios = $elogio1."-".$elogio2."-".$elogio3;
//montar a string de críticas
$criticas = $critica1."-".$critica2."-".$critica3;   
//pegar id do aluno
$aluno = $_SESSION['idUsuarioLogin'];
$result = "SELECT A1.idAluno FROM $db.$TB_ALUNO A1 inner join $db.$TB_USUARIO U1 ON U1.idUsuario = A1.Usuario_idUsuario WHERE U1.idUsuario = :idUsuario";
$select = $conx->prepare($result);
$select->bindParam(':idUsuario',$aluno);
$select->execute();
$aluno = '';
foreach($select->fetchAll() as $linha_array){
    $aluno = $linha_array['idAluno'];
    break;
}
//pegar id da disciplina cadastrada
$disciplina = 0;
$result = "SELECT ProfessorDisciplina_idProfessorDisciplina FROM $db.$TB_CRITICA where idCritica = :idCritica";
$select = $conx->prepare($result);
$select->bindParam(':idCritica',$id);
$select->execute();
foreach($select->fetchAll() as $linha_array){
        $disciplina = $linha_array['ProfessorDisciplina_idProfessorDisciplina'];
        break;
}
//controle para ver se a data é válida para a disciplina
$anoQuery = $ano;
if($semestre == 1){
    $anoQuery = $anoQuery."-07-15";
}
else{
    $anoQuery = $anoQuery."-12-31";
}
$result = "SELECT Count(*) 'Quantidade' FROM $db.$TB_PROFESSORDISCIPLINA where idProfessorDisciplina = :idDisciplina and DataInicial <= :data1 and (dataFinal >= :data2 or dataFinal='0000-00-00')";
$select = $conx->prepare($result);
$select->bindParam(':idDisciplina',$disciplina);
$select->bindParam(':data1',$anoQuery);
$select->bindParam(':data2',$anoQuery);
$select->execute();
foreach($select->fetchAll() as $linha_array){
        if($linha_array['Quantidade'] != 1){
            $variavelControle = 0;
            $_SESSION['mensagemErro'] = 'Data inválida para a disciplina!';}
        break;
}
if($_SESSION['tipoLogin'] != 2 and $send == 'Alterar'){
    $_SESSION['mensagemErro'] = 'Precisa ser aluno para alterar a crítica!';
    $variavelControle = 0;
}
if($send == 'Cancelar'){
	$_SESSION['mensagemFinalizacao'] = 'Operação cancelada com sucesso!';	
	header("Location: ../index.php");
}
else if($send == 'Alterar'){
    try{     
        if($aluno != '' && $variavelControle){
            $result = "UPDATE $db.$TB_CRITICA SET NotaAluno=:notaAluno,NotaEvolucao=:notaEvolucao,NotaDisciplina=:notaDisciplina,Descrição=:descricao,Data=now(),AnoSemestre=:anoSemestre,Elogios=:elogios,Criticas=:criticas WHERE idCritica = :idCritica and Aluno_idAluno = :idAluno";
            $insert = $conx->prepare($result);
            $insert->bindParam(':notaAluno',$notaAluno);
            $insert->bindParam(':notaEvolucao',$notaEvolucao);
            $insert->bindParam(':notaDisciplina', $notaDisciplina);
            $insert->bindParam(':descricao',$descricao);
            $insert->bindParam(':idCritica',$id);
            $insert->bindParam(':idAluno',$aluno);
            $insert->bindParam(':anoSemestre',$anoSemestre);
            $insert->bindParam(':elogios',$elogios);
            $insert->bindParam(':criticas',$criticas);
            $insert->execute();
            $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';}
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