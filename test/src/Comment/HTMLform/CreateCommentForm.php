<?php

namespace Nicklas\Comment;

/**
 * Test cases for class Navbar
 */
class CreateCommentFormTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test case to construct object and verify that the object
     * has the expected properties due various ways of constructing
     * it.
     */
    public function testCreateObject()
    {
        $form = new CreateCommentForm();
        $this->assertInstanceOf("Nicklas\Comment\HTMLform\CreateCommentForm", $form);
    }
}
