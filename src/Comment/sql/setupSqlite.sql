DROP TABLE IF EXISTS `ramverk1_comments`;
DROP TABLE IF EXISTS `ramverk1_users`;
CREATE TABLE `ramverk1_users`
(
  `id` INTEGER PRIMARY KEY NOT NULL,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `email` VARCHAR(100),
  `pass` VARCHAR(255) NOT NULL,
  `authority` VARCHAR(255) NOT NULL
);
CREATE TABLE `ramverk1_comments`
(
  `id` INTEGER PRIMARY KEY NOT NULL,
  `user` VARCHAR(100) NOT NULL,
  `comment` text
);
INSERT INTO `ramverk1_users` (`id`, `name`, `email`, `pass`, `authority`) VALUES
    (1, "admin", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "admin"),
    (2, "user", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "user");
INSERT INTO `ramverk1_comments` (`id`, `user`, `comment`) VALUES
    (1, "admin", "comment"),
    (2, "user", "comment2");
