<?php

namespace Nicklas\Comment;

use \Nicklas\Comment\Modules\Post;
use \Nicklas\Comment\Modules\Comment;
use \Nicklas\Comment\Modules\Vote;
use \Nicklas\Comment\Modules\Question;
use \Nicklas\Comment\Modules\User;

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
        $vote = new Vote($this->di->get("db"));

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

    /**
     * View specific question and create answer form
     *
     * @return void
     */
    public function postAcceptedAnswer($id)
    {
        // Find the post
        $post = new Post($this->di->get("db"));
        $post = $post->find("id", $id);

        // Find the question connected to post
        $question = new Question($this->di->get("db"));
        $question = $question->find("id", $post->questionId);

        // Control if the current logged user has authority to accept answer
        $userName = $this->di->get("session")->get("user");
        $user = new User($this->di->get("db"));

        if (!$user->controlAuthority($userName, $question->user)) {
            return false;
        }

        $post->accepted = "yes";
        $post->save();
        return true;
    }
}
