
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
DROP TABLE IF EXISTS `coffee_votes`;
DROP TABLE IF EXISTS `coffee_comments`;
DROP TABLE IF EXISTS `coffee_posts`;
DROP TABLE IF EXISTS `coffee_questions`;
DROP TABLE IF EXISTS `coffee_users`;
-- ------------------------------------------------------------------------

CREATE TABLE `coffee_users`
(
  `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `email` VARCHAR(100),
  `pass` VARCHAR(255) NOT NULL,
  `authority` VARCHAR(255) NOT NULL,
  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `question` text
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;


CREATE TABLE `coffee_questions`
(
  `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `user` VARCHAR(100) NOT NULL,
  `title` text,
  `tags` text,
  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `status` VARCHAR(20) DEFAULT 'active',

  FOREIGN KEY (`user`) REFERENCES `coffee_users` (`name`)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

CREATE TABLE `coffee_posts`
(
  `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `questionId` int,
  `accepted` VARCHAR(100) DEFAULT 'no',
  `user` VARCHAR(100) NOT NULL,
  `type` text, -- question or answer
  `text` text,
  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (`user`) REFERENCES `coffee_users` (`name`),
  FOREIGN KEY (`questionId`) REFERENCES `coffee_questions` (`id`)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;


CREATE TABLE `coffee_comments`
(
  `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `user` VARCHAR(100) NOT NULL,
  `parentId` INT,
  `text` text,
  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (`user`) REFERENCES `coffee_users` (`name`),
  FOREIGN KEY (`parentId`) REFERENCES `coffee_posts` (`id`)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;


CREATE TABLE `coffee_votes`
(
  `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `user` VARCHAR(100) NOT NULL,
  `parentId` int,
  `parentType` text, -- comment/question/answer
  `upVote` INT,
  `downVote` INT,

  FOREIGN KEY (`user`) REFERENCES `coffee_users` (`name`)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;


SELECT * FROM `coffee_posts`;
SELECT * FROM `coffee_questions` WHERE id = 1;
SELECT * FROM `coffee_posts` WHERE id = 2;




-- ------------------------------------------------------------------------
--
-- INSERT SETUP
--
-- ------------------------------------------------------------------------

-- USERS
-- ----------------------------------------------------------------------

INSERT INTO `coffee_users` (`name`, `email`, `pass`, `authority`, `question`) VALUES
    ("anders", "litemerafrukt@gmail.com", "$2y$10$ZnLcSoDWObj8LNwC4wXk4Ou.4vfgbitGTwqEK0p35vltXhbQmYm0W", "user", "lasagne"),
    ("aurora", "nhdandersson@gmail.com", "$2y$10$ZnLcSoDWObj8LNwC4wXk4Ou.4vfgbitGTwqEK0p35vltXhbQmYm0W", "user", "lasagne"),
    ("marcus", "marcusgu@hotmail.com", "$2y$10$ZnLcSoDWObj8LNwC4wXk4Ou.4vfgbitGTwqEK0p35vltXhbQmYm0W", "user", "lasagne"),
    ("peder", "peder.tornberg@gmail.com", "$2y$10$ZnLcSoDWObj8LNwC4wXk4Ou.4vfgbitGTwqEK0p35vltXhbQmYm0W", "user", "lasagne"),
    ("magnus", "magnusandersson076@gmail.com", "$2y$10$ZnLcSoDWObj8LNwC4wXk4Ou.4vfgbitGTwqEK0p35vltXhbQmYm0W", "user", "lasagne"),
    ("mos", "mos@dbwebb.se", "$2y$10$ZnLcSoDWObj8LNwC4wXk4Ou.4vfgbitGTwqEK0p35vltXhbQmYm0W", "user", "lasagne"),
    ("nicklas", "nicklas766@live.se", "$2y$10$ZnLcSoDWObj8LNwC4wXk4Ou.4vfgbitGTwqEK0p35vltXhbQmYm0W", "user", "lasagne"),
    ("user", "admin@admin.com", "$2y$10$ZnLcSoDWObj8LNwC4wXk4Ou.4vfgbitGTwqEK0p35vltXhbQmYm0W", "user", "lasagne"),
	("admin", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "admin", "lasagne");

    -- mos@dbwebb.se
    -- nhdandersson@gmail.com
    -- niso16@student.bth.se
    -- marcusgu@hotmail.com
    -- peder.tornberg@gmail.com
    -- magnusandersson076@gmail.com





-- "Funktionell programmering! Har ni sett de senaste uppdateringarna i javascript?"
-- "Hej, du har nog råkat besöka fel hemsida, jag tror du söker.."

-- QUESTIONS 1
-- ----------------------------------------------------------------------

INSERT INTO `coffee_questions` (`user`, `title`, `tags`) VALUES
    ("aurora", "Fråga om kaffe koppar", "mugg,kaffe");

INSERT INTO `coffee_posts` (`questionId`, `user`, `type`, `text`, `accepted`) VALUES
    (1, "aurora", "question", "Hej bör kaffe drickas ur tjocka koppar eller smala? Vad gillar ni mest? Personligen så föredrar jag smala.", "no"),
    (1, "marcus", "answer", "Bra fråga, troligtvis något många glömmer att tänka på. Jag har bara tjocka kaffekoppar hemma.", "no"),
    (1, "magnus", "answer", "Personligen så föredrar jag att variera, varför använda endast en?", "yes");

INSERT INTO `coffee_comments` (`parentId`, `user`, `text`) VALUES
    (2, "aurora", "Ok tack för ditt svar Marcus!"),
    (3, "marcus", "Hmm, du har en poäng jag ska köpa några smala koppar idag.");

INSERT INTO `coffee_votes` (`user`, `parentId`, `parentType`, `upVote`, `downVote`) VALUES
    -- auroras question
    ("anders", 1, "post", 1, null),
    ("magnus", 1, "post", 1, null),
    -- marcus answer
    ("aurora", 2, "post", 1, null),
    ("anders", 2, "post", 1, null),
    ("magnus", 2, "post", null, 1),
    -- magnus answer
    ("marcus", 3, "post", 1, null),
    ("anders", 3, "post", 1, null),
    ("aurora", 3, "post", 1, null),
    -- marcus comment;
    ("anders", 2, "comment", 1, null),
    ("magnus", 2, "comment", 1, null);


-- QUESTIONS 2
-- ----------------------------------------------------------------------
INSERT INTO `coffee_questions` (`user`, `title`, `tags`) VALUES
    ("nicklas", "Vilken tésort bör jag köpa?", "te,tesort");

INSERT INTO `coffee_posts` (`questionId`, `user`, `type`, `text`) VALUES
    (2, "nicklas", "question", "Hej alla! Vilken tésort bör jag köpa? Gärna att den är rik med antioxidanter."),
    (2, "peder", "answer", "Jag gillar roobius, köp den!");

INSERT INTO `coffee_comments` (`parentId`, `user`, `text`) VALUES
    (4, "mos", "Jag älskar té, men detta är fel hemsida.."),
    (5, "mos", "uppmuntra honom inte..");

INSERT INTO `coffee_votes` (`user`, `parentId`, `parentType`, `upVote`, `downVote`) VALUES
    -- nicklas question
    ("anders", 4, "post", null, 1),
    ("magnus", 4, "post", null, 1),
    ("peder",  4, "post", 1, null),
    ("mos",    4, "post", null, 1),
    -- peders answer
    ("mos",    5, "post", null, 1),
    ("aurora", 5, "post", null, 1),
    -- mos comment 1
    ("magnus", 3, "comment", 1, null),
    ("anders", 3, "comment", 1, null),
    ("marcus", 3, "comment", 1, null),
    -- mos comment 2
    ("magnus", 4, "comment", 1, null);




-- QUESTIONS 3
-- ----------------------------------------------------------------------
INSERT INTO `coffee_questions` (`user`, `title`, `tags`) VALUES
    ("peder", "Mörkrost eller mellan?", "kaffe,morkrost,mellanrost");

INSERT INTO `coffee_posts` (`questionId`, `user`, `type`, `text`) VALUES
    (3, "peder", "question", "###Mörkröst eller ljusrost?
Hej alla! Motivera gärna vad du tycker och varför den är bäst"),
    (3, "aurora", "answer", "Jag föredrar mellanrost, det ger en bra stark smak (men ej för mycket), jag använder
    [denna](https://www.arvidnordquist.se/kaffe/sortiment/mellan/)"),
    (3, "magnus", "answer", "Det beror på, om det är höst eller vinter så blir det mörkrost. Annars är det mellanrost som gäller."),
    (3, "mos", "answer", "Alltid mörkrost, det finns inget annat val för mig.");

INSERT INTO `coffee_comments` (`parentId`, `user`, `text`) VALUES
    (6, "marcus", "bra och intressant fråga! :)"),
    (7, "admin", "Bra svar, men ingen reklam på hemsidan tack."),
    (9, "magnus", "Hmm, jag förstår vad du menar, körde också bara mörkrost ett tag.");

INSERT INTO `coffee_votes` (`user`, `parentId`, `parentType`, `upVote`, `downVote`) VALUES
    -- peders question
    ("marcus", 6, "post", 1, null),
    -- auroras answer
    ("magnus", 7, "post", null, 1),
    ("admin", 7, "post", null, 1),
    -- mos answer
    ("magnus",  9, "post", 1, null);


-- QUESTIONS 4
-- ----------------------------------------------------------------------
INSERT INTO `coffee_questions` (`user`, `title`, `tags`) VALUES
    ("anders", "Funktionellt kaffe?", "kaffe,morkrost,funktionellt");

INSERT INTO `coffee_posts` (`questionId`, `user`, `type`, `text`, `accepted`) VALUES
    (4, "anders", "question", "##Funktionell dryck
Jag satt och funderade är inte kaffe en väldigt funktionell dryck? Jag blir riktigt pigg!", "no"),
    (4, "mos", "answer", "*anders...* men ja, man blir pigg på grund av koffeinet i kaffet.", "yes");

INSERT INTO `coffee_comments` (`parentId`, `user`, `text`) VALUES
    (11, "anders", "Haha, okej tack för ditt svar, accepterade svaret."),
    (11, "mos", "Ok tack! :)");

INSERT INTO `coffee_votes` (`user`, `parentId`, `parentType`, `upVote`, `downVote`) VALUES
    -- anders question
    ("mos", 10, "post", null, 1);


-- QUESTIONS 5
-- ----------------------------------------------------------------------
INSERT INTO `coffee_questions` (`user`, `title`, `tags`) VALUES
    ("marcus", "Ok att dricka kaffe på kvällen?", "kvallen,koffein,farligt");

INSERT INTO `coffee_posts` (`questionId`, `user`, `type`, `text`, `accepted`) VALUES
    (5, "marcus", "question", "Hej! Jag dricker nu kaffe på kvällen, men funderar om det är okej? Eller inte bra alls att göra det?", "no"),
    (5, "anders", "answer", "Köp koffeinfritt, så dricker du det koffeinfria på kvällen 100% succé!", "yes"),
    (5, "nicklas", "answer", "Jag brukar dricka kaffe på dagen och té på kvällen", "no");

INSERT INTO `coffee_comments` (`parentId`, `user`, `text`) VALUES
    (12, "peder", "Otroligt bra fråga, konstigt att ingen frågat förut."),
    (12, "magnus", "Bra fråga Marcus!"),
    (13, "aurora", "Haha roligt svar, men ändå sant! 1+"),
    (14, "marcus", "Du har en poäng men vi är en kaffe community..");

INSERT INTO `coffee_votes` (`user`, `parentId`, `parentType`, `upVote`, `downVote`) VALUES
    -- marcus question
    ("mos", 12, "post", 1, null),
    ("anders", 12, "post", 1, null),
    ("magnus", 12, "post", 1, null),
    ("peder", 12, "post", 1, null),
    -- anders answer
    ("aurora", 13, "post", 1, null),
    ("mos", 13, "post", null, 1),
    ("magnus", 13, "post", 1, null),
    ("marcus", 13, "post", 1, null),
    -- nicklas answer
    ("marcus", 14, "post", null, 1),
    ("aurora", 14, "post", null, 1),
    -- peder comment
    ("magnus", 10, "comment", 1, null),
    ("marcus", 10, "comment", 1, null),
    ("aurora", 10, "comment", 1, null);
-- KOFFEINFRITT? så kan man dricka på kvällen
-- ------------------------------------------------------------------------

-- INSERT INTO `coffee_votes` (`user`, `parentId`, `parentType`, `upVote`, `downVote`) VALUES
--                 ("aurora", 2, "post", 1, null),
--                 ("marcus", 2, "post", null, 1),
--                 ("anders", 2, "post", null, 1),
--                 ("peder", 2, "post", null, 1),
--                 ("aurora", 1, "comment", null, 1),
--                 ("marcus", 1, "comment", 1, null),
--                 ("anders", 1, "comment", 1, null),
--                 ("peder", 1, "comment", 1, null);
