<?php

namespace Nicklas\Comment;

// ANAX
use \Anax\Configure\ConfigureInterface;
use \Anax\Configure\ConfigureTrait;
use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;

// HTMLforms
use \Nicklas\Comment\HTMLForm\User\UserLoginForm;
use \Nicklas\Comment\HTMLForm\User\CreateUserForm;
use \Nicklas\Comment\HTMLForm\User\UserResetForm;

// MODULES
use \Nicklas\Comment\Modules\User;

/**
 * A controller class.
 */
class UserController implements
    ConfigureInterface,
    InjectionAwareInterface
{
    use ConfigureTrait, InjectionAwareTrait;

    /**
     * Logout user by setting "user" == null in session.
     *
     * @return void
     */
    public function logout()
    {
        $this->di->get('session')->set("user", null);
        $this->di->get("response")->redirect("user/login");
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
                ["user/fail/fail", [], "main"]
            ];
            $this->di->get("pageRenderComment")->renderPage([
                "views" => $views,
                "title" => "Not logged in"
            ]);
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
            ["user/pre/login", ["form" => $form->getHTML()], "main"]
        ];

        $this->di->get("pageRenderComment")->renderPage([
            "views" => $views,
            "title" => "Login"
        ]);
    }

    /**
    * Description.
    *
    * @return void
    */
   public function getPostReset()
   {
       $form       = new UserResetForm($this->di);
       $form->check();

       $views = [
           ["user/pre/reset", ["form" => $form->getHTML()], "main"]
       ];

       $this->di->get("pageRenderComment")->renderPage([
           "views" => $views,
           "title" => "Reset password"
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
    public function getPostCreateUser()
    {
        $form       = new CreateUserForm($this->di);
        $form->check();

        $views = [
            ["user/pre/create", ["form" => $form->getHTML()], "main"]
        ];

        $this->di->get("pageRenderComment")->renderPage([
            "views" => $views,
            "title" => "Create User"
        ]);
    }
}
