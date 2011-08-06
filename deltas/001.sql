CREATE DATABASE IF NOT EXISTS `leedshack`;
USE `leedshack`;

CREATE TABLE IF NOT EXISTS `activityStream` (
	id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	accountId VARCHAR(255) NOT NULL,
	`date` TIMESTAMP,
	`message` TEXT,
	INDEX(accountId),
	INDEX(`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
