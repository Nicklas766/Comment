<?php

namespace Nicklas\Comment\Modules;

/**
 * A database driven model.
 */
class Question extends ActiveRecordModelExtender
{

        /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "ramverk1_questions";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $user;

    public $title;
    public $tags;

    public $created;
    public $status; # default is active


    /**
     * Returns post with markdown and gravatar
     * @param string $sql
     * @param array $param
     *
     * @return array
     */
    public function getQuestions($sql = null, $params = null)
    {
        if ($sql == null) {
            $questions = $this->findAll();
        }
        if ($sql != null) {
            $questions = $this->findAllWhere($sql, $params);
        }

        return array_map(function ($question) {
            // User email
            $user = new User($this->db);
            // Start setting attributes
            $question->img = $user->getGravatar($question->user);
            $question->title = $this->getMD($question->title);
            $question->tags = explode(',', $question->tags);

            return $question;
        }, $questions);
    }

    /**
    * Returns one question with it's own question text and other answers
    * @param int $id
    *
     * @return object
    */
    public function getQuestion($id)
    {
        $question = $this->find("id", $id);

        // Add attributes from Post class
        $post = new Post($this->db);

        $question->question = $post->getPost("questionId = ? AND type = ?", [$question->id, "question"]);
        $question->answers = $post->getAllPosts("questionId = ? AND type = ?", [$question->id, "answer"]);
        $question->answerCount = count($question->answers);

        // Start setting up own attributes
        $question->title = $this->getMD($question->title);
        $question->tags = explode(',', $question->tags);


        return $question;
    }

    /**
     * Returns array of tags, keys are name, value is the integer of how many.
     *
     * @return array
    */
    public function getPopularTags()
    {
        $question = $this->findAll();

        $tagsMultiArray = array_map(function ($question) {
            return explode(',', $question->tags);
        }, $question);

        // Merge multi array, count values, sort high to low
        $tagArr = array_count_values(call_user_func_array("array_merge", $tagsMultiArray));
        arsort($tagArr);
        return $tagArr;
    }
}
