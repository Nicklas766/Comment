<?php

namespace Nicklas\Comment\Modules;

/**
 * Test cases for class Question
 */
class QuestionTest extends \PHPUnit_Framework_TestCase
{


    protected $question;
    protected static $di;

    public static function setUpBeforeClass()
    {
        self::$di = new \Anax\DI\DIFactoryConfig("testDI.php");
    }
    /**
     * Test cases requires DI-container, therefore save in constructor
     */
    public function setUp()
    {
        $this->question = new Question(self::$di->get("db"));
    }

    /**
     * Test case for GetPost function
     * Controls that the return is correct regarding to the function.
     */
    public function testGetQuestions()
    {
        // get all should be 2
        $questions = $this->question->getQuestions();
        $this->assertEquals(count($questions), 2);

        // Get question 1
        $question = $this->question->getQuestion(1);
        $this->assertEquals($question->user, "kalle");
    }

    /**
     * Test case for GetPosts function
     * Controls that the return is correct regarding to the function.
     */
    // public function testGetPosts()
    // {
    //     // Should have length of 2, also have type "comment"
    //     $questions = $this->question->getPosts("parentId = ? AND type = ?", [1, "comment"]);
    //     $this->assertEquals(count($questions), 2);
    //     $this->assertEquals($questions[1]->type, "comment");
    //
    //     // Should have all questions made by user "kalle"
    //     $questions = $this->question->getPosts("user = ? AND type = ?", ["kalle", "question"]);
    //     $this->assertEquals($questions[0]->user, "kalle");
    //     $this->assertEquals(count($questions), 1);
    //
    //     // Should have all questions made by all users
    //     $questions = $this->question->getPosts("type = ?", ["question"]);
    //     $this->assertEquals(count($questions), 2);
    // }

    /**
     * Test case for GetPopularTags function
     * Controls that the return is correct regarding to the function.
     */
    // public function testGetPopularTags()
    // {
    //     // Should have all questions made by all users
    //     $tags = $this->question->getPopularTags();
    //     var_dump($tags);
    //     $this->assertEquals(array_keys($tags)[0], "#kaffe");
    // }

    /**
     * Test case for controlAuthority function
     * Controls that the return is correct regarding to the function.
     */
    // public function testControlAuthority()
    // {
    //     $question = $this->question->getPost(1);
    //
    //     // Returns true since user is admin
    //     $returnedValue = $question->controlAuthority("admin");
    //     $this->assertEquals($returnedValue, true);
    //
    //     // Returns true since user made question
    //     $returnedValue = $question->controlAuthority("kalle");
    //     $this->assertEquals($returnedValue, true);
    //
    //     // Returns false since user didn't create question
    //     $returnedValue = $question->controlAuthority("sven");
    //     $this->assertEquals($returnedValue, false);
    // }
}
