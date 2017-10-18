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
    public $user; # question/answer/comment
    public $parentId; # All posts have different ids

    public $text;

    public $created;

    /**
     * Returns post with markdown and gravatar
     * @param string $sql
     * @param array $param
     *
     * @return array
     */
    public function getComments($sql, $params)
    {
        $comments = $this->findAllWhere("$sql", $params);

        return array_map(function ($comment) {
            $user = new User($this->db);
            $comment->img = $user->getGravatar($comment->user);
            $comment->markdown = $this->getMD($comment->text);

            return $comment;
        }, $comments);
    }
}
