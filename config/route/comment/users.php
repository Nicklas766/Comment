<?php
/**
 * Routes for the commentController
 */
return [
    "routes" => [
        [
            "info" => "Comments index page",
            "requestMethod" => null,
            "path" => "",
            "callable" => ["commentFrontController", "getAllUsersIndex"],
        ],
        [
            "info" => "Comments index page",
            "requestMethod" => "get|post",
            "path" => "create",
            "callable" => ["commentFrontController", "getPostCreateQuestion"],
        ],
    ]
];
