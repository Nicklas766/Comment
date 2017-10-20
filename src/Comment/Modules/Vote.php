<?php

namespace Nicklas\Comment\Modules;

/**
 * A database driven model.
 */
class Vote extends ActiveRecordModelExtender
{

        /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "ramverk1_votes";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $user; # question/answer/comment
    public $parentId; # All posts have different ids
    public $parentType; # post or comment
    public $upVote;
    public $downVote;


    /**
     * Return a vote
     * @param string $sql
     * @param array $params
     *
     * @return object
     */
    public function getVote($sql, $params)
    {
        $likes = $this->findAllWhere("$sql", $params);

        // Return users of the ones who have upvoted.
        $score = array_reduce($likes, function ($carry, $item) {
            $carry += $item->upVote - $item->downVote;
            return $carry;
        });

        $this->likes = $likes;
        $this->score = $score;
        return $this;
    }

    /**
     * Control if user has already liked or not
     * @param string $user
     * @param array $params
     *
     * @return bool
     */
    public function like($user, $parentId, $parentType)
    {
        $likes = $this->findAllWhere("parentId = ? AND parentType = ?", [$parentId, $parentType]);
        // Return users of the ones who have upvoted.
        $users = array_map(function ($like) {
            return $like->upVote != null ? $like->user : "";
        }, $likes);

        if (in_array($user, $users)) {
            return false;
        }
        $this->user = $user;
        $this->parentId = $parentId;
        $this->parentType = $parentId;
        $this->upVote = 1;
        $this->downVote = null;
        $this->save();
        return true;
    }

    /**
     * Control if user has already liked or not
     * @param string $user
     * @param array $params
     *
     * @return bool
     */
    public function dislike($user, $parentId, $parentType)
    {
        $likes = $this->findAllWhere("parentId = ? AND parentType = ?", [$parentId, $parentType]);
        // Return users of the ones who have upvoted.
        $users = array_map(function ($like) {
            return $like->downVote != null ? $like->user : "";
        }, $likes);

        if (in_array($user, $users)) {
            return false;
        }
        $this->user = $user;
        $this->parentId = $parentId;
        $this->parentType = $parentId;
        $this->upVote = null;
        $this->downVote = 1;
        $this->save();
        return true;
    }
}
