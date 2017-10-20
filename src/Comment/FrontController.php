<?php

namespace Nicklas\Comment;

use \Nicklas\Comment\Modules\Comment;
use \Nicklas\Comment\Modules\Vote;

/**
 * Extends the UserController, for comments
 */
class FrontController extends QuestionController
{


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
     * View specific question and create answer form
     *
     * @return void
     */
    public function postVote()
    {
        $vote = new Vote();

        $user = $this->di->get("session")->get("user");

        $parentType = isset($_POST["parentType"]) ? $_POST["parentType"] : "";
        $downVote   = isset($_POST["downVote"])   ? $_POST["downVote"]   : null;
        $parentId   = isset($_POST["parentId"])   ? $_POST["parentId"]   : "";
        $upVote     = isset($_POST["upVote"])     ? $_POST["upVote"]     : null;

        if ($upVote != null) {
            return $vote->like($user, $parentId, $parentType);
        }
        if ($downVote != null) {
            return $vote->dislike($user, $parentId, $parentType);
        }
        return false;
    }
}
