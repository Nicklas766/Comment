<?php

namespace Nicklas\Comment;

/**
 * Test cases for class Comment
 */
class UserControllerTest extends \PHPUnit_Framework_TestCase
{

    protected $di;
    protected $userController;

    /**
     * Test cases requires DI-container, therefore save in constructor
     */
    public function setUp()
    {
        $this->di = new \Anax\DI\DIFactoryConfig("testDI.php");
        $this->userController = $this->di->get("commentFrontController");
    }

    /**
     * Test case to construct object and verify that the object
     * has the expected properties due various ways of constructing
     * it.
     */
    public function testCreateObject()
    {
        $this->assertInstanceOf("Nicklas\Comment\UserController", $this->userController);
    }

    /**
     * Test case for getIndex function
     * Controls that the return is correct regarding to the function.
     */
    public function testGetUserDetails()
    {

        $user = new User();
        $user->setDb($this->di->get("db"));
        $user->name = "Andrew";
        $user->setPassword("password");
        $user->save();

        $returnedUser = $this->userController->getUserDetails("Andrew");

        $this->assertNotEquals($returnedUser, $user);

        $returnedUser->delete();
    }

    /**
    *   CANT BE TESTED SINCE EXIT ON REDIRECT
     * Test case for logout function
     * Controls that the return is correct regarding to the function.
     */
    public function testLogout()
    {
        // $this->di->get("session")->set("user", "Andrew");
        // $returnedValue = $this->di->get("session")->get("user");
        // $this->assertEquals($returnedValue, "Andrew");
        //
        // $this->userController->logout();
        // $returnedValue = $this->di->get("session")->get("user");
        // $this->assertEquals($returnedValue, null);

        // $this->userController
    }
}
