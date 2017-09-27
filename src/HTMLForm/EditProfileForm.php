<?php

namespace Nicklas\Comment\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Nicklas\Comment\User;

/**
 * Example of FormModel implementation.
 */
class EditProfileForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di, $name)
    {
        parent::__construct($di);
        $user = new User();
        $user->setDb($this->di->get("db"));
        $user->find("name", $name);
        $this->form->create(
            [
                "id" => __CLASS__,
                "class" => "login-widget",
                "fieldset" => true
            ],
            [

                "email" => [
                    "type"        => "text",
                    "value" => $user->email,
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Update profile",
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
        $email       = $this->form->value("email");

        if (strpos($email, '%') !== false) {
            $this->form->addOutput("% is not allowed");
            return false;
        }

          $name = $this->di->get('session')->get("user");
          $user = new User();
          $user->setDb($this->di->get("db"));
          $user->find("name", $name);

          $user->email = $email;
          $user->save();
          return true;
    }
}
