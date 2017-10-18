<?php

namespace Nicklas\Comment\Modules;

/**
 * A database driven model.
 */
class Post extends ActiveRecordModelExtender
{

        /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "ramverk1_posts";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $user;
    public $questionId; # Connection to new Question()

    public $type; # question/answer
    public $text;

    public $created;

    public $di;



    /**
     * Returns post with markdown and gravatar
     * @param string $sql
     * @param array $param
     *
     * @return array
     */
    public function getAllPosts($sql, $params)
    {
        $posts = $this->findAllWhere("$sql", $params);

        return array_map(function ($post) {

            // Get e-mail for Post
            $user = new User($this->db);
            $user->find("name", $post->user);
            $post->img = $this->gravatar($user->email);

            // Get comments for Post
            $comment = new Comment($this->db);
            $post->comments = $comment->getComments("parentId = ?", [$post->id]);

            // Get text
            $post->markdown = $this->getMD($post->text);

            return $post;
        }, $posts);
    }

    /**
     * return question/answer, three attributes are set, comments connected to them is an array.
     *
     * @return object
    */
    public function getPost($sql, $params)
    {
        $post = $this->findWhere("$sql", $params);

        // Get e-mail for question
        $user = new User($this->db);
        $user->find("name", $post->user);
        $post->img = $this->gravatar($user->email);


        $comment = new Comment($this->db);
        // Start setting attributes
        $post->img = $this->gravatar($user->email);
        $post->markdown = $this->getMD($post->text);
        $post->comments = $comment->getComments("parentId = ?", [$post->id]);

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
