<?php

namespace Nicklas\Comment\HTMLForm\Comment;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Nicklas\Comment\Modules\Post;

/**
 * Example of FormModel implementation.
 */
class CreateAnswerForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di, $questionId)
    {
        $this->questionId = $questionId;
        parent::__construct($di);
        $this->form->create(
            [
                "id" => __CLASS__,
            ],
            [
                "text" => [
                    "type"        => "textarea",
                    "label" => "Här kan du skriva ditt svar",
                    "placeholder" => "Ditt svar"
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Svara",
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
        $text       = $this->form->value("text");

        if (!$this->di->get('session')->has("user")) {
            $this->form->addOutput("Du behöver logga in för att kommentera.");
            return false;
        }

        if ($text == "") {
            $this->form->addOutput("Du skrev aldrig något. Skriv gärna något.");
            return false;
        }


        $user = $this->di->get('session')->get("user"); # get user name

        $post     = new Post($this->di->get("db"));
        $post->questionId = $this->questionId;
        $post->user       = $user;
        $post->text       = $text;
        $post->type       = "answer";
        $post->accepted   = "no";
        $post->save();

        $this->form->addOutput("Du skapade ett svar!");
        return true;
    }
}
