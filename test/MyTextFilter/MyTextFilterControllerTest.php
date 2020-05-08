<?php

namespace Epkmagr\MyTextFilter;

use Anax\Controller\SampleAppController;
use Anax\DI\DIMagic;
use PHPUnit\Framework\TestCase;
use Anax\Response\ResponseUtility;

/**
 * Test the controller like it would be used from the router,
 * simulating the actual router paths and calling it directly.
 */
class MyTextFilterControllerTest extends TestCase
{
    /**
     * @var MyTextFilterController $controller   The MyTextFilterController to be tested.
     * @var DIMagic  $app  The service container $di to contain $app as a service.
     */
    private $controller;
    private $app;

    /**
     * Setup the controller, before each testcase, just like the router
     * would set it up.
     */
    protected function setUp(): void
    {
        global $di;

        // Init service container $di to contain $app as a service
        $di = new DIMagic();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $app = $di;
        $this->app = $app;
        $di->set("app", $app);

        $this->controller = new MyTextFilterController();
        $this->controller->setApp($app);
        $this->controller->initialize();
    }

    /**
     * Call the controller index action.
     */
    public function testIndexAction()
    {
        $res = $this->controller->indexAction();
        $this->assertIsString($res);
        $this->assertContains("INDEX", $res);
    }

    /**
     * Call the controller bbcode action GET.
     */
    public function testBbcodeAction()
    {
        $res = $this->controller->bbcodeAction();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller link action.
     */
    public function testLinkAction()
    {
        $res = $this->controller->linkAction();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller markdown action.
     */
    public function testMarkdownAction()
    {
        $res = $this->controller->markdownAction();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller bbcode action.
     */
    public function testNl2brAction()
    {
        $res = $this->controller->nl2brAction();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }
}
