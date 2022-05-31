<!DOCTYPE html>
<html>
<head>
	<title>Validar CPF</title>
</head>
<body>
	<div align="center">
		<form action="#" method="POST">
			CPF:<br>
			<input type="text" name="cpf" placeholder="000.000.000-00">
			<br>
			<input type="submit" value="Validar">
		</form>
		<?php
		if (isset($_POST['cpf']))
		{
			require_once('class.CPF.php');

			$cpf = new CPF();
			$validate = $cpf->validate($_POST['cpf']);
			$message  = $validate == true ? 'CPF Valido!' : 'CPF Invalido!';

			echo "<br> <strong>{$message}</script>";
		}
		?>
		<br><br>		
	</div>
</body>
</html>
