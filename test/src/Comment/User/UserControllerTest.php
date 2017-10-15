<?php

namespace Nicklas\Comment;

use Nicklas\Comment\Modules\User;

/**
 * Test cases for class Comment
 */
class UserControllerTest extends \PHPUnit_Framework_TestCase
{

    protected $userController;

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
        $this->userController = self::$di->get("commentFrontController");
    }


    /**
     * Test case for getIndex function
     * Controls that the return is correct regarding to the function.
     */
    public function testGetUserDetails()
    {

        $user = new User();
        $user->setDb(self::$di->get("db"));
        $user->name = "Markus";
        $user->setPassword("password");
        $user->save();

        $returnedUser = $this->userController->getUserDetails("Markus");

        $this->assertNotEquals($returnedUser, $user);

        $returnedUser->delete();
    }
}
