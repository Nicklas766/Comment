<?php

namespace Nicklas\Comment\HTMLForm;

/**
 * Test cases for class Navbar
 */
class CreateCommentFormTest extends \PHPUnit_Framework_TestCase
{
    protected $di;
    protected $form;

    /**
     * Test cases requires DI-container, therefore save in constructor
     */
    public function setUp()
    {
        $this->di = new \Anax\DI\DIFactoryConfig("testDI.php");
    }

    /**
     * Test case for construct function
     * Controls that the return is correct regarding to the function.
     */
    public function testConstruct()
    {
        $this->form = new CreateCommentForm($this->di);
    }
    
    public function testCallBack()
    {
        $this->form = new CreateCommentForm($this->di);
        $this->form->callbackSubmit();
    }
}
