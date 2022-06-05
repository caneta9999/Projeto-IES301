<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel ="stylesheet" href="../../../css/css.css"/>

    <script type="module" src="../../../js/componentes-inicial.js"></script>
	<script src="../../../js/jquery-3.6.0.min.js"></script>
		<script src="../../../js/jQuery-Mask-Plugin-master/src/jquery.mask.js"></script>
    <title>Projeto IES301</title>
</head>
<body>
    <a href='/projeto-ies301/paginas/index.php'><img class="logo" src='/projeto-ies301/imgs/logo-sistema.png' alt=''></a>
    <h1>Esqueci a senha</h1>
    <form id="formEsqueciSenha" method="POST" action="php.php">
		<label for="login">Login: </label><input id="login" name="login" type="email" placeholder="Login" maxlength="100" required /> <br/>
        <label for="cpf">CPF: </label><input id="cpf" onchange="digitarCpf()" placeholder="000.000.000-00" onkeypress="$(this).mask('000.000.000-00');" name="cpf" type="text" required> <br/>  
        <h2>Digite a palavra formada pela imagem</h2>
        <p id="captchaText"></p>
        <input id="captcha" type="text" placeholder="Insira a palavra"/>
        <p id="captchaMensagem"></p>
        <input class="inputLogin" name="submit" type="submit" value="Enviar" />
    </form>
    <script>
		function digitarCpf(){
			$(document.getElementById("cpf")).val(document.getElementById("cpf").value).mask('000.000.000-00');
		}
        window.onload = function () {
            var vetor = [
                { teste: "BAAABbanAnaAA", valor: "banana" },
                { teste: "AACAabaCAxiII", valor: "abacaxi" },
                { teste: "BABtOMateAA", valor: "tomate" },
                { teste: "BBkiWiABBCCA", valor: "kiwi" },
                { teste: "BBmELãoAFFASFDFSDAA", valor: "melão" },
				{ teste: "MMAABAA12maÇã445AAA", valor: "maçã"}
            ];
            var indice = Math.floor(Math.random() * 100) % 6;
            document.getElementById("captchaText").innerHTML = "<b>" + vetor[indice].teste + "</b>";
            document.getElementById("formEsqueciSenha").onsubmit = function (e) {
                if (document.getElementById("captcha").value != vetor[indice].valor) {
                    document.getElementById("captchaMensagem").innerHTML = "<p class='mensagemErro'>Resposta errada!</p>";
                    e.preventDefault();
                }
            }
        }
    </script>
	<div id="push"></div>
    <div id="footer"></div>    
</body>
</html>