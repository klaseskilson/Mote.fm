-- database.sql
-- använd denna för att installera om sidan

CREATE DATABASE IF NOT EXISTS `hathor`;

USE `hathor`;

DROP TABLE IF EXISTS `users`;

CREATE TABLE IF NOT EXISTS `users` (
	`uid` INT(6) NOT NULL AUTO_INCREMENT,
	`email` CHAR(8) NOT NULL,
	`name` CHAR(60) NOT NULL,
	`password` CHAR(130) NOT NULL,
	PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;
