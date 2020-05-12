<?php

namespace Epkmagr\Content;

use Anax\Controller\SampleAppController;
use Anax\DI\DIMagic;
use PHPUnit\Framework\TestCase;
use Anax\Response\ResponseUtility;

/**
 * Test the controller like it would be used from the router,
 * simulating the actual router paths and calling it directly.
 */
class ContentControllerTest extends TestCase
{
    /**
     * @var ContentController $controller   The ContentController to be tested.
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

        $this->controller = new ContentController();
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
     * Call the controller showAll action.
     */
    public function testShowAllAction()
    {
        $res = $this->controller->showAllAction();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller showAll action logged in.
     */
    public function testShowAllActionLoggedIn()
    {
        $this->app->session->set("contentUser", "doe");

        $res = $this->controller->showAllAction();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }
}
