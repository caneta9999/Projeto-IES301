<?php
session_start();
unset($_SESSION['idUsuarioLogin']);
unset($_SESSION['tipoLogin']);
unset($_SESSION['administradorLogin']);
unset($_SESSION['idCursoLogin']);
header("Location: ./index.php");
?>