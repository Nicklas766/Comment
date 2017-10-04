<?php

namespace Nicklas\Comment;

/**
 * A database driven model.
 */
class Comment extends ActiveRecordModelExtender
{

        /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "ramverk1_comments";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $di;
    public $user;
    public $comment;


    /**
     * Constructor injects with DI container.
     *
     */
    public function __construct($di = null)
    {
        $this->di = $di;
    }



    /**
     * Get all comments
     *
     *
     * @return array
     */
    public function getAll()
    {
        $comments = $this->findAll();

        return array_map(function ($comment) {
            $user = new User();
            $user->setDb($this->di->get("db"));
            $user->find("name", $comment->user);

            $comment->img = $this->gravatar($user->email);
            $comment->markdown = $this->getMD($comment->comment);

            return $comment;
        }, $comments);
    }

    /**
     * Get comment
     *
     *
     * @return object
     */
    public function get($id)
    {
        $comment = $this->find("id", $id);
        $comment->markdown = $this->getMD($comment->comment);
        return $comment;
    }

    /**
     * Check if a comment belongs to user
     *
     *
     * @return boolean
     */
    public function controlAuthority($name)
    {
        $user = new User();
        $user->setDb($this->di->get("db"));
        $user->find("name", $name);

        // IF AUTHORITY == admin, then continue
        if ($user->authority != "admin") {
            return ($user->name == $this->user);
        }
        return true;
    }
}
