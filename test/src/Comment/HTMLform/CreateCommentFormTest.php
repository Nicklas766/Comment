<?php

namespace Nicklas\Comment\HTMLForm;

/**
 * Test cases for class Navbar
 */
class CreateCommentFormTest extends \PHPUnit_Framework_TestCase
{
    protected $form;

    /**
     * Test cases requires DI-container, therefore save in constructor
     */
     protected static $di;

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
        $this->form = new CreateCommentForm(self::$di);
    }

    public function testCallBack()
    {
        $this->form = new CreateCommentForm(self::$di);
        $this->form->callbackSubmit();
    }
}
