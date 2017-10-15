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
            "callable" => ["commentFrontController", "getIndex"],
        ],
        [
            "info" => "Comments index page",
            "requestMethod" => "get|post",
            "path" => "create",
            "callable" => ["commentFrontController", "getPostCreateQuestion"],
        ],

        // View question, also post answer
        [
            "info" => "Comments index page",
            "requestMethod" => "get|post",
            "path" => "{id:digit}",
            "callable" => ["commentFrontController", "getPostQuestionAnswer"],
        ],
        [
            "info" => "Update an comment",
            "requestMethod" => "get|post",
            "path" => "edit/{id:digit}",
            "callable" => ["commentFrontController", "getPostEditComment"],
        ],
    ]
];
