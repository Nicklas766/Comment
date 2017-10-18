
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


CREATE TABLE `ramverk1_questions`
(
  `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `user` VARCHAR(100) NOT NULL,
  `acceptedId` int,
  `title` text,
  `tags` text,
  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `status` VARCHAR(20) DEFAULT 'active',

  FOREIGN KEY (`user`) REFERENCES `ramverk1_users` (`name`)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

CREATE TABLE `ramverk1_posts`
(
  `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `user` VARCHAR(100) NOT NULL,
  `questionId` int,
  `text` text,
  `type` text, -- question or answer
  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (`user`) REFERENCES `ramverk1_users` (`name`),
  FOREIGN KEY (`questionId`) REFERENCES `ramverk1_questions` (`ramverk1_questions1`)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;


CREATE TABLE `ramverk1_comments`
(
  `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `user` VARCHAR(100) NOT NULL,
  `parentId` INT,
  `text` text,
  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (`user`) REFERENCES `ramverk1_users` (`name`),
  FOREIGN KEY (`parentId`) REFERENCES `ramverk1_posts` (`parentId`)
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


INSERT INTO `ramverk1_comments` (`user`, `type`, `parentId`, `title`, `tags`, `text`) VALUES
    ("kalle", "question", 0, "Fråga om kaffekoppar", "#mugg,#kaffe", "Hej bör kaffe drickas ur tjocka koppar eller smala? Vad gillar ni mest? Personligen så föredrar jag smala."),

    ("sven", "answer", 1, "", "", "Bra fråga, troligtvis något många glömmer att tänka på. Jag har bara tjocka kaffekoppar hemma."),

    ("kalle", "comment", 2, "", "", "Ok tack för ditt svar"),

    ("sven", "comment", 1, "", "",  "En kommentar till din fråga men ej svar"),

    ("kalle", "comment", 1, "", "",  "Tack för din kommentar på min fråga"),

    ("sven", "question", 0, "En fråga", "#mugg,#kaffe", "En fråga");



-- ------------------------------------------------------------------------
