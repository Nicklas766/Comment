
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
DROP TABLE IF EXISTS `ramverk1_posts`;
DROP TABLE IF EXISTS `ramverk1_questions`;
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
  `questionId` int,
  `user` VARCHAR(100) NOT NULL,
  `type` text, -- question or answer
  `text` text,
  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (`user`) REFERENCES `ramverk1_users` (`name`),
  FOREIGN KEY (`questionId`) REFERENCES `ramverk1_questions` (`id`)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;


CREATE TABLE `ramverk1_comments`
(
  `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `user` VARCHAR(100) NOT NULL,
  `parentId` INT,
  `text` text,
  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (`user`) REFERENCES `ramverk1_users` (`name`),
  FOREIGN KEY (`parentId`) REFERENCES `ramverk1_posts` (`id`)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;




-- ------------------------------------------------------------------------
--
-- INSERT SETUP
--
-- ------------------------------------------------------------------------

-- USERS
-- ----------------------------------------------------------------------

INSERT INTO `ramverk1_users` (`name`, `email`, `pass`, `authority`, `question`) VALUES
    ("admin", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "admin", "lasagne"),
    ("nicklas", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "user", "lasagne"),
    ("anders", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "user", "lasagne"),
    ("kalle", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "user", "lasagne"),
    ("sven", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "user", "lasagne"),
    ("jessica", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "user", "lasagne"),
    ("user", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "user", "lasagne");


-- "Funktionell programmering! Har ni sett de senaste uppdateringarna i javascript?"
-- "Hej, du har nog råkat besöka fel hemsida, jag tror du söker.."

-- QUESTIONS 1
-- ----------------------------------------------------------------------

INSERT INTO `ramverk1_questions` (`user`, `title`, `tags`) VALUES
    ("kalle", "Fråga om kaffe koppar", "#mugg,#kaffe");

INSERT INTO `ramverk1_posts` (`questionId`, `user`, `type`, `text`) VALUES
    (1, "kalle", "question", "Hej bör kaffe drickas ur tjocka koppar eller smala? Vad gillar ni mest? Personligen så föredrar jag smala."),
    (1, "sven", "answer", "Bra fråga, troligtvis något många glömmer att tänka på. Jag har bara tjocka kaffekoppar hemma."),
    (1, "jessica", "answer", "Personligen så föredrar jag att variera, varför använda endast en?");


INSERT INTO `ramverk1_comments` (`parentId`, `user`, `text`) VALUES
    (2, "kalle", "Ok tack för ditt svar Sven!"),
    (3, "sven", "Hmm, du har en poäng jag ska köpa några smala koppar idag.");

-- QUESTIONS 2
-- ----------------------------------------------------------------------
INSERT INTO `ramverk1_questions` (`user`, `title`, `tags`) VALUES
    ("nicklas", "Vilken tésort bör jag köpa?", "#té,#tésort");

INSERT INTO `ramverk1_posts` (`questionId`, `user`, `type`, `text`) VALUES
    (2, "nicklas", "question", "Hej alla! Vilken tésort bör jag köpa? Gärna att den är rik med antioxidanter."),
    (2, "sven", "answer", "Jag gillar roobius, köp den!");

INSERT INTO `ramverk1_comments` (`parentId`, `user`, `text`) VALUES
    (4, "kalle", "Jag älskar té, men detta är fel hemsida.."),
    (5, "kalle", "uppmuntra honom inte..");

-- ------------------------------------------------------------------------
