<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel ="stylesheet" href="../../css/css.css"/>

    <script type="module" src="../../js/componentes.js"></script>

    <title>Projeto IES301</title>
</head>
<body>
    <div id="navbar"></div>
	<?php
		session_start();
        if(isset($_SESSION['mensagemErro'])){
            echo "<p class='mensagemErro'>".$_SESSION['mensagemErro']."</p>";
            unset($_SESSION['mensagemErro']);
        }
	?>    
    <h1>Login</h1>
    <form method="POST" action="php.php">
		<label for="login">Login: </label><input id="login" name="login" type="text" placeholder="Login" maxlength="100" required /> <br/>
		<label for="senha">Senha: </label><input id="senha" name="senha" type="password" placeholder="Senha" minlength="8" maxlength="50" required /> <br/>         
    <input name="submit" type="submit" value="Entrar" />
    </form>
    <div id="footer"></div>    
</body>
</html>