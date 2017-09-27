<?php

namespace Nicklas\Comment\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Nicklas\Comment\User;

/**
 * Example of FormModel implementation.
 */
class CreateUserForm2 extends FormModel
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
                "legend" => "Create user",
                "fieldset" => true
            ],
            [
                "name" => [
                    "type"        => "text",
                    "validation" => ["not_empty"]
                ],

                "email" => [
                    "type"        => "text",
                ],

                "password" => [
                    "type"        => "password",
                    "validation" => ["not_empty"]
                ],

                "password-again" => [
                    "type"        => "password",
                    "validation" => [
                        "match" => "password"
                    ],
                ],

                "select" => [
                    "type"        => "select",
                    "label"       => "Select authority",
                    "options"     => ["admin" => "admin", "user" => "user"],
                ],

                "submit" => [
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
          $user->authority = $this->form->value("select");
          $user->setPassword($password);
          $user->save();

          $this->form->addOutput("You added the user");
          return true;
    }
}
