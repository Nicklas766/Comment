<?php

namespace Nicklas\Comment\HTMLForm\Comment;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Nicklas\Comment\Modules\User;
use \Nicklas\Comment\Modules\Comment;

/**
 * Example of FormModel implementation.
 */
class CreateQuestionForm extends FormModel
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
                "title" => [
                    "type"        => "text",
                    "label" => "En bra titel ökar chansen att någon svarar",
                    "placeholder" => "Titel"
                ],
                "tags" => [
                    "type"        => "text",
                    "label" => "Du kan ange fler än en tag. Men du behöver ange minst en.",
                    "placeholder" => "#tags"
                ],
                "question" => [
                    "type"        => "textarea",
                    "label" => "Här kan du skriva din fråga",
                    "placeholder" => "Din fråga"
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Skicka",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }



    public function getHashtags($string)
    {
        preg_match_all("/(#\w+)/u", $string, $matches);
        if ($matches) {
            $hashtagsArray = array_count_values($matches[0]);
            $hashtags = array_keys($hashtagsArray);
            $tags = implode(",", $hashtags);
            return $tags;
        }
        $tags = implode(",", $hashtags);
        return null;
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
        $title          = $this->form->value("title");
        $tags           = $this->getHashtags($this->form->value("tags"));
        $question       = $this->form->value("question");

        $comment = new Comment();
        $comment->setDb($this->di->get("db"));

        if (!$this->di->get('session')->has("user")) {
            $this->form->addOutput("Du behöver logga in");
            return false;
        }

        if (!$tags) {
            $this->form->addOutput("
            <p>Inga taggar hittades, du behöver ange minst en.</p>
            <p>Du taggar genom att göra en hashtag, #mintag.</p>
            <p>#hej på dig #kaffe <--- kommer att bli #hej #kaffe</p>
            ");
            return false;
        }

        $user = $this->di->get('session')->get("user"); # get user name

        $comment->user       = $user;
        $comment->title      = $title;
        $comment->tags       = $tags;
        $comment->text       = $question;
        $comment->type       = "question";
        $comment->save();

        $this->form->addOutput("Du skapade en fråga! Du bör se den nedan");
        return true;
    }
}
