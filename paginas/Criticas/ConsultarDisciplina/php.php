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
	//verificar se a disciplina é do professor mesmo (caso seja o professor que esteja solicitando)
    if($_SESSION['idProfessorLogin']){
            $result = "SELECT Count(*) 'Quantidade' from $db.$TB_PROFESSORDISCIPLINA Where Professor_idProfessor = :idProfessor and idProfessorDisciplina = :idDisciplina";
            $select= $conx->prepare($result);
            $select->bindParam(':idProfessor',$_SESSION['idProfessorLogin']);
            $select->bindParam(':idDisciplina',$id);
            $select->execute();
            foreach($select->fetchAll() as $linha_array){
                if($linha_array['Quantidade']!=1){
                    $id = -1;}
                break;}
            unset($_SESSION['idProfessorLogin']);
    }
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_CRITICA WHERE ProfessorDisciplina_idProfessorDisciplina =:id";
		$select = $conx->prepare($result);
		$select->bindParam(':id',$id);
		$select->execute();
        $variavelControle = 1;
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] < 1){
                $variavelControle = 0;}}
        if($variavelControle){
            $result = "SELECT C1.idCritica,A1.Matricula,C1.NotaDisciplina,C1.NotaEvolucao, C1.NotaAluno,C1.Descrição,C1.Data,C1.AnoSemestre, C1.Elogios, C1.Criticas FROM $db.$TB_CRITICA C1 inner join $db.$TB_ALUNO A1 ON C1.Aluno_idAluno = A1.idAluno WHERE C1.ProfessorDisciplina_idProfessorDisciplina = :id";
            $select = $conx->prepare($result);
            $select->bindParam(':id',$id);
            $select->execute();
            $_SESSION['queryCritica2'] = $select->fetchAll();
            $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';}
        else{
            $_SESSION['mensagemErro'] = 'A consulta não retornou resultados';
        }
        header("Location: ./consultar.php");	
    }
    catch(PDOException $e) {
            $msgErr = "Erro na consulta:<br />";
            $_SESSION['mensagemErro'] = $msgErr;     
			header("Location: ../index.php");			
    }
}
?>