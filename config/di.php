<?php
/**
 * Configuration file for DI container.
 */
return [
    // Services to add to the container.
    "services" => [
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
                $obj = new \Nicklas\Page\PageRender();
                $obj->setDI($this);
                return $obj;
            }
        ],
        // "db" => [
        //     "shared" => true,
        //     "callback" => function () {
        //         $obj = new \Anax\Database\DatabaseQueryBuilder();
        //         $obj->configure("database.php");
        //         return $obj;
        //     }
        // ],
    ],
];
