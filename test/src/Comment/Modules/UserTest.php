<?php

namespace Nicklas\Comment\Modules;

/**
 * Test cases for class Navbar
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    protected $user;

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
        $this->user = new User(self::$di);
        $this->user->setDb(self::$di->get("db"));
        $this->user->email = "user@anaxtesting.com";
        $this->user->question = "lasagne";
    }
    /**
     * Test case to construct object and verify that the object
     * has the expected properties due various ways of constructing
     * it.
     */
    public function testCreateObject()
    {
        $this->assertInstanceOf("Nicklas\Comment\Modules\User", $this->user);
    }


    /**
     * Test case for GetAllUsers function
     * Controls that the return is correct regarding to the function.
     */
    public function testGetAllUsers()
    {
        $user = $this->user;

        // Count should be 7
        $users = $user->getAllUsers();
        $this->assertEquals(count($users), true);

        // Count should be 1
        $users = $user->getAllUsers("authority = ?", "admin");
        $this->assertEquals(count($users), 1);

        // Count should be 6
        $users = $user->getAllUsers("authority = ?", "user");
        $this->assertEquals(count($users), 6);

        // kalle should have his posts
        $users = $user->getAllUsers();
        $this->assertEquals($users[3]->questions[0]->user, "kalle");

        //control markdown
        $this->assertEquals((strpos($users[3]->questions[0]->title, 'Fråga om kaffe koppar') !== false), true);
        $this->assertEquals(($users[3]->questions[0]->title == 'Fråga om kaffe koppar'), false);


        $this->assertEquals($users[3]->questions[0]->user, "kalle");

        // Should be 0 posts (type = answer) and 3 comments
        $this->assertEquals(count($users[3]->posts), 0);
        $this->assertEquals(count($users[3]->comments), 3);


        // make sure user has gravatar
        $this->assertEquals($users[3]->img, "https://www.gravatar.com/avatar/64e1b8d34f425d19e1ee2ea7236d3028&s=40");
    }

    /**
     * Test case for GetUser function
     * Controls that the return is correct regarding to the function.
     */
    public function testGetUser()
    {
        $user = $this->user->getUser("sven");

        // Should be 0 posts (type = answer) and 3 comments
        $this->assertEquals($user->name, "sven");
        $this->assertEquals($user->img, "https://www.gravatar.com/avatar/64e1b8d34f425d19e1ee2ea7236d3028&s=40");

        // Nicklas766
        $user = $this->user->getUser("nicklas766");
        $this->assertEquals($user->name, "nicklas766");
        $this->assertEquals($user->img, "https://www.gravatar.com/avatar/b4bf6c4b666412b90998cdf0db854a15&s=40");
        $this->assertEquals(count($user->comments), 0);
        $this->assertEquals(count($user->posts), 0);
        $this->assertEquals(count($user->question), 1);
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

    /**
     * Test case for verifyPassword function
     * Controls that the return is correct regarding to the function.
     */
    public function testVerifyQuestion()
    {
        $returnedValue = $this->user->verifyQuestion("kalle", "lasagne");
        $this->assertEquals($returnedValue, true);

        $returnedValue = $this->user->verifyPassword("kalle", "falskt");
        $this->assertEquals($returnedValue, false);
    }
}
