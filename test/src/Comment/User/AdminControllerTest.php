<?php

namespace Nicklas\Comment;

use Nicklas\Comment\Modules\User;

/**
 * Test cases for class Comment
 */
class AdminControllerTest extends \PHPUnit_Framework_TestCase
{

    protected $adminController;

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
        $this->adminController = self::$di->get("commentFrontController");
    }
    /**
     * Test case for getUsers function
     * Controls that the return is correct regarding to the function.
     */
    public function testGetUsers()
    {
        $user = new User();
        $user->setDb(self::$di->get("db"));
        $allUsers = $user->findAll();

        $allUsersWithGravatar = $this->adminController->getUsers();

        $this->assertNotEquals($allUsersWithGravatar, $allUsers);
        $this->assertNotEquals($allUsersWithGravatar[0]->img, null);
    }

    /**
     * Test case for checkIsAdmin function
     * Controls that the return is correct regarding to the function.
     */
    public function testCheckIsAdmin()
    {
        // should not fail
        self::$di->get("session")->set("user", "admin");
        $title = $this->adminController->checkIsAdmin();
        $this->assertEquals($title, null); ## should return false since no title

        // should fail
        self::$di->get("session")->set("user", "user");
        $title2 = $this->adminController->checkIsAdmin();
        $this->assertEquals($title2, null); ## "Not authorized"
    }


    /**
     * Test case for getUsersIndex function
     * Controls that the return is correct regarding to the function.
     */
    public function testGetUsersIndex()
    {
        $this->assertEquals($this->adminController->getUsersIndex(), null);
    }

    /**
     * These tests should be fixed since they actually dont test anything
     * They all use PageRender which exits.
     */
    public function testTheseNeedsToBeFixed()
    {

        self::$di->get("session")->set("user", "admin");
        
        $this->adminController->getPostAdminEditUser(1);

        // usercontroller
        $this->adminController->getPostLogin();
        $this->adminController->getPostCreateUser();
        $this->adminController->getPostEditUser();
        $this->adminController->renderProfile();

        // commentController
        $this->adminController->getIndex();
    }
}
