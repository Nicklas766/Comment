<?php

namespace Nicklas\Comment;

/**
 * Test cases for class Comment
 */
class CommentTest extends \PHPUnit_Framework_TestCase
{

    protected $di;
    protected $comment;

    /**
     * Test cases requires DI-container, therefore save in constructor
     */
    public function setUp()
    {
        $this->di = new \Anax\DI\DIFactoryConfig("testDI.php");
        $this->comment = new Comment($this->di);
        $this->comment->setDb($this->di->get("db"));
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
