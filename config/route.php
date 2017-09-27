<?php
/**
 * Configuration file for routes.
 */
return [
    // Load these routefiles in order specified and optionally mount them
    // onto a base route.
    "routeFiles" => [
        [
            // Routers for the user parts mounts on user/
            "mount" => "user",
            "file" => __DIR__ . "/route/comment/user.php",
        ],
        [
            // Routers for the user parts mounts on comment/
            "mount" => "comment",
            "file" => __DIR__ . "/route/comment/comment.php",
        ],
        [
            // Routers for the user parts mounts on admin/
            "mount" => "admin",
            "file" => __DIR__ . "/route/comment/admin.php",
        ],
    ],
];
