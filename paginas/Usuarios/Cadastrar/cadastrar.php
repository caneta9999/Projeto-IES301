<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']) || $_SESSION['administradorLogin']!=1)
{
  header('location:../../Login/index.php');
}?>
<?php
    require '../../../CamadaDados/conectar.php';
    $tb = 'Curso';
    $result = "SELECT Nome FROM $db.$tb";
    $select = $conx->prepare($result);
    $select->execute();
    $_SESSION['queryPessoaCursos1'] = $select->fetchAll();
?>
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
    <h1>Cadastrar</h1>
    <button class="button btnVoltar"><a href="../index.php">Voltar</a></button><br/>
    <form action="php.php" method="POST">
        <label for="login">Login: </label><input id="login" name="login" type="email" placeholder="Digite o email" minlength="1" maxlength="100" required> <br/>
        <label for="senha">Senha: </label><input id="senha" name="senha" type="password" placeholder="Digite a senha" minlength="8" maxlength="50" required> <br/>
        <label for="nome">Nome: </label><input id="nome" name="nome" type="text" placeholder="Digite o nome" maxlength="100" required> <br/>
        <input type="checkbox" id="administrador" name="administrador" checked> <label for="administrador">Administrador</label> <br/>
        <label for="cpf">CPF: </label><input id="cpf" name="cpf" type="number" placeholder="Digite o cpf" min="1" max="99999999999" required> <br/>
        <label for="tipoSelect"> Tipo de usuário: </label>
        <select id="tipoSelect" onchange="mudaTipo()">
            <option value="Nenhum"> Nenhum </option>
            <option value="Professor"> Professor </option>
            <option value="Aluno" selected> Aluno </option>
        </select><br/>
        <input id="tipo" name="tipo" type="hidden" placeholder="" value="Aluno" maxlength="10">
        <?php
            echo '<label id="labelCurso" for="cursoSelect"> Curso do usuário: </label>';
            echo '<select id="cursoSelect" onchange="mudaCurso()">';
            foreach($_SESSION['queryPessoaCursos1'] as $linha_array) {
                $nome = $linha_array['Nome'];
                echo '<option value='."'$nome'".">".$nome."</option>";
            } 
            foreach($_SESSION['queryPessoaCursos1'] as $linha_array) {
                echo '<input type="hidden" id="curso" name="curso" value='."'$nome'"."/>";
                break;
            }            
            echo '</select>';
            echo '<br/>';
            echo '<label id="labelMatricula" for="matricula">Matricula: </label><input id="matricula" name="matricula" type="text" placeholder="Digite a matricula" min="1" max="99999999"> <br/>'
        ?>
        <input type="submit" name="submit" value="Enviar">
    </form>
    <script>
        function mudaTipo(){
           var select = document.getElementById('tipoSelect').value;
           document.getElementById('tipo').value = select;
           if(select!='Aluno'){
                document.getElementById("cursoSelect").style.visibility = "hidden";
                document.getElementById("matricula").style.visibility = "hidden";
                document.getElementById("labelCurso").style.visibility = "hidden";
                document.getElementById("labelMatricula").style.visibility = "hidden";
           }
           else{
                document.getElementById("cursoSelect").style.visibility = "visible";
                document.getElementById("matricula").style.visibility = "visible"; 
                document.getElementById("labelCurso").style.visibility = "visible";
                document.getElementById("labelMatricula").style.visibility = "visible";             
           }
        }
        function mudaCurso(){
            document.getElementById('curso').value = document.getElementById('cursoSelect').value;
        }
    </script>
    <div id="footer"></div>    
</body>
</html>