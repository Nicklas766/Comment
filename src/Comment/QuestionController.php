<?php

namespace Nicklas\Comment;

use \Nicklas\Comment\HTMLForm\Comment\CreateQuestionForm;
use \Nicklas\Comment\HTMLForm\Comment\CreateAnswerForm;
use \Nicklas\Comment\HTMLForm\Comment\EditCommentForm;

use \Nicklas\Comment\Modules\Question;
use \Nicklas\Comment\Modules\Comment;

/**
 * Extends the UserController, for comments
 */
class QuestionController extends AdminController
{

    /**
     * Show all items.
     *
     * @return void
     */
    public function getIndex()
    {
        $question = new Question($this->di->get("db"));

        $views = [
            ["comment/question/view-all", ["questions" => $question->getQuestions()], "main"]
        ];

        $this->di->get("pageRenderComment")->renderPage([
            "views" => $views,
            "title" => "All questions"
        ]);
    }

    /**
     * View specific question and create answer form
     *
     * @return void
     */
    public function getPostQuestionAnswer($id, $sort = null)
    {
        $question = new Question($this->di->get("db"));
        $question = $question->getQuestion($id);

        // Get query for up or down
        $order = isset($_GET["order"]) ? $_GET["order"] : "up";


        if ($sort == "points") {
            usort($question->answers, function($a, $b) {
                return $a->vote->score < $b->vote->score;
            });
        }

        if ($sort == "vote") {
            usort($question->answers, function($a, $b) {
                return count($b->vote->likes) > count($a->vote->likes);
            });
        }


        if ($order == "down") {
            asort($question->answers);
        }

        if ($order == "up") {
            arsort($question->answers);
        }





        $form       = new CreateAnswerForm($this->di, $id);
        $form->check();



        $views = [
            ["comment/question/view/view-question", ["question" => $question], "question"],
            ["comment/question/view/view-answers", ["answers" => $question->answers, "questionId" => $question->question->questionId], "question"],
            ["comment/question/view/post-answer", ["form" => $form->getHTML()], "form"],
            ["comment/question/view/wrappedField", ["question" => $question], "main"]
            ];
        $this->di->get("pageRenderComment")->renderPage([
            "views" => $views,
            "title" => "Create your question"
        ]);

        return false;
    }

    /**
     * Show all items.
     *
     * @return void
     */
    public function getTaggedQuestions($tag)
    {
        $question = new Question($this->di->get("db"));

        $questions = $question->getQuestions();



        $filteredQuestions = array_filter($questions, function ($value) use ($tag) {
            return in_array($tag, $value->tags);
        });

        $views = [
            ["comment/question/view-all", ["questions" => $filteredQuestions], "main"]
        ];

        $this->di->get("pageRenderComment")->renderPage([
            "views" => $views,
            "title" => "Questions | $tag"
        ]);
    }

    /**
     * View all comments and create question form
     *
     * @return void
     */
    public function getPostCreateQuestion()
    {
        $question = new Question($this->di->get("db"));

        $form       = new CreateQuestionForm($this->di);
        $form->check();

        $views = [
            ["comment/question/create-question", ["form" => $form->getHTML()], "main"],
            ["comment/question/view-all", ["questions" => $question->getQuestions()], "main"]
        ];

        // If not logged in, render other views
        if (!$this->di->get("session")->has("user")) {
            $views = [
                ["comment/loginComment", [], "main"]
            ];
        }

        $this->di->get("pageRenderComment")->renderPage([
            "views" => $views,
            "title" => "Create your question"
        ]);
    }
}
