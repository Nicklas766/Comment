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
        "activeRecordModelExtender" => [
            "shared" => false,
            "callback" => function () {
                $obj = new \Nicklas\Comment\ActiveRecordModelExtender();
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
                // USERS
                $sql = '
                CREATE TABLE `ramverk1_users`
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

                // COMMENT TABLE
                $sql = 'CREATE TABLE `ramverk1_comments`
                (
                    `id` INTEGER PRIMARY KEY NOT NULL,
                    `user` VARCHAR(100) NOT NULL,
                    `type` VARCHAR(100) NOT NULL,
                    `parentId` INT,
                    `title` text,
                    `tags` text,
                    `text` text,
                    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    `status` VARCHAR(20) DEFAULT active,
                    FOREIGN KEY (`user`) REFERENCES `ramverk1_users` (`name`)
                )';
                $obj->execute($sql);




                $sql = 'INSERT INTO `ramverk1_users` (`name`, `email`, `pass`, `authority`, `question`) VALUES
                    ("admin", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "admin", "lasagne"),
                    ("kalle", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "user", "lasagne"),
                    ("sven", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "user", "lasagne"),
                    ("user", "admin@admin.com", "$2y$10$Oo8aC.3U9NlfrSBO3W5bG.jByboAvCRA/UuTwAx9uJOb5BlOVh0xC", "user", "lasagne")';
                $obj->execute($sql);
                $sql = 'INSERT INTO `ramverk1_comments` (`user`, `type`, `parentId`, `title`, `tags`, `text`) VALUES
                    ("kalle", "question", 0, "Fråga om kaffekoppar", "#mugg,#kaffe", "Hej bör kaffe drickas ur tjocka koppar eller smala? Vad gillar ni mest? Personligen så föredrar jag smala."),
                    ("sven", "answer", 1, "", "", "Bra fråga, troligtvis något många glömmer att tänka på. Jag har bara tjocka kaffekoppar hemma."),
                    ("kalle", "comment", 2, "", "", "Ok tack för ditt svar"),
                    ("sven", "comment", 1, "", "",  "En kommentar till din fråga men ej svar"),
                    ("kalle", "comment", 1, "", "",  "Tack för din kommentar på min fråga"),
                    ("sven", "question", 0, "En fråga", "#mörkrost,#kaffe", "En fråga")';
                $obj->execute($sql);


                return $obj;
            }
        ],
    ],
];
