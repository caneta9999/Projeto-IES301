<?php
    session_start();
    if(!isset($_SESSION['idUsuarioLogin']) || (!$_SESSION['administradorLogin'] && $_SESSION['tipoLogin']!=1))
    {
        header('location:../../Login/index.php');
    }
?>
<?php
    require '../../../camadaDados/conectar.php';
    require '../../../camadaDados/tabelas.php';
    $send=filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
    if($send=='Consultar disciplina'){
        $id = filter_input(INPUT_POST,'disciplina',FILTER_SANITIZE_NUMBER_INT);
        if(!is_numeric($id) || $id < 1 || $id > 99999999999){
            $id = "";}
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
        $_SESSION['estatisticasId'] = $id;
        header('location: estatisticas.php');
    }
    else if($send == 'Consultar estatisticas gerais' && $_SESSION['administradorLogin']){
        $_SESSION['estatisticasId'] = 0;
        header('location: estatisticas.php');
    }
?>