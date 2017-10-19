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
        $user = new Users($this->di->get("db"));
        $users = $user->getUsers();
        $views = [
            ["user/pre/create", ["form" => $form->getHTML()], "main"]
        ];

        $this->di->get("pageRenderComment")->renderPage([
            "views" => $views,
            "title" => "Create User"
        ]);
    }
}
