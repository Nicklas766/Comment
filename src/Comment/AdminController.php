<?php

namespace Nicklas\Comment;

use \Nicklas\Comment\HTMLForm\EditUserForm;
use \Nicklas\Comment\HTMLForm\CreateUserForm2;

/**
 * A controller class.
 */
class AdminController extends UserController
{

    /**
     * Get all users
     *
     *
     * @return array
     */
    public function getUsers()
    {
        $user = new User();
        $user->setDb($this->di->get("db"));
        $users = $user->findAll();

        return array_map(function ($user) {
            $user->setGravatar($user->email);

            return $user;
        }, $users);
    }

    /**
     * check if user is logged in
     *
     * @return void
     */
    public function checkIsAdmin()
    {
        $this->checkIsLogin();

        $user = new User();
        $user->setDb($this->di->get("db"));
        $user = $user->find("name", $this->di->get("session")->get("user"));

        if ($user->authority != "admin") {
            $views = [
                ["admin/fail", [], "main"]
            ];
            $this->renderPage($views, "Not authorized");
        }
    }


    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return void
     */
    public function getUsersIndex()
    {
        $views = [
            ["admin/navbar", [], "main"],
            ["admin/crud/view-all", ["users" => $this->getUsers()], "main"]
        ];

        $this->di->get("pageRenderComment")->renderPage([
            "views" => $views,
            "title" => "A collection of items"
        ]);
    }


    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return void
     */
    public function getPostAdminEditUser($id)
    {
        $form = new EditUserForm($this->di, $id);
        $form->check();

        $views = [
            ["admin/navbar", [], "main"],
            ["admin/crud/edit", ["form" => $form->getHTML()], "main"]
        ];

        $this->renderPage($views, "A login page");
    }

    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return void
     */
    public function getPostAdminCreateUser()
    {
        $form = new CreateUserForm2($this->di);
        $form->check();

        $views = [
            ["admin/navbar", [], "main"],
            ["admin/crud/edit", ["form" => $form->getHTML()], "main"]
        ];

        $this->renderPage($views, "A login page");
    }
}
