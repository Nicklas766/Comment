<?php

namespace Nicklas\Comment;

/**
 * Test cases for class Comment
 */
class FrontControllerTest extends \PHPUnit_Framework_TestCase
{

    protected $frontController;

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
        $this->frontController = self::$di->get("commentFrontController");
    }


    /**
     * Test case for getIndex function
     * Controls that the return is correct regarding to the function.
     */
    public function testAcceptedAnswer()
    {
        // Kalle made question 1, post 2 is connected to question 1
        self::$di->get("session")->set("user", "kalle");
        $this->assertEquals($this->frontController->postAcceptedAnswer(2), true);

        self::$di->get("session")->set("user", "nicklas");
        $this->assertEquals($this->frontController->postAcceptedAnswer(2), false);

        self::$di->get("session")->set("user", "admin");
        $this->assertEquals($this->frontController->postAcceptedAnswer(2), true);
    }
}
