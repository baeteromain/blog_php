-- MySQL Script generated by MySQL Workbench
-- Mon Jan  4 15:33:53 2021
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema blog_php_br
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `blog_php_br` ;

-- -----------------------------------------------------
-- Schema blog_php_br
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `blog_php_br` DEFAULT CHARACTER SET utf8 ;
USE `blog_php_br` ;

-- -----------------------------------------------------
-- Table `blog_php_br`.`role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blog_php_br`.`role` ;

CREATE TABLE IF NOT EXISTS `blog_php_br`.`role` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `blog_php_br`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blog_php_br`.`user` ;

CREATE TABLE IF NOT EXISTS `blog_php_br`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(100) NOT NULL,
  `username` VARCHAR(45) NOT NULL,
  `password` VARCHAR(60) NOT NULL,
  `role_id` INT NOT NULL,
  `token` VARCHAR(255) NULL,
  `valid` INT NULL DEFAULT 0,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  PRIMARY KEY (`id`),
  INDEX `fk_user_role1_idx` (`role_id` ASC),
  CONSTRAINT `fk_user_role1`
    FOREIGN KEY (`role_id`)
    REFERENCES `blog_php_br`.`role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `blog_php_br`.`post`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blog_php_br`.`post` ;

CREATE TABLE IF NOT EXISTS `blog_php_br`.`post` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `filename` VARCHAR(255) NOT NULL,
  `content` LONGTEXT NOT NULL,
  `created_at` DATE NOT NULL,
  `update_at` DATE NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_post_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_post_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `blog_php_br`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `blog_php_br`.`comment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blog_php_br`.`comment` ;

CREATE TABLE IF NOT EXISTS `blog_php_br`.`comment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `content` VARCHAR(255) NOT NULL,
  `created_at` DATE NOT NULL,
  `publish` TINYINT NOT NULL DEFAULT 0,
  `comment_id` INT NOT NULL,
  `post_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`, `post_id`, `user_id`),
  INDEX `fk_category_category_idx` (`comment_id` ASC),
  INDEX `fk_comment_post1_idx` (`post_id` ASC),
  INDEX `fk_comment_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_category_category`
    FOREIGN KEY (`comment_id`)
    REFERENCES `blog_php_br`.`comment` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comment_post1`
    FOREIGN KEY (`post_id`)
    REFERENCES `blog_php_br`.`post` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comment_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `blog_php_br`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `blog_php_br`.`category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blog_php_br`.`category` ;

CREATE TABLE IF NOT EXISTS `blog_php_br`.`category` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `blog_php_br`.`post_category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blog_php_br`.`post_category` ;

CREATE TABLE IF NOT EXISTS `blog_php_br`.`post_category` (
  `category_id` INT NOT NULL,
  `post_id` INT NOT NULL,
  PRIMARY KEY (`category_id`, `post_id`),
  INDEX `fk_category_has_post_post1_idx` (`post_id` ASC),
  INDEX `fk_category_has_post_category1_idx` (`category_id` ASC),
  CONSTRAINT `fk_category_has_post_category1`
    FOREIGN KEY (`category_id`)
    REFERENCES `blog_php_br`.`category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_category_has_post_post1`
    FOREIGN KEY (`post_id`)
    REFERENCES `blog_php_br`.`post` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
