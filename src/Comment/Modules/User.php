<?php

namespace Nicklas\Comment\Modules;

/**
 * A database driven model.
 */
class User extends ActiveRecordModelExtender
{

        /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "coffee_users";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $name;
    public $email;
    public $pass;
    public $authority = "user";
    public $question;

    public function getReputation($user)
    {
        // Get all the vote->scores connected to users posts
        $questionPoints = array_reduce($user->questions, function ($carry, $item) {
            $carry += $item->question->vote->score;
            return $carry;
        });

        $postPoints = array_reduce($user->posts, function ($carry, $item) {
            $carry += $item->vote->score;
            return $carry;
        });

        $commentPoints = array_reduce($user->comments, function ($carry, $item) {
            $carry += $item->vote->score;
            return $carry;
        });

        $points =
        ($questionPoints * 2) +
        ($postPoints     * 3) +
        ($commentPoints  * 1);

        // Algorithm for reputation points
        $reputation =
        (count($user->questions) * 2)   +
        (count($user->posts)     * 3)   +
        (count($user->comments)  * 1)   + $points;

         return $reputation;
    }


    /**
     * Sets up user
     * @param object $user
     *
     * @return object
     */
    public function setupUser($user)
    {
        $question = new Question($this->db);
        $post     = new Post($this->db);
        $comment  = new Comment($this->db);
        $vote     = new Vote($this->db);

        // Basic setup for user
        $user->img        = $this->getGravatar($user->name);

        // Get all posts/votes user made
        $sqlAccept = "user = ? AND type = ? AND accepted = ?";
        $user->acceptedAnswers = count($post->getAllPosts($sqlAccept, [$user->name, "answer", "yes"]));
        $user->questions       = $question->getQuestions("user = ?", $user->name);
        $user->comments        = $comment->getComments("user = ?", $user->name);
        $user->posts           = $post->getAllPosts("user = ? AND type = ?", [$user->name, "answer"]);
        $user->votes           = $vote->getVote("user = ?", [$user->name]);


        // Amount of posts
        $user->postAmount = count($user->questions) + count($user->posts) + count($user->comments);

        // Algorithm for reputation points
        $user->reputation = $this->getReputation($user);

        // Get all the questions connected to answers
        $user->answeredQuestions = array_map(function ($answer) {
            $question = new Question($this->db);
            return $question->getQuestion($answer->questionId);
        }, $user->posts);

        return $user;
    }

    /**
     * Returns post with markdown and gravatar
     * @param string $sql
     * @param array $param
     *
     * @return array
     */
    public function getAllUsers($sql = null, $params = null)
    {
        if ($sql == null) {
            $users = $this->findAll();
        }
        if ($sql != null) {
            $users = $this->findAllWhere($sql, $params);
        }

        return array_map(array($this, 'setupUser'), $users);
    }

    /**
     * return question/answer, three attributes are set, comments connected to them is an array.
     * @param string $name
     *
     * @return object
    */
    public function getUser($name)
    {
        $user = $this->find("name", $name);
        $user = $this->setupUser($user);
        return $user;
    }


    /**
     * Check if user exists
     *
     * @param string $name
     *
     * @return boolean true if user exists in database else false
     */
    public function userExists($name)
    {
        $user = $this->find("name", $name);
        return !$user ? false : true;
    }
    /**
     * Returns gravatar link
     *
     * @param string $email
     *
     * @return string as gravatar link
     */
    public function setGravatar()
    {
        $this->img = $this->gravatar($this->email);
    }

    /**
     * Returns gravatar link
     *
     * @param string $name
     *
     * @return string as gravatar link
     */
    public function getGravatar($name)
    {
        $this->find("name", $name);
        return $this->gravatar($this->email);
    }

    /**
     * Set the pass.
     *
     * @param string $pass the pass to use.
     *
     * @return void
     */
    public function setPassword($pass)
    {
        $this->pass = password_hash($pass, PASSWORD_DEFAULT);
    }

    /**
     * Verify the name and the pass, if successful the object contains
     * all details from the database row.
     *
     * @param string $name  name to check.
     * @param string $pass the pass to use.
     *
     * @return boolean true if name and pass matches, else false.
     */
    public function verifyPassword($name, $pass)
    {
        $this->find("name", $name);
        return password_verify($pass, $this->pass);
    }

    /**
     * Verify the name and the anaswer, if successful the object contains
     * all details from the database row.
     *
     * @param string $name  name to check.
     * @param string $answer the answer.
     *
     * @return boolean true if name and pass matches, else false.
     */
    public function verifyQuestion($name, $answer)
    {
        $this->find("name", $name);
        return ($this->question == $answer);
    }


    /**
    * Check if a post belongs to user
    *
    *
    * @return boolean
    */
    public function controlAuthority($loggedUser, $name)
    {
        $this->find("name", $loggedUser);
        // IF AUTHORITY == admin, then continue
        if ($this->authority != "admin") {
            return ($this->name == $name);
        }
        return true;
    }
}
