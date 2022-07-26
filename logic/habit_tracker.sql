-- 
-- Open phpMyAdmin http://localhost/phpmyadmin/
-- Menu > Import > Choose File > ".../habit_tracker.sql"
-- 

--
-- Database: `habit_tracker`
--

DROP DATABASE IF EXISTS `habit_tracker`;
CREATE DATABASE `habit_tracker`;
USE `habit_tracker`;

--
-- Table `habit`
--

CREATE TABLE `habit` (
	`id` INT NOT NULL,
  `habit_name` VARCHAR(255) NOT NULL,
  `completed` BOOLEAN NOT NULL DEFAULT 0,
  `created_on` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);  

--
-- Event to reset field `completed` at midnight
-- 

DELIMITER //
DROP EVENT IF EXISTS `reset_done_today` //
CREATE EVENT `reset_done_today` 
	ON SCHEDULE EVERY 1 DAY 
	STARTS CONCAT(DATE(NOW() + INTERVAL 1 DAY ), ' 00:00:05')
DO
	BEGIN
    UPDATE `habit` 
    SET `completed` = 0 
    WHERE `completed` = 1;
	END //