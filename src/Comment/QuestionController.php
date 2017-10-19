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
    public function getPostQuestionAnswer($id)
    {
        $question = new Question($this->di->get("db"));

        $form       = new CreateAnswerForm($this->di, $id);
        $form->check();

        $question = $question->getQuestion($id);

        $views = [
            ["comment/question/view/view-question", ["question" => $question], "question"],
            ["comment/question/view/view-answers", ["answers" => $question->answers], "question"],
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
     * View specific question and create answer form
     *
     * @return void
     */
    public function postComment($id)
    {
        $text = isset($_POST["text"]) ? $_POST["text"] : "nothingfound";

        if ($text == "nothingfound") {
            return false;
        }
        $user = $this->di->get("session")->get("user");
        $comment = new Comment($this->di->get("db"));
        $comment->text = $text;
        $comment->parentId = $id;
        $comment->user = $user;
        $comment->save();
        return true;
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
