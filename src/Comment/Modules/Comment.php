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


    public $text;

    public $parentId; # All posts have different ids
    public $type; # question/answer/comment

    public $created;
    public $status; # default is active




    /**
     * HA EN FOREACH I En FOREACH FÖR ATT HA COMMENTS CONNECTED
     *

        */

    /**
     * Constructor injects with DI container.
     *
     */
    public function __construct($di = null)
    {
        $this->di = $di;
    }
    /**
     * Returns post with markdown and gravatar
     * @param string $sql
     * @param array $param
     *
     * @return array
     */
    public function getPosts($sql, $params)
    {
        $posts = $this->findAllWhere("$sql", $params);

        return array_map(function ($post) {
            $user = new User();
            $user->setDb($this->di->get("db"));
            $user->find("name", $post->user);

            $post->img = $this->gravatar($user->email);
            $post->markdown = $this->getMD($post->text);

            return $post;
        }, $posts);
    }

    /**
     * return question/answer, three attributes are set, comments connected to them is an array.
     *
     * @return object
    */
        public function getPost($id)
        {
            $post = $this->find("id", $id);

            // Get user who posted
            $user = new User();
            $user->setDb($this->di->get("db"));
            $user->find("name", $post->user);

            // Start setting attributes
            $post->comments = $this->getPosts("parentId = ? AND type = ?", [$id, "comment"]);
            $post->img = $this->gravatar($user->email);
            $post->markdown = $this->getMD($post->text);

            return $post;
        }



    /**
     * Check if a post belongs to user
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
