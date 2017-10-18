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
    public function getQuestions($sql, $params)
    {
        $questions = $this->findAllWhere("$sql", $params);

        return array_map(function ($question) {

            // Find users email
            $user = new User();
            $user->setDb($this->di->get("db"));
            $user->find("name", $question->user);

            // Start setting attributes
            $question->img = $this->gravatar($user->email);
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
        $post = new Posts();
        $post->setDb($this->di->get("db"));

        $question->question = $post->getPost("questionId = ? AND type = ?", [$post->id, "question"]);
        $question->answers = $post->getAllPosts("questionId = ? AND type = ?", [$post->id, "answer"]);
        $question->answersCount = count($question->answers);

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

        if (array_key_exists('', $tagArr)) {
            unset($tagArr[""]);
        }
        return $tagArr;
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
