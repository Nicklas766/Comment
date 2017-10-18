<?php

namespace Nicklas\Comment\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Nicklas\Comment\User;
use \Nicklas\Comment\Post;

/**
 * Form to update an item.
 */
class EditCommentForm extends FormModel
{
    /**
     * Constructor injects with DI container and the id to update.
     *
     * @param Anax\DI\DIInterface $di a service container
     * @param integer             $id to update
     */
    public function __construct(DIInterface $di, $id)
    {
        parent::__construct($di);
        $this->comment = $this->getCommentDetails($id);
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Update details of the (comment {$this->comment->id})",
                "fieldset" => true
            ],
            [
                "comment" => [
                    "type" => "textarea",
                    "validation" => ["not_empty"],
                    "value" => $this->comment->comment,
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Save",
                    "callback" => [$this, "callbackSubmit"]
                ],

                "reset" => [
                    "type"      => "reset",
                ],

                "delete" => [
                    "type" => "submit",
                    "value" => "Delete",
                    "callback" => [$this, "callBackDelete"]
                ],
            ]
        );
    }



    /**
     * Get details on item to load form with.
     *
     * @param integer $id get details on item with id.
     *
     * @return object true if okey, false if something went wrong.
     */
    public function getCommentDetails($id)
    {
        $comment = new Comment();
        $comment->setDb($this->di->get("db"));
        $comment->find("id", $id);
        return $comment;
    }


    /**
     * get details for the user based on sessio
     *
     *
     * @return object the user
     */
    public function controlAuthority()
    {
        // Get logged in users details, based on session
        $userName = $this->di->get("session")->get("user");
        $user = new User();
        $user->setDb($this->di->get("db"));
        $user->find("name", $userName);

        // IF AUTHORITY == admin, then continue
        if ($user->authority != "admin") {
            return ($user->name == $this->comment->user);
        }
        return true;
    }

    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return void
     */
    public function callbackDelete()
    {
        // Form values
        $comment = $this->comment;

        if (!$this->controlAuthority($comment)) {
            $this->form->addOutput("You can't edit comment #$comment->id");
            return false;
        }

        $comment->delete();
        $this->di->get("response")->redirect("comment");
    }

    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return void
     */
    public function callbackSubmit()
    {
        $comment = $this->comment;

        if (!$this->controlAuthority($comment)) {
            $this->form->addOutput("You can't edit comment #$comment->id");
            return false;
        }

        $comment->comment = $this->form->value("comment");
        $comment->save();
        $this->di->get("response")->redirect("comment/edit/{$comment->id}");
    }
}
