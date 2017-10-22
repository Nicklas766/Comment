<?php

namespace Nicklas\Comment;

// MODULES
use \Nicklas\Comment\Modules\User;
use \Nicklas\Comment\Modules\Post;
use \Nicklas\Comment\HTMLForm\Comment\EditPostForm;
use \Nicklas\Comment\HTMLForm\Comment\EditCommentForm;

/**
 * A controller class.
 */
class UserController extends ProfileController
{

    /**
     * Create page for all users overview
     *
     * @return void
     */
    public function getAllUsersIndex()
    {
        $user = new User($this->di->get("db"));
        $users = $user->getAllUsers();

        $views = [
            ["comment/users/view-all", ["users" => $users], "main"]
        ];

        $this->di->get("pageRenderComment")->renderPage([
            "views" => $views,
            "title" => "Coffee users"
        ]);
    }

    /**
     * Create page for all users overview
     *
     * @return void
     */
    public function getUserIndex($name)
    {
        $user = new User($this->di->get("db"));
        $user = $user->getUser($name);

        $views = [
            ["comment/users/view-user", ["user" => $user], "main"]
        ];

        $this->di->get("pageRenderComment")->renderPage([
            "views" => $views,
            "title" => "User | $user->name"
        ]);
    }

    /**
     * Create page for all users overview
     *
     * @return void
     */
    public function getPostEditPost($id)
    {
        $form = new EditPostForm($this->di, $id);
        $form->check();

        $views = [
            ["comment/default-form", ["form" => $form->getHTML()], "main"]
        ];

        $this->di->get("pageRenderComment")->renderPage([
            "views" => $views,
            "title" => "Edit post | $id"
        ]);
    }
    /**
     * Create page for all users overview
     *
     * @return void
     */
    public function getPostEditComment($id)
    {
        $form = new EditCommentForm($this->di, $id);
        $form->check();

        $views = [
            ["comment/default-form", ["form" => $form->getHTML()], "main"]
        ];

        $this->di->get("pageRenderComment")->renderPage([
            "views" => $views,
            "title" => "Edit comment | $id"
        ]);
    }
}
