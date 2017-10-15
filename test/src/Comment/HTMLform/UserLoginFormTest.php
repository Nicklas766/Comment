<?php

namespace Nicklas\Comment\HTMLForm\User;

use Nicklas\Comment\Modules\User;
use Nicklas\Comment\Modules\Comment;

/**
 * Test cases for class Navbar
 */
class UserLoginFormTest extends \PHPUnit_Framework_TestCase
{
    protected static $di;

    /**
     * Test cases requires DI-container, therefore save in constructor
     */

    public static function setUpBeforeClass()
    {
        self::$di = new \Anax\DI\DIFactoryConfig("testDI.php");
    }
    /**
     * Should return false since no value submitted
     */
    public function testNoInputCallback()
    {
        $form = new UserLoginForm(self::$di);
        $this->assertEquals($form->callbackSubmit(), false);
    }
}
