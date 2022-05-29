<?php
session_start();
if(!isset($_SESSION['idUsuarioLogin']))
{
  header('location:../../Login/index.php');
}?>
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
    <?php 
      if($_SESSION['administradorLogin']) {
        echo "<div id='menu' class='menu-adm'></div>";
      } else {
        echo "<div id='menu'></div>";
      }
    ?>
    <div id="navbar"></div>
    <?php
		if(isset($_SESSION['mensagemFinalizacao'])){
			echo "<p class='mensagemFinalizacao'>".$_SESSION['mensagemFinalizacao']."</p>";
            unset($_SESSION['mensagemFinalizacao']);
		}
		if(isset($_SESSION['mensagemErro'])){
			echo "<p class='mensagemErro'>".$_SESSION['mensagemErro']."</p>";
			unset($_SESSION['mensagemErro']);
		}
    ?>
	<?php 
	echo '<h2>Visualizar disciplina</h2>';
    echo '<button class="button btnVoltar"><a href="../index.php">Voltar</a></button><br/>';
    if($_SESSION['administradorLogin']) {
    echo '<form action="php.php" method="POST">';
        echo '<label for="id">Id: </label><input id="id" name="id" type="number" placeholder="Digite o id" min="1" max="99999999999" required> <br/>';
        echo '<input type="submit" name="submit" value="Enviar">';
    echo '</form>';
    echo '<hr/>';
	}
	?>
	<?php
        if(isset($_SESSION['queryVisualizarDisciplina1']) && isset($_SESSION['queryVisualizarDisciplina2'])){
            $nome = 'Disciplina';
            $id = -1;
            $sigla = 'AAA000';
            $codigo = 1111;
            $descricao = 'Disciplina...';
            $professor = [];
			$tipo = 0;
            $ativa = 0;
			$curso = 0;
            foreach($_SESSION['queryVisualizarDisciplina1'] as $linha_array){
                $nome = $linha_array['Nome'];
                $id = $linha_array['idDisciplina'];
                $sigla = $linha_array['Sigla'];
                $codigo = $linha_array['Código'];
				$tipo = $linha_array['Tipo'];
                $ativa = $linha_array['Ativa'];
                $descricao = $linha_array['Descrição'];
				$curso = $linha_array['NomeCurso'];
                $_SESSION['idAlteracao3'] = $id;
            }
            foreach($_SESSION['queryVisualizarDisciplina2'] as $linha_array) {
                $professor_nome = $linha_array['Nome'];
                
                $professor_periodo = '';
                switch ($linha_array['Periodo']) {
                    case 0:
                        $professor_periodo = 'Manhã';
                        break;
                    case 1:
                        $professor_periodo = 'Tarde';
                        break;
                    case 2:
                        $professor_periodo = 'Noite';
                        break;
                }

                $professor_diaSemana = '';
                switch ($linha_array['DiaSemana']) {
                    case 2:
                        $professor_diaSemana = 'Segunda-Feira';
                        break;
                    case 3:
                        $professor_diaSemana = 'Terça-Feira';
                        break;
                    case 4:
                        $professor_diaSemana = 'Quarta-Feira';
                        break;
                    case 5:
                        $professor_diaSemana = 'Quinta-Feira';
                        break;
                    case 6:
                        $professor_diaSemana = 'Sexta-Feira';
                        break;
                    case 7:
                        $professor_diaSemana = 'Sábado';
                        break;
                }

                array_push($professor, $professor_nome . ' (' . $professor_periodo . ') — ' . $professor_diaSemana);             
                
            }

            $professor_echo = '<div style="text-align: left;">';
            foreach ($professor as $umProfessor) {
                $professor_echo .= $umProfessor . '</br>';
            }
            $professor_echo .= "</div>";

            echo '<main id="text-visualizar-disciplina">';
            echo '<section>';
            echo '<h1>' . $sigla . '</h1>';
            echo '<p><span class="p-bold">Nome: </span>' . $nome . '</p>';
            echo '<p><span class="p-bold">Código: </span>' . $codigo . '</p>';
            echo '<p><span class="p-bold">Curso: </span>' . $curso . '</p>';
            echo '<div style="display: flex; justify-content: left; align-items: baseline">';
            echo '<p><span class="p-bold">Professor(a):&nbsp;</span></p>';
            echo $professor_echo;
            echo '</div>';
            echo '</section>';
            echo '<section>';
            echo '<h2> Descrição </h2>';
            echo '<p>'. $descricao . '</p>';
            echo '</section>';
            echo '</main>';
            /*
            echo '<form method="POST" action="php2.php">';
            echo '<label for="id">Id:</label> <input value='.$id.' id="id" name="id" type="number" placeholder="Id da disciplina" min="1" max="99999999999" required readonly="readonly"/> <br/>';
            echo '<label for="nome">Nome:</label> <input value='."'$nome'".' id="nome" name="nome" type="text" placeholder="Nome da disciplina" maxlength="50" required /> <br/>';
            echo '<label for="descricao"> Descrição: </label><textarea rows="5" cols="30" id="descricao" name="descricao" placeholder="Digite a descrição da matéria" required maxlength="500" >'."$descricao".'</textarea> <br/>';
            echo '<label for="codigo">Código: </label><input value='."'$codigo'".'id="codigo" name="codigo" placeholder="AAA000" type="number" min="1" max="9999" required> <br/>';
            echo '<label for="sigla">Sigla: </label><input value='."'$sigla'".'id="sigla" name="sigla" placeholder="Sigla da disciplina" type="text" maxlength="6" required> <br/>';        
            echo '<label for="tipoSelect"> Tipo: </label>';
            echo '<select id="tipoSelect" onchange="mudaTipo()">';
            if($tipo == 0){
                echo '<option value="0" selected> Obrigatória </option>';
                echo '<option value="1"> Eletiva </option>';
                echo '<option value="2"> Escolha </option>';}
            else if($tipo == 1){
                echo '<option value="0"> Obrigatória </option>';
                echo '<option value="1" selected> Eletiva </option>';
                echo '<option value="2"> Escolha </option>';                
            }else{
                echo '<option value="0"> Obrigatória </option>';
                echo '<option value="1"> Eletiva </option>';
                echo '<option value="2" selected> Escolha </option>';                 
            }
			echo '<input id="tipo" name="tipo" type="hidden" placeholder="" value='."'$tipo'".'>';
			echo '<br/>';
			echo '<label for="curso">Curso:</label><input type="text" id="curso" readonly="readonly" name="curso" value='."'$curso'"."/>";
			echo '<br/>';
			echo '<input name="submit" type="submit" value="Excluir" />';
            echo '<input name="submit" type="submit" value="Alterar" />';
            echo '</form>';
            */
            unset($_SESSION['queryVisualizarDisciplina1']);
            unset($_SESSION['queryVisualizarDisciplina2']);
        }
    ?>
	<script>
    function mudaTipo(){
        document.getElementById('tipo').value = document.getElementById('tipoSelect').value;
    }
    </script>
    <div id="footer"></div>    
</body>
</html>