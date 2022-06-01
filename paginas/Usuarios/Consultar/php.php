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
	$nome = filter_input(INPUT_POST,'nome',FILTER_SANITIZE_STRING);
    if(strlen($nome) > 100){
        $nome = "";
    }
    $nome = "%".$nome."%";
    $matricula = filter_input(INPUT_POST,'matricula',FILTER_SANITIZE_NUMBER_INT);
    if(!is_numeric($matricula) || $matricula < 1 || $matricula > 99999999){
        $matricula = -1;
    }
    try{
        $result = "SELECT count(*) 'quantidade' FROM $db.$TB_USUARIO WHERE Nome like :nome";
		$select = $conx->prepare($result);
		$select->bindParam(':nome',$nome);
		$select->execute();
        $variavelControle = 1;//nome
		foreach($select->fetchAll() as $linha_array){
			if($linha_array['quantidade'] == 0 || $nome == "%%"){
                $variavelControle = 0;}}
        if(!$variavelControle){
            $result = "SELECT count(*) 'quantidade' FROM $db.$TB_ALUNO WHERE Matricula like :matricula";
            $select = $conx->prepare($result);
            $select->bindParam(':matricula',$matricula);
            $select->execute();
            $variavelControle = 2;//matricula          
            foreach($select->fetchAll() as $linha_array){
                if($linha_array['quantidade'] == 0){
                    $variavelControle = 0;}}           
        }
        if($variavelControle){
            if($variavelControle == 1){//nome
                $result = "SELECT idUsuario,".'Login'.",Nome,Administrador,Cpf,Tipo,Ativo FROM $db.$TB_USUARIO WHERE Nome like :nome";
                $select = $conx->prepare($result);
                $select->execute(['nome' => $nome]);
                $_SESSION['queryUsuario1'] = $select->fetchAll();
                $result = "SELECT U1.idUsuario,A1.idAluno,".'U1.Login'.",U1.Nome,U1.Administrador,U1.Cpf,U1.Tipo,A1.Matricula,C1.Nome 'CursoNome',U1.Ativo FROM $db.$TB_USUARIO U1 inner join $db.$TB_ALUNO A1 On U1.idUsuario = A1.Usuario_idUsuario inner join $db.$TB_CURSO C1 On A1.Curso_idCurso = C1.idCurso WHERE U1.Nome like :nome";
                $select = $conx->prepare($result);
                $select->execute(['nome' => $nome]);
                $_SESSION['queryUsuario2'] = $select->fetchAll();
            }
            else{//matricula
                $result = "SELECT A1.idAluno,U1.idUsuario,".'U1.Login'.",U1.Nome,U1.Cpf,U1.Tipo,A1.Matricula,C1.Nome 'CursoNome',U1.Ativo  FROM $db.$TB_USUARIO U1 inner join $db.$TB_ALUNO A1 On U1.idUsuario = A1.Usuario_idUsuario inner join $db.$TB_CURSO C1 On A1.Curso_idCurso = C1.idCurso WHERE A1.Matricula=:Matricula";
                $select = $conx->prepare($result);
                $select->execute(['Matricula' => $matricula]);
                $_SESSION['queryUsuario2'] = $select->fetchAll();
            }  
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