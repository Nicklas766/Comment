<?php

namespace Nicklas\Comment\HTMLForm\Comment;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Nicklas\Comment\Modules\User;
use \Nicklas\Comment\Modules\Comment;

/**
 * Example of FormModel implementation.
 */
class CreateCommentForm extends FormModel
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
            ],
            [
                "comment" => [
                    "type"        => "textarea",
                    "label" => false,
                    "placeholder" => "Comment here"
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Skicka",
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
        $sentComment       = $this->form->value("comment");

        $comment = new Comment();
        $comment->setDb($this->di->get("db"));

        if (!$this->di->get('session')->has("user")) {
            $this->form->addOutput("You need to log in");
            return false;
        }

        $user = $this->di->get('session')->get("user"); # get user name

        $comment->user = $user;
        $comment->comment = $sentComment;
        $comment->save();
        return true;
    }
}
