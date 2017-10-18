<?php

namespace Nicklas\Comment;

use \Nicklas\Comment\HTMLForm\Comment\CreateQuestionForm;
use \Nicklas\Comment\HTMLForm\Comment\EditCommentForm;

use \Nicklas\Comment\Modules\Question;

/**
 * Extends the UserController, for comments
 */
class CommentController extends AdminController
{


    /**
     * Show all items.
     *
     * @return void
     */
    public function getIndex()
    {
        $question = new Question($this->di);
        $question->setDb($this->di->get("db"));

        $views = [
            ["comment/question/view-all", ["questions" => $question->getQuestions()], "main"]
        ];

        $this->di->get("pageRenderComment")->renderPage([
            "views" => $views,
            "title" => "All questions"
        ]);
    }
    /**
     * Show all items.
     *
     * @return void
     */
    public function getTaggedQuestions($tag)
    {
        $comment = new Comment($this->di);
        $comment->setDb($this->di->get("db"));

        $views = [
            ["comment/crud/view-all", ["questions" => $comment->getPosts("tags LIKE ?", [$tag])], "main"]
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
        $comment = new Comment($this->di);
        $comment->setDb($this->di->get("db"));

        $form       = new CreateQuestionForm($this->di);
        $form->check();

        $views = [
            ["comment/crud/createQuestion", ["form" => $form->getHTML()], "main"],
            ["comment/crud/view-all", ["questions" => $comment->getPosts("type = ?", ["question"])], "main"]
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

    /**
     * View specific question and create answer form
     *
     * @return void
     */
    public function getPostQuestionAnswer($id)
    {
        $question = new Question($this->di);
        $question->setDb($this->di->get("db"));

        $question = $question->getQuestion($id);

        // If not logged in, render other views
        if ($question->type == "question") {
            $views = [
                ["comment/question/view-question", ["question" => $question], "main"]
             ];
            $this->di->get("pageRenderComment")->renderPage([
                "views" => $views,
                "title" => "Create your question"
            ]);
        }
        return false;
    }
}
