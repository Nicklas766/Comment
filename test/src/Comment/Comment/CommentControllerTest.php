<?php

namespace Nicklas\Comment;

/**
 * Test cases for class Comment
 */
class CommentControllerTest extends \PHPUnit_Framework_TestCase
{

    protected $commentController;

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
        $this->commentController = self::$di->get("commentFrontController");
    }

    /**
     * Test case for getIndex function
     * Controls that the return is correct regarding to the function.
     */
    public function testGetUserDetails()
    {
        // $this->commentController->getIndex();
    }
}
