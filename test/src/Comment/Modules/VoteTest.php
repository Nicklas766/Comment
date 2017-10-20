<?php

namespace Nicklas\Comment\Modules;

/**
 * Test cases for class Comment
 */
class VoteTest extends \PHPUnit_Framework_TestCase
{


    protected $vote;
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
        $this->vote = new Vote(self::$di->get("db"));
    }

    /**
     * Test case to construct object and verify that the object
     * has the expected properties due various ways of constructing
     * it.
     */
    public function testCreateObject()
    {
        $vote = new Comment(self::$di->get("db"));
        $this->assertInstanceOf("Nicklas\Comment\Modules\Comment", $vote);
    }

    /**
     * Test case for TestLike function
     * Controls that the return is correct regarding to the function.
     */
    public function testLike()
    {
        // kalle has liked post 1 in db
        $vote = $this->vote;
        $this->assertEquals($vote->like("kalle", 1, "post"), false);

        $vote = $this->vote;
        $this->assertEquals($vote->like("sven", 1, "post"), true);

        // sven has liked comment 1 in db
        $vote = $this->vote;
        $this->assertEquals($vote->like("sven", 1, "comment"), false);
    }

    /**
     * Test case for DisLike function
     * Controls that the return is correct regarding to the function.
     */
    public function testDislike()
    {
        // sven has disliked post 1 in db
        $vote = $this->vote;
        $this->assertEquals($vote->dislike("sven", 1, "post"), false);

        $vote = $this->vote;
        $this->assertEquals($vote->dislike("kalle", 1, "post"), true);

        $vote = $this->vote;
        $this->assertEquals($vote->dislike("sven", 1, "comment"), true);
    }

    /**
     * Test case for GetVote function
     * Controls that the return is correct regarding to the function.
     */
    public function testGetVote()
    {
        // sven has disliked post 1 in db
        // jessica has disliked post 1 in db
        // anders has disliked post 1 in db
        // kalle has liked post 1 in db
        $vote = $this->vote;
        $vote = $vote->getVote("parentId = ? AND parentType = ?", [1, "post"]);
        $this->assertEquals($vote->score, -2);

        // sven has liked comment 1 in db
        // jessica has liked comment 1 in db
        // anders has liked comment 1 in db
        // kalle has disliked comment 1 in db
        $vote = $this->vote;
        $vote = $vote->getVote("parentId = ? AND parentType = ?", [1, "comment"]);
        $this->assertEquals($vote->score, 2);
    }
}
