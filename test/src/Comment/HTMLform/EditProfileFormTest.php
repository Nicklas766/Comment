<?php

namespace Nicklas\Comment\HTMLForm\Profile;

use Nicklas\Comment\Modules\User;
use Nicklas\Comment\Modules\Comment;

/**
 * Test cases for class Navbar
 */
class EditProfileFormTest extends \PHPUnit_Framework_TestCase
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
     * Test case for construct function
     */
    public function testConstruct()
    {
        $form = new EditProfileForm(self::$di, "user");
        $this->assertEquals($form, new EditProfileForm(self::$di, "user"));
    }

    /**
     * Should return false since no session
     * Controls that the return is correct regarding to the function.
     */
    public function testCallback()
    {
        $user = new User(self::$di);
        $user->setDb(self::$di->get("db"));
        $user->name = "Ted";
        $user->setPassword("password");
        $user->email = "user@anaxtesting.com";
        $user->save();


        $form = new EditProfileForm(self::$di, "Ted");
        $this->assertEquals($form->callbackSubmit(), false);
    }
}
