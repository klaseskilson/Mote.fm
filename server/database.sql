-- database.sql
-- use this to install the page's database

CREATE DATABASE IF NOT EXISTS `hathor`;

USE `hathor`;

DROP TABLE IF EXISTS `nowplaying`;
DROP TABLE IF EXISTS `quevote`;
DROP TABLE IF EXISTS `quesong`;
DROP TABLE IF EXISTS `parties`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `spotifycache`;

CREATE TABLE IF NOT EXISTS `users` (
	`uid` INT(6) NOT NULL AUTO_INCREMENT,
	`email` CHAR(254) NOT NULL,
	`name` CHAR(60) NOT NULL,
	`password` CHAR(130) NOT NULL,
	`hashkey` CHAR(20) NOT NULL,
	`time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`activated` BOOL DEFAULT 0,
	PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE IF NOT EXISTS `parties` (
	`partyid` INT(6) NOT NULL AUTO_INCREMENT,
	`uid` INT(6) NOT NULL,
	`name` CHAR(60) NOT NULL,
	`hash` CHAR(5) NOT NULL,
	`time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`locale` CHAR(2) NOT NULL,
	PRIMARY KEY (`partyid`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

ALTER TABLE `parties`
	ADD CONSTRAINT `parties_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`);

CREATE TABLE IF NOT EXISTS `quesong` (
	`songid` INT(6) NOT NULL AUTO_INCREMENT,
	`uid` INT(6) NOT NULL,
	`partyid` INT(6) NOT NULL,
	`uri` CHAR(60) NOT NULL,
	`played` BOOL DEFAULT 0,
	`time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`songid`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

ALTER TABLE `quesong`
	ADD CONSTRAINT `quesong_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`);
ALTER TABLE `quesong`
	ADD CONSTRAINT `quesong_ibfk_2` FOREIGN KEY (`partyid`) REFERENCES `parties` (`partyid`);

CREATE TABLE IF NOT EXISTS `quevote` (
	`voteid` INT(6) NOT NULL AUTO_INCREMENT,
	`songid` INT(6) NOT NULL,
	`uid` INT(6) NOT NULL,
	`time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`voteid`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

ALTER TABLE `quevote`
	ADD CONSTRAINT `quevote_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`);
ALTER TABLE `quevote`
	ADD CONSTRAINT `quevote_ibfk_2` FOREIGN KEY (`songid`) REFERENCES `quesong` (`songid`);


CREATE TABLE IF NOT EXISTS `nowplaying` (
	`playid` INT(16) NOT NULL AUTO_INCREMENT,
	`partyid` INT(6) NOT NULL,
	`trackuri` char(64) NOT NULL,
	PRIMARY KEY (`playid`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

ALTER TABLE `nowplaying`
	ADD CONSTRAINT `nowplaying_ibfk_1` FOREIGN KEY (`partyid`) REFERENCES `parties` (`partyid`);


CREATE TABLE IF NOT EXISTS `spotifycache` (
	`cacheid` INT(6) NOT NULL AUTO_INCREMENT,
	`uri` CHAR(60) NOT NULL,
	`songname` TEXT NOT NULL,
	`artistname` TEXT NOT NULL,
	`albumname` TEXT NOT NULL,
	`image` TEXT NOT NULL,
	PRIMARY KEY(`cacheid`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE IF NOT EXISTS `playedsongs` (
	`playid` INT(6) NOT NULL AUTO_INCREMENT,
	`songid` INT(6) NOT NULL,
	`time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY(`playid`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

ALTER TABLE `playedsongs`
	add CONSTRAINT `playedsongs_ibfk_1`FOREIGN KEY (`songid`) REFERENCES `quesong` (`songid`)
