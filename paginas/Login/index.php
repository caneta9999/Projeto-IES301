<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel ="stylesheet" href="../../css/css.css"/>

    <script type="module" src="../../js/componentes-inicial.js"></script>

    <title>Projeto IES301</title>
</head>
<body>
    <a href='/projeto-ies301/paginas/index.php'><img class="logo" src='/projeto-ies301/imgs/logo-sistema.png' alt=''></a>
	<?php
		session_start();
        if(isset($_SESSION['mensagemErro'])){
            echo "<p class='mensagemErro'>".$_SESSION['mensagemErro']."</p>";
            unset($_SESSION['mensagemErro']);
        }
		if(isset($_SESSION['mensagemFinalizacao'])){
			echo "<p class='mensagemFinalizacao'>".$_SESSION['mensagemFinalizacao']."</p>";
			unset($_SESSION['mensagemFinalizacao']);
		}
	?>    
    <h1>Login</h1>
    <form method="POST" action="php.php">
		<label for="login">Login: </label><input id="login" name="login" type="email" placeholder="Login" maxlength="100" required /> <br/>
		<label for="senha">Senha: </label><input id="senha" name="senha" type="password" placeholder="Senha" minlength="8" maxlength="50" required /> <br/>         
    <input class="inputLogin" name="submit" type="submit" value="Entrar" />
    </form>
    <a id="aEsqueciSenha" href="./EsqueciSenha/index.php">Esqueceu a senha?</a>
    <div id="footer"></div>    
</body>
</html>