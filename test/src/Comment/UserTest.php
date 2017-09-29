<?php

namespace Nicklas\Comment;

/**
 * Test cases for class Navbar
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test case to construct object and verify that the object
     * has the expected properties due various ways of constructing
     * it.
     */
    public function testCreateObject()
    {
        $user = new User();
        $this->assertInstanceOf("Nicklas\Comment\User", $user);
    }

    /**
     * Test case for UserExists function
     * Controls that the return is correct regarding to the function.
     */
    public function testUserExists()
    {
    $user = new User();
    $db = new Database();
    $user->setDb($db);

    // test nl2br
    $this->assertEquals($user->userExists("admin"), true);
    }
}
