<?php

namespace Nicklas\Comment;

use \Nicklas\Comment\HTMLForm\Admin\EditUserForm;
use \Nicklas\Comment\HTMLForm\Admin\CreateUserForm2;

// MODULES
use \Nicklas\Comment\Modules\User;

/**
 * A controller class.
 */
class AdminController extends UserController
{

    /**
     * Get all users
     *
     * @return array
     */
    public function getUsers()
    {
        $user = new User($this->di->get("db"));
        return $user->getAllUsers();
    }

    /**
     * check if user is logged in
     *
     * @return void
     */
    public function checkIsAdmin()
    {
        $this->checkIsLogin();

        $user = new User($this->di->get("db"));
        $user = $user->getUser($this->di->get("session")->get("user"));

        if ($user->authority != "admin") {
            $views = [
                ["comment/admin/fail", [], "main"]
            ];
            $this->di->get("pageRenderComment")->renderPage([
                "views" => $views,
                "title" => "Not authorized"
            ]);
        }
    }


    /**
     * Description.
     *
     * @return void
     */
    public function getUsersIndex()
    {
        $views = [
            ["comment/admin/navbar", [], "main"],
            ["comment/admin/crud/view-all", ["users" => $this->getUsers()], "main"]
        ];

        $this->di->get("pageRenderComment")->renderPage([
            "views" => $views,
            "title" => "A collection of users"
        ]);
    }


    /**
     * Description.
     *
     * @param int $id
     *
     * @return void
     */
    public function getPostAdminEditUser($id)
    {
        $form = new EditUserForm($this->di, $id);
        $form->check();

        $views = [
            ["comment/admin/navbar", [], "main"],
            ["comment/admin/crud/edit", ["form" => $form->getHTML()], "main"]
        ];

        $this->di->get("pageRenderComment")->renderPage([
            "views" => $views,
            "title" => "Edit user"
        ]);
    }

    /**
     * Description.
     *
     * @return void
     */
    public function getPostAdminCreateUser()
    {
        $form = new CreateUserForm2($this->di);
        $form->check();

        $views = [
            ["comment/admin/navbar", [], "main"],
            ["comment/admin/crud/edit", ["form" => $form->getHTML()], "main"]
        ];

        $this->di->get("pageRenderComment")->renderPage([
            "views" => $views,
            "title" => "Create user"
        ]);
    }
}
