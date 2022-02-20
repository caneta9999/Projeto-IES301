-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Tempo de geração: 14-Fev-2022 às 12:50
-- Versão do servidor: 10.4.22-MariaDB
-- versão do PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `mydb`
--

CREATE DATABASE mydb;
USE mydb;

-- --------------------------------------------------------

--
-- Estrutura da tabela `aluno`
--

CREATE TABLE `aluno` (
  `idAluno` int(11) NOT NULL,
  `Matricula` decimal(8,0) NOT NULL,
  `Usuario_idUsuario` int(11) NOT NULL,
  `Curso_idCurso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `aluno`
--

INSERT INTO `aluno` (`idAluno`, `Matricula`, `Usuario_idUsuario`, `Curso_idCurso`) VALUES
(3, '109', 6, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `critica`
--

CREATE TABLE `critica` (
  `idCritica` int(11) NOT NULL,
  `Aluno_idAluno` int(11) NOT NULL,
  `NotaDisciplina` decimal(1,0) NOT NULL,
  `Descrição` varchar(500) NOT NULL,
  `ProfessorDisciplina_idProfessorDisciplina` int(11) NOT NULL,
  `Data` datetime NOT NULL,
  `NotaEvolucao` decimal(1,0) NOT NULL,
  `NotaAluno` decimal(1,0) NOT NULL	
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `curso`
--

CREATE TABLE `curso` (
  `idCurso` int(11) NOT NULL,
  `Nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `curso`
--

INSERT INTO `curso` (`idCurso`, `Nome`) VALUES
(2, 'Curso2');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cursodisciplina`
--

CREATE TABLE `cursodisciplina` (
  `CursoDisciplinaId` int(11) NOT NULL,
  `Curso_idCurso` int(11) NOT NULL,
  `Disciplina_idDisciplina` int(11) NOT NULL,
  `Tipo` decimal(1,0) NOT NULL,
  `Ativa` decimal(1,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `cursodisciplina`
--

INSERT INTO `cursodisciplina` (`CursoDisciplinaId`, `Curso_idCurso`, `Disciplina_idDisciplina`, `Tipo`, `Ativa`) VALUES
(9, 2, 6, '0', '1');

-- --------------------------------------------------------

--
-- Estrutura da tabela `disciplina`
--

CREATE TABLE `disciplina` (
  `idDisciplina` int(11) NOT NULL,
  `Nome` varchar(50) NOT NULL,
  `Descrição` varchar(500) NOT NULL,
  `Código` decimal(4,0) NOT NULL,
  `Sigla` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `disciplina`
--

INSERT INTO `disciplina` (`idDisciplina`, `Nome`, `Descrição`, `Código`, `Sigla`) VALUES
(6, 'Análise', '....', '11', 'AAA101');

-- --------------------------------------------------------

--
-- Estrutura da tabela `professor`
--

CREATE TABLE `professor` (
  `idProfessor` int(11) NOT NULL,
  `Usuario_idUsuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `professor`
--

INSERT INTO `professor` (`idProfessor`, `Usuario_idUsuario`) VALUES
(2, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `professordisciplina`
--

CREATE TABLE `professordisciplina` (
  `idProfessorDisciplina` int(11) NOT NULL,
  `Professor_idProfessor` int(11) NOT NULL,
  `Disciplina_idDisciplina` int(11) NOT NULL,
  `Periodo` decimal(1,0) NOT NULL,
  `DataInicial` date NOT NULL,
  `DataFinal` date DEFAULT NULL,
  `DiaSemana` decimal(1,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `professordisciplina`
--

INSERT INTO `professordisciplina` (`idProfessorDisciplina`, `Professor_idProfessor`, `Disciplina_idDisciplina`, `Periodo`, `DataInicial`, `DataFinal`, `DiaSemana`) VALUES
(16, 2, 6, '0', '2022-02-08', '0000-00-00', '6'),
(18, 2, 6, '1', '2022-02-08', '0000-00-00', '2');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `idUsuario` int(11) NOT NULL,
  `Login` varchar(100) NOT NULL,
  `Senha` varchar(50) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `Administrador` decimal(1,0) NOT NULL,
  `Cpf` decimal(11,0) NOT NULL,
  `Tipo` decimal(1,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`idUsuario`, `Login`, `Senha`, `Nome`, `Administrador`, `Cpf`, `Tipo`) VALUES
(4, 'email@gmail.com', '12345678', 'André', '1', '123', '1'),
(6, 'email2@gmail.com', '12345678', 'André', '0', '1142', '2');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `aluno`
--
ALTER TABLE `aluno`
  ADD PRIMARY KEY (`idAluno`),
  ADD UNIQUE KEY `Matricula_UNIQUE` (`Matricula`),
  ADD KEY `fk_Aluno_Usuario_idx` (`Usuario_idUsuario`),
  ADD KEY `fk_Aluno_Curso1_idx` (`Curso_idCurso`);

--
-- Índices para tabela `critica`
--
ALTER TABLE `critica`
  ADD PRIMARY KEY (`idCritica`),
  ADD KEY `fk_Aluno_has_Disciplina_Aluno1_idx` (`Aluno_idAluno`),
  ADD KEY `fk_Critica_ProfessorDisciplina1_idx` (`ProfessorDisciplina_idProfessorDisciplina`);

--
-- Índices para tabela `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`idCurso`),
  ADD UNIQUE KEY `Nome_UNIQUE` (`Nome`);

--
-- Índices para tabela `cursodisciplina`
--
ALTER TABLE `cursodisciplina`
  ADD PRIMARY KEY (`CursoDisciplinaId`),
  ADD KEY `fk_Curso_has_Disciplina_Disciplina1_idx` (`Disciplina_idDisciplina`),
  ADD KEY `fk_Curso_has_Disciplina_Curso1_idx` (`Curso_idCurso`);

--
-- Índices para tabela `disciplina`
--
ALTER TABLE `disciplina`
  ADD PRIMARY KEY (`idDisciplina`),
  ADD UNIQUE KEY `Código_UNIQUE` (`Código`),
  ADD UNIQUE KEY `Sigla_UNIQUE` (`Sigla`),
  ADD UNIQUE KEY `Nome` (`Nome`);

--
-- Índices para tabela `professor`
--
ALTER TABLE `professor`
  ADD PRIMARY KEY (`idProfessor`),
  ADD KEY `fk_Professor_Usuario1_idx` (`Usuario_idUsuario`);

--
-- Índices para tabela `professordisciplina`
--
ALTER TABLE `professordisciplina`
  ADD PRIMARY KEY (`idProfessorDisciplina`),
  ADD KEY `fk_Professor_has_Disciplina_Disciplina1_idx` (`Disciplina_idDisciplina`),
  ADD KEY `fk_Professor_has_Disciplina_Professor1_idx` (`Professor_idProfessor`);

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idUsuario`),
  ADD UNIQUE KEY `Cpf_UNIQUE` (`Cpf`),
  ADD UNIQUE KEY `Login_UNIQUE` (`Login`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `aluno`
--
ALTER TABLE `aluno`
  MODIFY `idAluno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `critica`
--
ALTER TABLE `critica`
  MODIFY `idCritica` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `curso`
--
ALTER TABLE `curso`
  MODIFY `idCurso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `cursodisciplina`
--
ALTER TABLE `cursodisciplina`
  MODIFY `CursoDisciplinaId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `disciplina`
--
ALTER TABLE `disciplina`
  MODIFY `idDisciplina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `professor`
--
ALTER TABLE `professor`
  MODIFY `idProfessor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `professordisciplina`
--
ALTER TABLE `professordisciplina`
  MODIFY `idProfessorDisciplina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `aluno`
--
ALTER TABLE `aluno`
  ADD CONSTRAINT `fk_Aluno_Curso1` FOREIGN KEY (`Curso_idCurso`) REFERENCES `curso` (`idCurso`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Aluno_Usuario` FOREIGN KEY (`Usuario_idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `critica`
--
ALTER TABLE `critica`
  ADD CONSTRAINT `fk_Aluno_has_Disciplina_Aluno1` FOREIGN KEY (`Aluno_idAluno`) REFERENCES `aluno` (`idAluno`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Critica_ProfessorDisciplina1` FOREIGN KEY (`ProfessorDisciplina_idProfessorDisciplina`) REFERENCES `professordisciplina` (`idProfessorDisciplina`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `cursodisciplina`
--
ALTER TABLE `cursodisciplina`
  ADD CONSTRAINT `fk_Curso_has_Disciplina_Curso1` FOREIGN KEY (`Curso_idCurso`) REFERENCES `curso` (`idCurso`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Curso_has_Disciplina_Disciplina1` FOREIGN KEY (`Disciplina_idDisciplina`) REFERENCES `disciplina` (`idDisciplina`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `professor`
--
ALTER TABLE `professor`
  ADD CONSTRAINT `fk_Professor_Usuario1` FOREIGN KEY (`Usuario_idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `professordisciplina`
--
ALTER TABLE `professordisciplina`
  ADD CONSTRAINT `fk_Professor_has_Disciplina_Disciplina1` FOREIGN KEY (`Disciplina_idDisciplina`) REFERENCES `disciplina` (`idDisciplina`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Professor_has_Disciplina_Professor1` FOREIGN KEY (`Professor_idProfessor`) REFERENCES `professor` (`idProfessor`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
