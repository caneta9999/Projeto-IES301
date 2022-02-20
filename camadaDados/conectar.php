<?php
if(!isset($_SESSION)) 
{ 
        session_start(); 
} 
require ("preparar_conexao.php");
try {

     $conx = new PDO("mysql:host=$host;dbname=$db", $user,$pass); 
     $conx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
     $msgErr =  "Falha de conexão<br />";
     $_SESSION['msgErr'] = $msgErr; 
} 
?>
