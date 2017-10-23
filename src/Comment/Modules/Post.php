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
    protected $tableName = "coffee_posts";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $user;
    public $questionId; # Connection to new Question()
    public $accepted; # default "no"

    public $type; # question/answer
    public $text;

    public $created;

    public $di;


    /**
     * Set ups the question
     * @param object $post
     *
     * @return object
     */
    public function setupPost($post)
    {

        $user    = new User($this->db);
        $comment = new Comment($this->db);
        $vote    = new Vote($this->db);

        $post->img      = $user->getGravatar($post->user);
        $post->comments = $comment->getComments("parentId = ?", [$post->id]);
        $post->vote     = $vote->getVote("parentId = ? AND parentType = ?", [$post->id, "post"]);
        $post->markdown = $this->getMD($post->text);

        return $post;
    }

    /**
     * Returns post with markdown and gravatar
     * @param string $sql
     * @param array $param
     *
     * @return objects[]
     */
    public function getAllPosts($sql, $params)
    {
        $posts = $this->findAllWhere("$sql", $params);

        // array_reverse so latest order question gets returned
        return array_map(array($this, 'setupPost'), $posts);
    }

    /**
     * return question/answer, three attributes are set, comments connected to them is an array.
     * @param string $sql
     * @param array $param
     *
     * @return object
    */
    public function getPost($sql, $params)
    {
        $post = $this->findWhere("$sql", $params);
        return $post->setupPost($post);
    }
}
