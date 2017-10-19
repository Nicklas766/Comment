<?php

namespace Nicklas\Comment;

// MODULES
use \Nicklas\Comment\Modules\User;

/**
 * A controller class.
 */
class UserController extends ProfileController
{

    /**
     * Create page for all users overview
     *
     * @return void
     */
    public function getAllUsersIndex()
    {
        $user = new User($this->di->get("db"));
        $users = $user->getAllUsers();

        $views = [
            ["comment/users/view-all", ["users" => $users], "main"]
        ];

        $this->di->get("pageRenderComment")->renderPage([
            "views" => $views,
            "title" => "Coffee users"
        ]);
    }

    /**
     * Create page for all users overview
     *
     * @return void
     */
    public function getUserIndex($name)
    {
        $user = new User($this->di->get("db"));
        $user = $user->getUser($name);

        $views = [
            ["comment/users/view-user", ["user" => $user], "main"]
        ];

        $this->di->get("pageRenderComment")->renderPage([
            "views" => $views,
            "title" => "User | $user->name"
        ]);
    }
}
