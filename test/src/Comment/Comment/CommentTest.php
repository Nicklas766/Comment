<?php

namespace Nicklas\Comment;

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
        $this->comment = new Comment(self::$di);
        $this->comment->setDb(self::$di->get("db"));
    }

    /**
     * Test case to construct object and verify that the object
     * has the expected properties due various ways of constructing
     * it.
     */
    public function testCreateObject()
    {
        $comment = new Comment();
        $this->assertInstanceOf("Nicklas\Comment\Comment", $comment);
    }

    /**
     * Test case for GetALl function
     * Controls that the return is correct regarding to the function.
     */
    public function testGetAll()
    {
        $returnedValue = is_array($this->comment->getAll());

        $this->assertContainsOnly(new Comment(), $this->comment->getAll()); # array should only contain Comment objects
        $this->assertEquals($returnedValue, true);
    }

    /**
     * Test case for get function
     * Controls that the return is correct regarding to the function.
     */
    public function testGet()
    {
        $returnedValue = is_object($this->comment->get("1"));
        $this->assertEquals($returnedValue, true);
    }

    /**
     * Test case for controlAuthority function
     * Controls that the return is correct regarding to the function.
     */
    public function testControlAuthority()
    {
        $returnedValue = $this->comment->controlAuthority("admin");
        $this->assertEquals($returnedValue, true);

        $returnedValue = $this->comment->controlAuthority("user");
        $this->assertEquals($returnedValue, false);
    }
}
