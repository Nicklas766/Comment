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
        $this->user = new User(self::$di->get("db"));
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

        // get all should be 1
        $questions = $this->question->getQuestions("user = ?", "kalle");
        $this->assertEquals(count($questions), 1);

        // Get question 1
        $question = $this->question->getQuestion(1);
        $this->assertEquals($question->user, "kalle");
        $this->assertEquals($question->answerCount, 2);
    }


    /**
     * Test case for GetPopularTags function
     * Controls that the return is correct regarding to the function.
     */
    public function testGetPopularTags()
    {
        // Should have all questions made by all users
        $tags = $this->question->getPopularTags();
        $this->assertEquals(array_keys($tags)[0], "kaffe");
    }

    // /**
    //  * Test case for controlAuthority function
    //  * Controls that the return is correct regarding to the function.
    //  */
    public function testControlAuthority()
    {
        // Control authority for kalles question
        $question = $this->question->getQuestion(1);
        $this->assertEquals($this->user->controlAuthority("admin", $question->user), true);
        $this->assertEquals($this->user->controlAuthority("kalle", $question->user), true);
        $this->assertEquals($this->user->controlAuthority("sven", $question->user), false);
    }
}
