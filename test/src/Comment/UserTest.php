<?php

namespace Nicklas\Comment;

/**
 * Test cases for class Navbar
 */
class UserTest extends \PHPUnit_Framework_TestCase
{

    protected $di;
    protected $user;

    /**
    * Test cases requires DI-container, therefore save in constructor
    */
    public function setUp()
    {
        $this->di = new \Anax\DI\DIFactoryConfig("testDI.php");
        $this->user = new User($this->di);
        $this->user->setDb($this->di->get("db"));
        $this->user->email = "user@anaxtesting.com";
    }
    /**
     * Test case to construct object and verify that the object
     * has the expected properties due various ways of constructing
     * it.
     */
    public function testCreateObject()
    {
        $this->assertInstanceOf("Nicklas\Comment\User", $this->user);
    }

    /**
     * Test case for UserExists function
     * Controls that the return is correct regarding to the function.
     */
    public function testUserExists()
    {
        $user = $this->user;
        $this->assertEquals(is_bool($user->userExists("")), true); ## return should be bool

        $this->assertEquals($user->userExists("admin"), true); ## should return true
        $this->assertEquals($user->userExists("thisdoesntexist"), false);
    }

    /**
     * Test case for setGravatar function
     * Controls that the return is correct regarding to the function.
     */
    public function testSetGravatar()
    {
        $this->user->setGravatar();

        $this->assertNotEquals($this->user->img, null);
    }

    /**
     * Test case for setPassword function
     * Controls that the return is correct regarding to the function.
     */
    public function testSetPassword()
    {

        $this->assertEquals($this->user->pass, null);

        $this->user->setPassword("myPassword");
        $this->assertNotEquals($this->user->pass, null);
    }

    /**
     * Test case for verifyPassword function
     * Controls that the return is correct regarding to the function.
     */
    public function testVerifyPassword()
    {

        $this->user->name = "Andrew";
        $this->user->setPassword("password");

        $returnedValue = $this->user->verifyPassword("Andrew", "password");
        $this->assertEquals($returnedValue, true);

        $returnedValue = $this->user->verifyPassword("Andrew", "password2");
        $this->assertEquals($returnedValue, false);
    }
}
