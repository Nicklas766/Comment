<?php

namespace Nicklas\Comment\Modules;

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
    public $type; # question/answer/comment
    public $parentId; # All posts have different ids

    public $title;
    public $tags;
    public $text;

    public $created;

    public $di;


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
    public function getComments($sql, $params)
    {
        $posts = $this->findAllWhere("$sql", $params);

        return array_map(function ($post) {
            $user = new User();
            $user->setDb($this->di->get("db"));
            $user->find("name", $post->user);


            $post->comments = $this->getPosts("parentId = ? AND type = ?", [$post->id, "comment"]);
            $post->tags = explode(',', $post->tags);

            $post->img = $this->gravatar($user->email);
            $post->markdown = $this->getMD($post->text);

            return $post;
        }, $posts);
    }
}
