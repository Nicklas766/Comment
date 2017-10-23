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
        if ($question->find("id", $id) == null) {
            return false;
        }
        $question = $question->getQuestion($id);


        // This is a bad practice and should be in the module, (fix this after kmom10 future)
        // getting actual user Objects for posts for reputation show in view.
        $question->question->userObj = $user->getUser($question->user);
        $question->userObj           = $user->getUser($question->user);

        $question->answers = array_map(function ($answer) {
            $user                = new User($this->di->get("db"));
            $answer->userObj     = $user->getUser($answer->user);
            $answer->vote->score = $answer->vote->score == null ? 0 : $answer->vote->score;
            return $answer;
        }, $question->answers);

        // Get query for up or down
        $order = isset($_GET["order"]) ? $_GET["order"] : "up";

        // Highest points
        if ($sort == "points") {
            usort($question->answers, function ($current, $next) {
                return $current->vote->score > $next->vote->score;
            });
        }

        // Highest votes
        if ($sort == "vote") {
            usort($question->answers, function ($current, $next) {
                return count($current->vote->likes) > count($next->vote->likes);
            });
        }

        // If up array_reverse, "down" is default from module
        if ($order == "up") {
            $question->answers = array_reverse($question->answers);
        }

        $form       = new CreateAnswerForm($this->di, $id);
        $form->check();

        $views = [
            ["comment/question/view/view-question", ["question" => $question], "question"],
            ["comment/question/view/view-answers",
            ["answers" => $question->answers, "questionId" => $question->question->questionId], "question"],
            ["comment/question/view/post-answer", ["form" => $form->getHTML()], "form"],
            ["comment/question/view/wrappedField", ["question" => $question], "main"]
            ];
        $this->di->get("pageRenderComment")->renderPage([
            "views" => $views,
            "title" => "Fråga $question->id"
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
