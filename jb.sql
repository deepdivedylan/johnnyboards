-- MySQL Script generated by MySQL Workbench
-- 05/14/14 10:08:48
-- Model: New Model    Version: 1.0
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `mydb` ;
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
-- -----------------------------------------------------
-- Schema new_schema1
-- -----------------------------------------------------
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`contact`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`contact` ;

CREATE TABLE IF NOT EXISTS `mydb`.`contact` (
  `idcontact` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `companyName` VARCHAR(64) NOT NULL,
  `address1` VARCHAR(64) NOT NULL,
  `addres2` VARCHAR(64) NULL,
  `city` VARCHAR(64) NOT NULL,
  `zipcode` VARCHAR(10) NOT NULL,
  `state` VARCHAR(64) NOT NULL,
  `phoneNumber` VARCHAR(20) NOT NULL,
  `email` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`idcontact`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`client`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`client` ;

CREATE TABLE IF NOT EXISTS `mydb`.`client` (
  `idclient` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `contractStart` DATE NOT NULL,
  `contractRenew` DATE NOT NULL,
  `clientType` INT UNSIGNED NOT NULL,
  `contactId` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idclient`),
  INDEX `contactId_idx` (`contactId` ASC),
  CONSTRAINT `contactId`
    FOREIGN KEY (`contactId`)
    REFERENCES `mydb`.`contact` (`idcontact`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`board`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`board` ;

CREATE TABLE IF NOT EXISTS `mydb`.`board` (
  `idboard` INT UNSIGNED NOT NULL,
  `boardStatus` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`idboard`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`venue`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`venue` ;

CREATE TABLE IF NOT EXISTS `mydb`.`venue` (
  `idVenue` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `longitude` DECIMAL(9,6) NOT NULL,
  `latitude` DECIMAL(9,6) NOT NULL,
  `contactId` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idVenue`),
  INDEX `contactId_idx` (`contactId` ASC),
  CONSTRAINT `contactId`
    FOREIGN KEY (`contactId`)
    REFERENCES `mydb`.`contact` (`idcontact`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`boardLocation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`boardLocation` ;

CREATE TABLE IF NOT EXISTS `mydb`.`boardLocation` (
  `idBoardLocation` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `boardLocation` VARCHAR(64) NULL,
  `venueId` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idBoardLocation`),
  INDEX `venueId_idx` (`venueId` ASC),
  CONSTRAINT `venueId`
    FOREIGN KEY (`venueId`)
    REFERENCES `mydb`.`venue` (`idVenue`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`adPlacement`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`adPlacement` ;

CREATE TABLE IF NOT EXISTS `mydb`.`adPlacement` (
  `clientId` INT UNSIGNED NOT NULL,
  `boardId` INT UNSIGNED NOT NULL,
  `contactId` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`clientId`, `boardId`),
  INDEX `contactId_idx` (`contactId` ASC),
  INDEX `boardId_idx` (`boardId` ASC),
  CONSTRAINT `clientId`
    FOREIGN KEY (`clientId`)
    REFERENCES `mydb`.`client` (`idclient`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `boardId`
    FOREIGN KEY (`boardId`)
    REFERENCES `mydb`.`board` (`idboard`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `contactId`
    FOREIGN KEY (`contactId`)
    REFERENCES `mydb`.`contact` (`idcontact`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`type` ;

CREATE TABLE IF NOT EXISTS `mydb`.`type` (
  `idtype` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`idtype`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`poster`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`poster` ;

CREATE TABLE IF NOT EXISTS `mydb`.`poster` (
  `idposter` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `posterType` INT UNSIGNED NOT NULL,
  `contactId` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idposter`),
  INDEX `contactId_idx` (`contactId` ASC),
  INDEX `posterType_idx` (`posterType` ASC),
  CONSTRAINT `contactId`
    FOREIGN KEY (`contactId`)
    REFERENCES `mydb`.`contact` (`idcontact`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `posterType`
    FOREIGN KEY (`posterType`)
    REFERENCES `mydb`.`type` (`idtype`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`printer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`printer` ;

CREATE TABLE IF NOT EXISTS `mydb`.`printer` (
  `idprinter` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `hours open` VARCHAR(64) NULL,
  `longitude` DECIMAL(9,6) NULL,
  `latitude` DECIMAL(9,6) NULL,
  `areasCovered` VARCHAR(64) NULL,
  `contactId` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idprinter`),
  INDEX `contactId_idx` (`contactId` ASC),
  CONSTRAINT `contactId`
    FOREIGN KEY (`contactId`)
    REFERENCES `mydb`.`contact` (`idcontact`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
