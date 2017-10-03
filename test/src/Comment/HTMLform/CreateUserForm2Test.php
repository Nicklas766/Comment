<?php

namespace Nicklas\Comment\HTMLForm;

/**
 * Test cases for class Navbar
 */
class CreateUserForm2Test extends \PHPUnit_Framework_TestCase
{
    protected $form;
    protected static $di;

    /**
     * Test cases requires DI-container, therefore save in constructor
     */

    public static function setUpBeforeClass()
    {
        self::$di = new \Anax\DI\DIFactoryConfig("testDI.php");
    }

    /**
     * Test case for construct function
     * Controls that the return is correct regarding to the function.
     */
    public function testConstruct()
    {
        $this->form = new CreateUserForm2(self::$di);
    }
}
