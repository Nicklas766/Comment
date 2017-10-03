<?php

namespace Nicklas\Comment\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Nicklas\Comment\User;

/**
 * Example of FormModel implementation.
 */
class EditUserForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di, $id)
    {
        parent::__construct($di);
        $this->user = new User();
        $this->user->setDb($this->di->get("db"));
        $this->user->find("id", $id);
        $user = $this->user;
        $this->form->create(
            [
                "id" => __CLASS__,
                "fieldset" => true,
                "legend" => "Update user: $user->name"
            ],
            [

                "name" => [
                    "type"        => "text",
                    "readonly" => true,
                    "value" => $user->name,
                ],
                "email" => [
                    "type"        => "text",
                    "value" => $user->email,
                ],
                "select" => [
                    "type"        => "select",
                    "label"       => "Select authority",
                    "options"     => ["$user->authority" => $user->authority, "admin" => "admin", "user" => "user"],
                ],
                "submit" => [
                    "type" => "submit",
                    "value" => "Update",
                    "callback" => [$this, "callbackSubmit"]
                ],
                "reset" => [
                    "type"      => "reset",
                ],
                "delete" => [
                    "type" => "submit",
                    "value" => "Delete",
                    "onclick"=>"return confirm('Vill du verkligen radera användaren?');",
                    "callback" => [$this, "callBackDelete"]
                ],
            ]
        );
    }


    /**
     * @return void
     */
    public function callbackDelete()
    {
        $this->user->delete();
        $this->di->get("response")->redirect("admin/user");
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

          $user = $this->user;

          $user->email = $email;
          $user->authority = $this->form->value("select") ?: "user";
          $user->save();
          $this->form->addOutput("Du uppdaterade användaren");
          return true;
    }
}
