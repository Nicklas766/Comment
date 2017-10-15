<?php

namespace Nicklas\Comment\HTMLForm\User;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Nicklas\Comment\Modules\User;

/**
 * Example of FormModel implementation.
 */
class UserLoginForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di)
    {
        parent::__construct($di);

        $this->form->create(
            [
                "id" => __CLASS__,
                "br-after-label" => false,
                "use_fieldset" => false,
            ],
            [
                "user" => [
                    "type"        => "text",
                    //"description" => "Here you can place a description.",
                    "placeholder" => "Användarnamn",
                    "validation" => ["not_empty"],
                    "label" => false
                ],

                "password" => [
                    "type"        => "password",
                    //"description" => "Here you can place a description.",
                    "placeholder" => "Lösenord",
                    "validation" => ["not_empty"],
                    "label" => false
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Login",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return boolean true if okey, false if something went wrong.
     */
    public function callbackSubmit()
    {
        // Get values from the submitted form
        $name       = $this->form->value("user");
        $password      = $this->form->value("password");

        $user = new User();
        $user->setDb($this->di->get("db"));
        $res = $user->verifyPassword($name, $password);

        if (!$res) {
            $this->form->rememberValues();
            $this->form->addOutput("User or password did not match.");
            return false;
        }

        $this->di->get('session')->set("user", $name); # set user in session
        $this->di->get("response")->redirect("user/profile");
    }
}
