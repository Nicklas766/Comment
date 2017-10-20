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
    public $accepted; # default "no"

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
            $post->img = $user->getGravatar($post->user);

            // Get comments for Post
            $comment = new Comment($this->db);
            $post->comments = $comment->getComments("parentId = ?", [$post->id]);


            // Get votes for Post
            $vote = new Vote($this->db);
            $post->vote = $vote->getVote("parentId = ? AND parentType = ?", [$post->id, "post"]);

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
        $post->img = $user->getGravatar($post->user);

        // Get comments for Post
        $comment = new Comment($this->db);
        $post->comments = $comment->getComments("parentId = ?", [$post->id]);

        // Get votes for Post
        $vote = new Vote($this->db);
        $post->vote = $vote->getVote("parentId = ? AND parentType = ?", [$post->id, "post"]);
        
        // Start setting attributes
        $post->markdown = $this->getMD($post->text);


        return $post;
    }
}
