
--
-- GRANT ALL ON commentify.* TO user@localhost IDENTIFIED BY 'pass';
-- CREATE DATABASE commentify;
USE anaxdb;
-- CREATE DATABASE commentify;

-- Ensure UTF8 as chacrter encoding within connection.
SET NAMES utf8;

--
-- Setup tables
--
-- ------------------------------------------------------------------------
DROP TABLE IF EXISTS `ramverk1_comments`;
DROP TABLE IF EXISTS `ramverk1_question`;
DROP TABLE IF EXISTS `ramverk1_users`;
-- ------------------------------------------------------------------------

CREATE TABLE `ramverk1_users`
(
  `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `email` VARCHAR(100),
  `pass` VARCHAR(255) NOT NULL,
  `authority` VARCHAR(255) NOT NULL,
  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `question` text
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;



CREATE TABLE `ramverk1_comments`
(
  `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `user` VARCHAR(100) NOT NULL,
  `text` text,

  `parentId` INT,
  `type` VARCHAR(100) NOT NULL,

  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `status` VARCHAR(20) DEFAULT 'active',


  FOREIGN KEY (`user`) REFERENCES `ramverk1_users` (`name`)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;






-- ------------------------------------------------------------------------
--
-- INSERT SETUP
--
-- ------------------------------------------------------------------------

INSERT INTO `ramverk1_users` (`name`, `email`, `pass`, `authority`, `question`) VALUES
    ("admin", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "admin", "lasagne"),
    ("kalle", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "user", "lasagne"),
    ("sven", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "user", "lasagne"),
    ("user", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "user", "lasagne");


INSERT INTO `ramverk1_comments` (`user`, `text`, `parentId`, `type`) VALUES
    ("kalle", "Hej bör kaffe drickas ur tjocka koppar eller smala? Vad gillar ni mest? Personligen så föredrar jag smala.", 0, "question"),
    ("sven", "Bra fråga, troligtvis något många glömmer att tänka på. Jag har bara tjocka kaffekoppar hemma.", 1, "answer"),
    ("kalle", "Ok tack för ditt svar", 2, "comment"),
    ("sven", "En kommentar till din fråga men ej svar", 1, "comment");



-- ------------------------------------------------------------------------
