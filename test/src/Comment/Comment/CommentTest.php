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
        $this->assertInstanceOf("Nicklas\Comment\Modules\Comment", $comment);
    }

    /**
     * Test case for GetPost function
     * Controls that the return is correct regarding to the function.
     */
    public function testGetPost()
    {
        // First should be question
        $comment = $this->comment->getPost(1);
        $this->assertEquals($comment->user, "kalle");
        $this->assertEquals($comment->type, "question");

        // Second should be answer
        $comment = $this->comment->getPost(2);
        $this->assertEquals($comment->user, "sven");
        $this->assertEquals($comment->type, "answer");

        // third should be comment
        $comment = $this->comment->getPost(3);
        $this->assertEquals($comment->user, "kalle");
        $this->assertEquals($comment->type, "comment");
    }

    /**
     * Test case for GetPosts function
     * Controls that the return is correct regarding to the function.
     */
    public function testGetPosts()
    {
        // Should have length of 2, also have type "comment"
        $comments = $this->comment->getPosts("parentId = ? AND type = ?", [1, "comment"]);
        $this->assertEquals(count($comments), 2);
        $this->assertEquals($comments[1]->type, "comment");

        // Should have all questions made by user "kalle"
        $questions = $this->comment->getPosts("user = ? AND type = ?", ["kalle", "question"]);
        $this->assertEquals($questions[0]->user, "kalle");
        $this->assertEquals(count($questions), 1);

        // Should have all questions made by all users
        $questions = $this->comment->getPosts("type = ?", ["question"]);
        $this->assertEquals(count($questions), 2);
    }

    /**
     * Test case for GetPopularTags function
     * Controls that the return is correct regarding to the function.
     */
    public function testGetPopularTags()
    {
        // Should have all questions made by all users
        $tags = $this->comment->getPopularTags();
        var_dump($tags);
        $this->assertEquals(array_keys($tags)[0], "#kaffe");
    }

    /**
     * Test case for controlAuthority function
     * Controls that the return is correct regarding to the function.
     */
    public function testControlAuthority()
    {
        $comment = $this->comment->getPost(1);

        // Returns true since user is admin
        $returnedValue = $comment->controlAuthority("admin");
        $this->assertEquals($returnedValue, true);

        // Returns true since user made question
        $returnedValue = $comment->controlAuthority("kalle");
        $this->assertEquals($returnedValue, true);

        // Returns false since user didn't create question
        $returnedValue = $comment->controlAuthority("sven");
        $this->assertEquals($returnedValue, false);
    }
}
