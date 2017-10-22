<?php

namespace Nicklas\Comment\HTMLForm\Comment;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Nicklas\Comment\Modules\User;
use \Nicklas\Comment\Modules\Post;

/**
 * Example of FormModel implementation.
 */
class EditPostForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di, $id)
    {
        parent::__construct($di);
        $this->post     = new Post($di->get("db"));
        $this->post->find("id", $id);
        if ($this->post->id == null) {
            $di->get("response")->redirect("question");
        }
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Här kan du uppdatera din post"
            ],
            [
                "text" => [
                    "type"  => "textarea",
                    "value" => $this->post->text,
                    "label" => "Här kan du skriva din fråga",
                    "placeholder" => "Din fråga"
                ],
                "submit" => [
                    "type" => "submit",
                    "value" => "Spara",
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
        $text = $this->form->value("text");

        if (!$this->di->get('session')->has("user")) {
            $this->form->addOutput("Du behöver logga in");
            return false;
        }

        $user = new User($this->di->get("db"));
        if ($user->controlAuthority($this->di->get('session')->get("user"), $this->post->user) != true) {
            $this->form->addOutput("Du får inte redigera denna.");
            return false;
        }

        if ($text == "") {
            $this->form->addOutput("Du skrev aldrig något. Skriv gärna något.");
            return false;
        }

        $this->post->text = $text;
        $this->post->save();
        $this->form->addOutput("Du har uppdaterat inlägget");
        return true;
    }
}
