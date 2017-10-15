<?php

namespace Nicklas\Comment;

use \Nicklas\Comment\HTMLForm\Comment\CreateCommentForm;
use \Nicklas\Comment\HTMLForm\Comment\EditCommentForm;

use Nicklas\Comment\Modules\Comment;

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
        $comment = new Comment($this->di);
        $comment->setDb($this->di->get("db"));

        $form       = new CreateCommentForm($this->di);
        $form->check();

        $views = [
            ["comment/makeComment", ["form" => $form->getHTML()], "main"]
        ];

        // If not logged in, render other views
        if (!$this->di->get("session")->has("user")) {
            $views = [
                ["comment/loginComment", [], "main"]
            ];
        }

        $this->di->get("pageRenderComment")->renderPage([
            "views" => $views,
            "title" => "Create a comment"
        ]);
    }
}
