<?php

namespace Nicklas\Comment\HTMLForm;

use Nicklas\Comment\User;
use Nicklas\Comment\Comment;

/**
 * Test cases for class Navbar
 */
class EditCommentFormTest extends \PHPUnit_Framework_TestCase
{
    protected $di;
    protected $form;
    protected $comment;
    protected $user;

    /**
     * Test cases requires DI-container, therefore save in constructor
     */
    public function setUp()
    {
        $this->di = new \Anax\DI\DIFactoryConfig("testDI.php");

        // create user
        $this->user = new User($this->di);
        $this->user->setDb($this->di->get("db"));
        $this->user->name = "Steve";
        $this->user->setPassword("password");
        $this->user->save();

        //create comment
        $this->comment = new Comment();
        $this->comment->setDb($this->di->get("db"));
        $this->comment->user = $this->user->name;
        $this->comment->comment = "comment text";
        $this->comment->save();

        // save comment id
        $this->lastId = $this->di->get("db")->lastInsertId();
    }

    protected function tearDown()
    {
        $this->comment->delete();
        $this->user->delete();
    }

    /**
     * Test case for construct function
     * Controls that the return is correct regarding to the function.
     */
    public function testConstruct()
    {
        $this->form = new EditCommentForm($this->di, $this->lastId);
    }
}
