<?php

namespace Nicklas\Comment\HTMLForm;

use Nicklas\Comment\User;

/**
 * Test cases for class Navbar
 */
class EditUserFormTest extends \PHPUnit_Framework_TestCase
{
    protected $form;

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
        // setup user
        $this->user = new User();
        $this->user->setDb(self::$di->get("db"));
        $this->user->name = "James";
        $this->user->setPassword("password");
        $this->user->authority = "user";
        $this->user->save();

        $this->lastId = self::$di->get("db")->lastInsertId();
    }

    protected function tearDown()
    {
        $this->user->delete();
    }

    /**
     * Test case for construct function
     * Controls that the return is correct regarding to the function.
     */
    public function testConstruct()
    {
        $this->form = new EditUserForm(self::$di, $this->lastId);
        $this->assertEquals($this->form, new EditUserForm(self::$di, $this->lastId));
    }

    public function testCallBack()
    {
        $this->form = new EditUserForm(self::$di, $this->lastId);
        $this->form->callbackSubmit();
    }
}
