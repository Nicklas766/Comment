<?php

namespace Nicklas\Comment\Modules;

/**
 * Test cases for class Comment
 */
class CommentTest extends \PHPUnit_Framework_TestCase
{


    protected $comment;
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
        $this->comment = new Comment(self::$di->get("db"));
    }

    /**
     * Test case to construct object and verify that the object
     * has the expected properties due various ways of constructing
     * it.
     */
    public function testCreateObject()
    {
        $comment = new Comment(self::$di->get("db"));
        $this->assertInstanceOf("Nicklas\Comment\Modules\Comment", $comment);
    }

    /**
     * Test case for GetPost function
     * Controls that the return is correct regarding to the function.
     */
    public function testGetComments()
    {
        // Should have 2 likes since on question 2 the first comment was made in db
        // First comment should be connected to post 2
        $comment = $this->comment->getComments("parentId = ?", [2]);
        $this->assertEquals($comment[0]->vote->score, 2);
        $this->assertEquals($comment[0]->user, "kalle");

        // // First should be connected to post 2
        $comment = $this->comment->getComments("parentId = ?", [3]);
        $this->assertEquals($comment[0]->user, "sven");
        $this->assertEquals($comment[0]->vote->score, 0);
    }
}
