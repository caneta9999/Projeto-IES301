<?php
    session_start();
    if(!isset($_SESSION['idUsuarioLogin']) || !$_SESSION['administradorLogin'])
    {
        header('location:../../Login/index.php');
    }
?>
<?php
    $send=filter_input(INPUT_POST,'submit',FILTER_SANITIZE_STRING);
    if($send=='Consultar disciplina'){
        $id = filter_input(INPUT_POST,'disciplina',FILTER_SANITIZE_NUMBER_INT);
        if(!is_numeric($id) || $id < 1 || $id > 99999999999){
            $id = "";}
        $_SESSION['estatisticasId'] = $id;
        header('location: estatisticas.php');
    }
    else if($send == 'Consultar dados gerais'){
        $_SESSION['estatisticasId'] = 0;
        header('location: estatisticas.php');
    }
?>