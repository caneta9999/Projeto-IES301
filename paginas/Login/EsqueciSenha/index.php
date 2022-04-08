<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel ="stylesheet" href="../../../css/css.css"/>

    <script type="module" src="../../../js/componentes.js"></script>
    <title>Projeto IES301</title>
</head>
<body>
    <div id="navbar"></div>   
    <h1>Esqueci a senha</h1>
    <form id="formEsqueciSenha" method="POST" action="php.php">
		<label for="login">Login: </label><input id="login" name="login" type="text" placeholder="Login" maxlength="100" required /> <br/>
        <label for="cpf">CPF: </label><input id="cpf" name="cpf" type="number" placeholder="Digite o cpf" min="1" max="99999999999" required> <br/>  
        Qual a palavra formada pelas letras?<br/>
        <p id="captchaText"></p>
        <input id="captcha" type="text" placeholder="Insira a palavra"/>
        <p id="captchaMensagem"></p>
        <input name="submit" type="submit" value="Enviar" />
    </form>
    <script>
        window.onload = function () {
            var vetor = [
                { teste: "BA AA BbanAnaAA", valor: "banana" },
                { teste: "A ACAabaCAxiII", valor: "abacaxi" },
                { teste: "B ABtOMateAA", valor: "tomate" },
                { teste: "BBkiWiABBC CA", valor: "kiwi" },
                { teste: "BBmELãoAFFASFDFSDAA", valor: "melão" },
				{ teste: "MMAABA A12maÇã445AAA", valor: "maçã"}
            ];
            var indice = Math.floor(Math.random() * 100) % 6;
            document.getElementById("captchaText").innerHTML = "<b>" + vetor[indice].teste + "</b>";
            document.getElementById("formEsqueciSenha").onsubmit = function (e) {
                if (document.getElementById("captcha").value != vetor[indice].valor) {
                    document.getElementById("captchaMensagem").innerHTML = "Resposta Errada";
                    e.preventDefault();
                }
            }
        }
    </script>
    <div id="footer"></div>    
</body>
</html>