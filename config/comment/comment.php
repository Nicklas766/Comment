<?php
/**
 * Routes for the commentController
 */
return [
    "routes" => [
        [
            "info" => "Comments index page",
            "requestMethod" => "get|post",
            "path" => "",
            "callable" => ["commentFrontController", "getIndex"],
        ],
        [
            "info" => "Update an comment",
            "requestMethod" => "get|post",
            "path" => "edit/{id:digit}",
            "callable" => ["commentFrontController", "getPostEditComment"],
        ],
    ]
];
