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
	$nome = filter_input(INPUT_POST,'nome',FILTER_SANITIZE_STRING);
    $sigla = filter_input(INPUT_POST,'sigla',FILTER_SANITIZE_STRING);
    if(strlen($nome) > 50){
        $nome = "";
    }
    $nome = "%".$nome."%";
    if(strlen($sigla)>6){
		$sigla = "";
    }
	$sigla = "%".$sigla."%";
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_DISCIPLINA WHERE Nome like :nome";
		$select = $conx->prepare($result);
		$select->bindParam(':nome',$nome);
		$select->execute();
        $variavelControle = 1;//nome
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] == 0 || $nome == "%%"){
                $variavelControle = 0;}}
        if(!$variavelControle){
            $result = "SELECT count(*) 'quantidade' FROM $db.$TB_DISCIPLINA WHERE Sigla like :sigla";
            $select = $conx->prepare($result);
            $select->bindParam(':sigla',$sigla);
            $select->execute();
            $variavelControle = 2;//sigla           
            foreach($select->fetchAll() as $linha_array){
                if($linha_array['quantidade'] == 0 || $sigla == "%%"){
                    $variavelControle = 0;}}           
        }
        if($variavelControle){
            if($variavelControle == 1){//nome
                $result = "SELECT * FROM $db.$TB_DISCIPLINA WHERE Nome like :nome";
                $select = $conx->prepare($result);
                $select->execute(['nome' => $nome]);
                $_SESSION['queryDisciplina1'] = $select->fetchAll();
            }
            else{//sigla
                $result = "SELECT * FROM $db.$TB_DISCIPLINA WHERE Sigla like :sigla";
                $select = $conx->prepare($result);
                $select->execute(['sigla' => $sigla]);
                $_SESSION['queryDisciplina1'] = $select->fetchAll();
            }  
            $_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';}
        else{
            if($nome=="%%" && $sigla=="%%"){
                $result = "SELECT * FROM $db.$TB_DISCIPLINA";
                $select = $conx->prepare($result);
                $select->execute();
                $_SESSION['queryDisciplina1'] = $select->fetchAll();
				$_SESSION['mensagemFinalizacao'] = 'Operação finalizada com sucesso!';                
            }
            else{
                $_SESSION['mensagemErro'] = 'A consulta não retornou resultados';
            }
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