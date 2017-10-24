<?php

namespace Nicklas\Comment\HTMLForm\Comment;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Nicklas\Comment\Modules\Question;
use \Nicklas\Comment\Modules\Post;

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
                "text" => [
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

    public function isValid($str)
    {
        return !preg_match('/[^A-Za-z0-9.#. \\-$]/', $str);
    }

    public function getHashtags($string)
    {
        if (!$this->isValid($string)) {
            return null;
        }
        preg_match_all("/(#\w+)/u", $string, $matches);
        if ($matches) {
            $hashtagsArray = array_count_values($matches[0]);
            $hashtags = array_keys($hashtagsArray);
            $tags = implode(",", $hashtags);
            return str_replace("#", "", $tags);
        }
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
        $text       = $this->form->value("text");

        if (!$this->di->get('session')->has("user")) {
            $this->form->addOutput("Du behöver logga in");
            return false;
        }

        if ($text == "") {
            $this->form->addOutput("Du skrev aldrig något. Skriv gärna något.");
            return false;
        }

        if (!$tags) {
            $this->form->addOutput("
            <p>Din fråga skickades inte:</p>
            <p>Antingen så angav du inga taggar, eller så angav du en ogiltig.</p>
            <p>Nordiska bokstäver är ej tillåtna i tags, använd istället a för ä t.ex.</p>
            <p>Tips:</p>
            <p>Du taggar genom att göra en hashtag, #mintag.</p>
            <p>#hej pa dig #kaffe <--- kommer att bli #hej #kaffe</p>

            <input type='submit' id='accept' value=Ok>
            <script>
            $( document ).ready(function() {
                $('#accept').click(function () {
                    $('output').remove();
                });

            });
            </script>
            ");
            return false;
        }


        $user = $this->di->get('session')->get("user"); # get user name

        $question = new Question($this->di->get("db"));
        $question->user       = $user;
        $question->title      = $title;
        $question->tags       = $tags;
        $question->save();


        $post     = new Post($this->di->get("db"));
                $post->user       = $user;
        $post->questionId = $question->id;
        $post->text       = $text;
        $post->type       = "question";
        $post->accepted   = "no";
        $post->save();


        $this->form->addOutput("Du skapade en fråga! Du bör se den nedan");
        return true;
    }
}
