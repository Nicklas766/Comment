<?php
/**
 * Configuration file for DI container.
 */
return [

    // Services to add to the container.
    "services" => [
        "request" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Anax\Request\Request();
                $obj->init();
                return $obj;
            }
        ],
        "response" => [
            "shared" => true,
            //"callback" => "\Anax\Response\Response",
            "callback" => function () {
                $obj = new \Anax\Response\ResponseUtility();
                $obj->setDI($this);
                return $obj;
            }
        ],
        "url" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Anax\Url\Url();
                $request = $this->get("request");
                $obj->setSiteUrl($request->getSiteUrl());
                $obj->setBaseUrl($request->getBaseUrl());
                $obj->setStaticSiteUrl($request->getSiteUrl());
                $obj->setStaticBaseUrl($request->getBaseUrl());
                $obj->setScriptName($request->getScriptName());
                $obj->configure("url.php");
                $obj->setDefaultsFromConfiguration();
                return $obj;
            }
        ],
        "router" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Anax\Route\Router();
                $obj->setDI($this);
                $obj->configure("route.php");
                return $obj;
            }
        ],
        "view" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Anax\View\ViewCollection();
                $obj->setDI($this);
                $obj->configure("view.php");
                return $obj;
            }
        ],
        "viewRenderFile" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Anax\View\ViewRenderFile2();
                $obj->setDI($this);
                return $obj;
            }
        ],
        "session" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Anax\Session\SessionConfigurable();
                $obj->configure("session.php");
                $obj->start();
                return $obj;
            }
        ],
        "textfilter" => [
            "shared" => true,
            "callback" => "\Anax\TextFilter\TextFilter",
        ],
        "pageRender" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Anax\Page\PageRender();
                $obj->setDI($this);
                return $obj;
            }
        ],
        "errorController" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Anax\Page\ErrorController();
                $obj->setDI($this);
                return $obj;
            }
        ],
        "debugController" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Anax\Page\DebugController();
                $obj->setDI($this);
                return $obj;
            }
        ],
        "flatFileContentController" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Anax\Page\FlatFileContentController();
                $obj->setDI($this);
                return $obj;
            }
        ],
        "commentFrontController" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Nicklas\Comment\FrontController();
                $obj->setDI($this);
                return $obj;
            }
        ],
        "pageRenderComment" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Nicklas\Page\PageRenderMock();
                $obj->setDI($this);
                return $obj;
            }
        ],
        "pageRenderComment2" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Nicklas\Page\PageRender();
                $obj->setDI($this);
                return $obj;
            }
        ],
        "db" => [
            "shared" => false,
            "callback" => function () {
                $obj = new \Anax\Database\DatabaseQueryBuilder();
                $obj->configure("sqlitedatabase.php");
                $obj->connect();



                // SETUP ALL TABLES
                // USER
                $sql = 'CREATE TABLE `ramverk1_users`
                (
                  `id` INTEGER PRIMARY KEY NOT NULL,
                  `name` VARCHAR(100) NOT NULL UNIQUE,
                  `email` VARCHAR(100),
                  `pass` VARCHAR(255) NOT NULL,
                  `authority` VARCHAR(255) NOT NULL,
                  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                  `question` text
                )';
                $obj->execute($sql);

                // QUESTIONs TABLE
                $sql = 'CREATE TABLE `ramverk1_questions`
                (
                  `id` INTEGER PRIMARY KEY NOT NULL,
                  `user` VARCHAR(100) NOT NULL,
                  `acceptedId` int,
                  `title` text,
                  `tags` text,
                  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                  `status` VARCHAR(20) DEFAULT active,

                  FOREIGN KEY (`user`) REFERENCES `ramverk1_users` (`name`)
                )';
                $obj->execute($sql);

                // POSTS TABLE
                $sql = 'CREATE TABLE `ramverk1_posts`
                (
                  `id` INTEGER PRIMARY KEY NOT NULL,
                  `questionId` int,
                  `accepted` VARCHAR(100) DEFAULT "no",
                  `user` VARCHAR(100) NOT NULL,
                  `type` text, -- question or answer
                  `text` text,
                  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                  FOREIGN KEY (`user`) REFERENCES `ramverk1_users` (`name`),
                  FOREIGN KEY (`questionId`) REFERENCES `ramverk1_questions` (`id`)
                )';
                $obj->execute($sql);


                // POSTS TABLE
                $sql = 'CREATE TABLE `ramverk1_comments`
                (
                  `id` INTEGER PRIMARY KEY NOT NULL,
                  `user` VARCHAR(100) NOT NULL,
                  `parentId` INT,
                  `text` text,
                  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                  FOREIGN KEY (`user`) REFERENCES `ramverk1_users` (`name`),
                  FOREIGN KEY (`parentId`) REFERENCES `ramverk1_posts` (`id`)
                )';
                $obj->execute($sql);


                // POSTS TABLE
                $sql = 'CREATE TABLE `ramverk1_votes`
                (
                  `id` INTEGER PRIMARY KEY NOT NULL,
                  `user` VARCHAR(100) NOT NULL,
                  `parentId` int,
                  `parentType` text, -- comment/question/answer
                  `upVote` INT,
                  `downVote` INT,

                  FOREIGN KEY (`user`) REFERENCES `ramverk1_users` (`name`)
                )';
                $obj->execute($sql);




                $sql = 'INSERT INTO `ramverk1_users` (`name`, `email`, `pass`, `authority`, `question`) VALUES
                    ("admin", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "admin", "lasagne"),
                    ("nicklas766", "nicklas766@live.se", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "user", "lasagne"),
                    ("anders", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "user", "lasagne"),
                    ("kalle", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "user", "lasagne"),
                    ("sven", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "user", "lasagne"),
                    ("jessica", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "user", "lasagne"),
                    ("user", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "user", "lasagne")';
                $obj->execute($sql);

                // --------------------------------------------- QUESTIONs 1
                $sql = 'INSERT INTO `ramverk1_questions` (`user`, `title`, `tags`) VALUES
                    ("kalle", "Fråga om kaffe koppar", "mugg,kaffe")';
                $obj->execute($sql);

                $sql = 'INSERT INTO `ramverk1_posts` (`questionId`, `user`, `type`, `text`) VALUES
                    (1, "kalle", "question", "Hej bör kaffe drickas ur tjocka koppar eller smala? Vad gillar ni mest? Personligen så föredrar jag smala."),
                    (1, "sven", "answer", "Bra fråga, troligtvis något många glömmer att tänka på. Jag har bara tjocka kaffekoppar hemma."),
                    (1, "jessica", "answer", "Personligen så föredrar jag att variera, varför använda endast en?")';
                $obj->execute($sql);

                $sql = 'INSERT INTO `ramverk1_comments` (`parentId`, `user`, `text`) VALUES
                    (2, "kalle", "Ok tack för ditt svar Sven!"),
                    (3, "sven", "Hmm, du har en poäng jag ska köpa några smala koppar idag.")';
                $obj->execute($sql);

                // --------------------------------------------- QUESTIONs 2
                $sql = 'INSERT INTO `ramverk1_questions` (`user`, `title`, `tags`) VALUES
                    ("nicklas766", "Vilken tésort bör jag köpa?", "té,tésort,kaffe")';
                $obj->execute($sql);

                $sql = 'INSERT INTO `ramverk1_posts` (`questionId`, `user`, `type`, `text`) VALUES
                    (2, "nicklas766", "question", "Hej alla! Vilken tésort bör jag köpa? Gärna att den är rik med antioxidanter."),
                    (2, "sven", "answer", "Jag gillar roobius, köp den!")';
                $obj->execute($sql);

                $sql = 'INSERT INTO `ramverk1_comments` (`parentId`, `user`, `text`) VALUES
                    (4, "kalle", "Jag älskar té, men detta är fel hemsida.."),
                    (5, "kalle", "uppmuntra honom inte..")';
                $obj->execute($sql);


                // --------------------------------------------- LIKES
                $sql = 'INSERT INTO `ramverk1_votes` (`user`, `parentId`, `parentType`, `upVote`, `downVote`) VALUES
                    ("kalle", 1, "post", 1, null),
                    ("sven", 1, "post", null, 1),
                    ("anders", 1, "post", null, 1),
                    ("jessica", 1, "post", null, 1),
                    ("kalle", 1, "comment", null, 1),
                    ("sven", 1, "comment", 1, null),
                    ("anders", 1, "comment", 1, null),
                    ("jessica", 1, "comment", 1, null)';
                $obj->execute($sql);


                return $obj;
            }
        ],
    ],
];
