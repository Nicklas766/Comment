<?php

namespace Nicklas\Comment\HTMLForm;

use Nicklas\Comment\User;

/**
 * Test cases for class Navbar
 */
class EditUserFormTest extends \PHPUnit_Framework_TestCase
{
    protected $di;
    protected $form;

    /**
     * Test cases requires DI-container, therefore save in constructor
     */
    public function setUp()
    {
        $this->di = new \Anax\DI\DIFactoryConfig("testDI.php");

        // setup user
        $this->user = new User();
        $this->user->setDb($this->di->get("db"));
        $this->user->name = "James";
        $this->user->setPassword("password");
        $this->user->save();

        $this->lastId = $this->di->get("db")->lastInsertId();
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
        $this->form = new EditUserForm($this->di, $this->lastId);
    }

    public function testCallBack()
    {
        $this->form = new EditUserForm($this->di, $this->lastId);
        $this->form->callbackSubmit();
    }
}
