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
     * Shows the post(question or answer) and create comment form
     *
     * @return void
     */
    public function getPostCreateComment($id)
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
}
