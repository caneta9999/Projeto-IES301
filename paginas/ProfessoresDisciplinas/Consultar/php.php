<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']))
{
  header('location:../../Login/index.php');
}?>
<?php
require '../../../CamadaDados/conectar.php';
$tb = 'professordisciplina';
$tb2 = 'Professor';
$tb4 = 'Usuario';
$tb3 = 'Disciplina';
$send=filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
if($send){
	$nome = filter_input(INPUT_POST,'nome',FILTER_SANITIZE_STRING);
    if(strlen($nome) > 100){
        $nome = "";
    }
    $nome = "%".$nome."%";
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$tb3 WHERE Nome like :nome";
		$select = $conx->prepare($result);
		$select->bindParam(':nome',$nome);
		$select->execute();
        $variavelControle = 1;//nome
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] != 1){
                $variavelControle = 0;}}
        if($variavelControle){
            $result = "SELECT PD1.idProfessorDisciplina, U1.Nome, D1.Nome 'DisciplinaNome', PD1.Periodo, PD1.dataInicial, PD1.dataFinal, PD1.diaSemana from $db.$tb PD1 inner join $db.$tb2 P1 On PD1.Professor_idProfessor = P1.idProfessor inner join $db.$tb4 U1 On P1.Usuario_idUsuario = U1.idUsuario inner join $db.$tb3 D1 On D1.idDisciplina = PD1.Disciplina_idDisciplina Where D1.Nome like :nome";
            $select = $conx->prepare($result);
            $select->bindParam(':nome',$nome);
            $select->execute();   
            $_SESSION['queryProfessorDisciplina1'] = $select->fetchAll();
            $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';        
        }else{
            $_SESSION['mensagemErro'] = 'Não foi possível achar a disciplina e seus professores!';
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