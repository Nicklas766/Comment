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
    protected $tableName = "ramverk1_users";

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

        $question   = new Question($this->db);
        $post       = new Post($this->db);
        $comment    = new Comment($this->db);

        return array_map(function ($user) use ($question, $post, $comment) {
            $user->questions    = $question->getQuestions("user = ?", $user->name);
            $user->posts        = $post->getAllPosts("user = ? AND type = ?", [$user->name, "answer"]);
            $user->comments     = $comment->getComments("user = ?", $user->name);
            $user->img          = $this->getGravatar($user->name);
            
            return $user;
        }, $users);
    }

    /**
     * return question/answer, three attributes are set, comments connected to them is an array.
     *
     * @return object
    */
    public function getUser($name)
    {
        $user = $this->find("name", $name);

        $question   = new Question($this->db);
        $post       = new Post($this->db);
        $comment    = new Comment($this->db);

        // Get all the different posts user made.
        $user->questions    = $question->getQuestions("user = ?", $user->name);
        $user->posts        = $post->getAllPosts("user = ? AND type = ?", [$user->name, "answer"]);
        $user->comments     = $comment->getComments("user = ?", $user->name);
        $user->img          = $this->getGravatar($name);

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
