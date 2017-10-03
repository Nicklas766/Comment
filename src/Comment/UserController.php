<?php

namespace Nicklas\Comment;

use \Anax\Configure\ConfigureInterface;
use \Anax\Configure\ConfigureTrait;
use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;
use \Nicklas\Comment\HTMLForm\UserLoginForm;
use \Nicklas\Comment\HTMLForm\CreateUserForm;
use \Nicklas\Comment\HTMLForm\EditProfileForm;

/**
 * A controller class.
 */
class UserController implements
    ConfigureInterface,
    InjectionAwareInterface
{
    use ConfigureTrait, InjectionAwareTrait;



    /**
     * @var $data description
     */
    //private $data;



    /**
     * Get details on item to load form with.
     *
     * @param integer $id get details on item with id.
     *
     * @return object true if okey, false if something went wrong.
     */
    public function getUserDetails($name)
    {
        $user = new User();
        $user->setDb($this->di->get("db"));
        $user->find("name", $name);
        $user->setGravatar();
        return $user;
    }

    /**
     * Logout user by setting "user" == null in session.
     *
     *
     * @return void
     */
    public function logout()
    {
        $this->di->get('session')->set("user", null);
        $this->di->get("response")->redirect("user/login");
    }


    /**
     * Render page for users
     *
     * @return void
     */
    public function renderPage($views, $title)
    {
        $this->di->get("pageRenderComment")->renderPage([
            "views" => $views,
            "title" => "$title"
        ]);
    }


    /**
     * check if user is logged in
     *
     * @return void
     */
    public function checkIsLogin()
    {
        if (!$this->di->get("session")->has("user")) {
            $views = [
                ["user/fail", [], "main"]
            ];
            $this->renderPage($views, "Not logged in");
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
    public function getPostLogin()
    {
        $form       = new UserLoginForm($this->di);
        $form->check();

        $views = [
            ["user/login", ["form" => $form->getHTML()], "main"]
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
    public function getPostCreateUser()
    {
        $form       = new CreateUserForm($this->di);
        $form->check();

        $views = [
            ["user/create", ["form" => $form->getHTML()], "main"]
        ];

        $this->renderPage($views, "Create a user");
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
    public function getPostEditUser()
    {
        $name = $this->di->get('session')->get("user");
        $user = $this->getUserDetails($name);

        $form       = new EditProfileForm($this->di, $name);
        $form->check();

        $views = [
            ["user/profile/edit", ["form" => $form->getHTML(), "user" => $user], "main"]
        ];

        $this->renderPage($views, "A create user page");
    }


    /**
     * Render profile page
     *
     * @return void
     */
    public function renderProfile()
    {
        $this->checkIsLogin();

        $name = $this->di->get('session')->get("user");
        $user = $this->getUserDetails($name);


        $views = [
            ["user/profile/profile", ["user" => $user], "main"]
        ];

        if ($user->authority == "admin") {
            $views = [
                ["admin/navbar", [], "main"],
                ["user/profile/profile", ["user" => $user], "main"]
            ];
        }

        $this->renderPage($views, "$user->name");
    }
}
