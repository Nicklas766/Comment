<?php

namespace Nicklas\Page;

/**
 * Test cases for class Comment
 */
class PageRenderTest extends \PHPUnit_Framework_TestCase
{

    protected $pageRender;

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
        $this->pageRender = self::$di->get("pageRenderComment");
    }

    /**
     * Test case for SetArray function
     * Controls that the return is correct regarding to the function.
     */
    public function testSetArray()
    {
        $validArr = [
            "0" => ["link" => "hello1"],
            "1" => ["link" => "hello2"],
            "2" => ["link" => "hello3"],
        ];
        $files = ["hello1", "hello2", "hello3"];
        $returnedValue = $this->pageRender->setArray($files, "link");

        $this->assertEquals($validArr, $returnedValue);
    }

    /**
     * Test case for add function
     * Controls that the return is correct regarding to the function.
     */
    public function testAdd()
    {
        $validArr = [
            ["view" => "admin/navbar", "content" => [], "region" => "main"]
        ];
        $view = ["admin/navbar", [], "main"];
        $returnedArray = $this->pageRender->add($view);
        $this->assertEquals($validArr, $returnedArray);

        // if multiple views create indexes for them
        $validArr2 = [
            ["view" => "page/home/greeting", "content" => [], "region" => "home"],
            ["view" => "page/home/content", "content" => [], "region" => "home"],
            ["view" => "page/home/content2", "content" => [], "region" => "home"]
        ];
        $views = [["page/home/greeting", "page/home/content", "page/home/content2"], [], "home"];
        $returnedArray2 = $this->pageRender->add($views);
        $this->assertEquals($validArr2, $returnedArray2);

        // if multiple content (multideminsional) create indexes for them
        $view3 = ["user/profile", [["name" => "nicklas"]], "home"];
        $validArr3 = [
            ["view" => "user/profile", "content" => ["name" => "nicklas"], "region" => "home"],
        ];
        $returnedArray3 = $this->pageRender->add($view3);
        $this->assertEquals($validArr3, $returnedArray3);
    }

    /**
     * Test case for getIndex function
     * Controls that the return is correct regarding to the function.
     */
    public function testViewify()
    {
        $this->pageRender->viewify([]);
    }
}
