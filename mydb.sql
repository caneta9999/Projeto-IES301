DROP database `mydb`;
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`Curso`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Curso` (
  `idCurso` INT NOT NULL AUTO_INCREMENT,
  `Nome` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`idCurso`),
  UNIQUE INDEX `Nome_UNIQUE` (`Nome` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Disciplina`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Disciplina` (
  `idDisciplina` INT NOT NULL AUTO_INCREMENT,
  `Nome` VARCHAR(50) NOT NULL,
  `Descrição` VARCHAR(500) NOT NULL,
  `Código` DECIMAL(4,0) NOT NULL,
  `Sigla` VARCHAR(8) NOT NULL,
  `Tipo` DECIMAL(1,0) NOT NULL,
  `Ativa` DECIMAL(1,0) NOT NULL,
  `Curso_idCurso` INT NOT NULL,
  PRIMARY KEY (`idDisciplina`),
  UNIQUE INDEX `Código_UNIQUE` (`Código` ASC) ,
  INDEX `fk_Disciplina_Curso1_idx` (`Curso_idCurso` ASC) ,
  CONSTRAINT `fk_Disciplina_Curso1`
    FOREIGN KEY (`Curso_idCurso`)
    REFERENCES `mydb`.`Curso` (`idCurso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Usuario` (
  `idUsuario` INT NOT NULL AUTO_INCREMENT,
  `Login` VARCHAR(100) NOT NULL,
  `Senha` VARCHAR(50) NOT NULL,
  `Nome` VARCHAR(100) NOT NULL,
  `Administrador` DECIMAL(1,0) NOT NULL,
  `Cpf` DECIMAL(11,0) NOT NULL,
  `Tipo` DECIMAL(1,0) NOT NULL,
  `Ativo` DECIMAL(1,0) NOT NULL,
  PRIMARY KEY (`idUsuario`),
  UNIQUE INDEX `Login_UNIQUE` (`Login` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Aluno`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Aluno` (
  `idAluno` INT NOT NULL AUTO_INCREMENT,
  `Matricula` DECIMAL(8,0) NOT NULL,
  `Usuario_idUsuario` INT NOT NULL,
  `Curso_idCurso` INT NOT NULL,
  PRIMARY KEY (`idAluno`),
  INDEX `fk_Aluno_Usuario_idx` (`Usuario_idUsuario` ASC) ,
  INDEX `fk_Aluno_Curso1_idx` (`Curso_idCurso` ASC) ,
  UNIQUE INDEX `Matricula_UNIQUE` (`Matricula` ASC) ,
  CONSTRAINT `fk_Aluno_Usuario`
    FOREIGN KEY (`Usuario_idUsuario`)
    REFERENCES `mydb`.`Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Aluno_Curso1`
    FOREIGN KEY (`Curso_idCurso`)
    REFERENCES `mydb`.`Curso` (`idCurso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Professor`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Professor` (
  `idProfessor` INT NOT NULL AUTO_INCREMENT,
  `Usuario_idUsuario` INT NOT NULL,
  PRIMARY KEY (`idProfessor`),
  INDEX `fk_Professor_Usuario1_idx` (`Usuario_idUsuario` ASC) ,
  CONSTRAINT `fk_Professor_Usuario1`
    FOREIGN KEY (`Usuario_idUsuario`)
    REFERENCES `mydb`.`Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`ProfessorDisciplina`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`ProfessorDisciplina` (
  `idProfessorDisciplina` INT NOT NULL AUTO_INCREMENT,
  `Professor_idProfessor` INT NOT NULL,
  `Disciplina_idDisciplina` INT NOT NULL,
  `Periodo` DECIMAL(1,0) NOT NULL,
  `DataInicial` DATE NOT NULL,
  `DataFinal` DATE NULL,
  `DiaSemana` DECIMAL(1,0) NOT NULL,
  PRIMARY KEY (`idProfessorDisciplina`),
  INDEX `fk_Professor_has_Disciplina_Disciplina1_idx` (`Disciplina_idDisciplina` ASC) ,
  INDEX `fk_Professor_has_Disciplina_Professor1_idx` (`Professor_idProfessor` ASC) ,
  CONSTRAINT `fk_Professor_has_Disciplina_Professor1`
    FOREIGN KEY (`Professor_idProfessor`)
    REFERENCES `mydb`.`Professor` (`idProfessor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Professor_has_Disciplina_Disciplina1`
    FOREIGN KEY (`Disciplina_idDisciplina`)
    REFERENCES `mydb`.`Disciplina` (`idDisciplina`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Critica`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Critica` (
  `idCritica` INT NOT NULL AUTO_INCREMENT,
  `Aluno_idAluno` INT NOT NULL,
  `NotaDisciplina` DECIMAL(1,0) NOT NULL,
  `Descrição` VARCHAR(500) NOT NULL,
  `ProfessorDisciplina_idProfessorDisciplina` INT NOT NULL,
  `Data` DATETIME NOT NULL,
  `NotaEvolucao` DECIMAL(1,0) NOT NULL,
  `NotaAluno` DECIMAL(1,0) NOT NULL,
  `AnoSemestre` DECIMAL(5,0) NOT NULL,
  `Elogios` VARCHAR(50) NOT NULL,
  `Criticas` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`idCritica`),
  INDEX `fk_Aluno_has_Disciplina_Aluno1_idx` (`Aluno_idAluno` ASC) ,
  INDEX `fk_Critica_ProfessorDisciplina1_idx` (`ProfessorDisciplina_idProfessorDisciplina` ASC) ,
  CONSTRAINT `fk_Aluno_has_Disciplina_Aluno1`
    FOREIGN KEY (`Aluno_idAluno`)
    REFERENCES `mydb`.`Aluno` (`idAluno`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Critica_ProfessorDisciplina1`
    FOREIGN KEY (`ProfessorDisciplina_idProfessorDisciplina`)
    REFERENCES `mydb`.`ProfessorDisciplina` (`idProfessorDisciplina`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;