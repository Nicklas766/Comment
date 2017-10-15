<?php

namespace Nicklas\Comment\HTMLForm\User;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Nicklas\Comment\Modules\User;

/**
 * Example of FormModel implementation.
 */
class CreateUserForm extends FormModel
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
                "class" => "login-widget",
            ],
            [
                "name" => [
                    "type"        => "text",
                    "validation" => ["not_empty"],
                    "placeholder" => "name",
                    "label" => false,
                ],

                "email" => [
                    "type"        => "text",
                    "placeholder" => "email",
                    "label" => false,
                ],
                "question" => [
                    "type"        => "text",
                    "placeholder" => "Din favoriträtt",
                    "validation" => ["not_empty"],
                    "label" => false,
                ],

                "password" => [
                    "type"        => "password",
                    "validation" => ["not_empty"],
                    "placeholder" => "password",
                    "label" => false
                ],

                "password-again" => [
                    "type"        => "password",
                    "validation" => [
                        "match" => "password"
                    ],
                    "placeholder" => "password",
                    "label" => false
                ],

                "submit" => [
                    "label" => false,
                    "type" => "submit",
                    "value" => "Create user",
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
        $name       = $this->form->value("name");
        $email       = $this->form->value("email");
        $question       = $this->form->value("question");
        $password      = $this->form->value("password");
        $passwordAgain = $this->form->value("password-again");

        // Check password matches
        if ($password !== $passwordAgain) {
            $this->form->rememberValues();
            $this->form->addOutput("Password did not match.");
            return false;
        }

        if (strpos($name, '%') !== false) {
            $this->form->addOutput("% is not allowed");
            return false;
        }

         $user = new User();
         $user->setDb($this->di->get("db"));

        if ($user->userExists($name)) {
             $this->form->addOutput("User already exists");
             return false;
        }

          $user->name = $name;
          $user->email = $email;
          $user->question = $question;
          $user->setPassword($password);
          $user->save();

          $this->di->get('session')->set("user", $name); # set user in session
          $this->di->get("response")->redirect("user/profile");
    }
}
