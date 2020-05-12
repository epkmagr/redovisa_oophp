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
class ContentControllerShowPageAndBlogTest extends TestCase
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
     * Call the controller showPages action.
     */
    public function testShowPagesAction()
    {
        $res = $this->controller->showPagesAction();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller showPages action logged in.
     */
    public function testShowPagesActionLoggedIn()
    {
        $this->app->session->set("contentUser", "doe");

        $res = $this->controller->showPagesAction();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller showPage action.
     */
    public function testShowPageAction()
    {
        $res = $this->controller->showPageAction("hem");
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller showPage action with
     * unknown path.
     */
    public function testShowPageActionPathUnknown()
    {
        $res = $this->controller->showPageAction("unknown");
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller showBlog action.
     */
    public function testShowBlogAction()
    {
        $res = $this->controller->showBlogAction();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller showBlog action logged in.
     */
    public function testShowBlogActionLoggedIn()
    {
        $this->app->session->set("contentUser", "doe");

        $res = $this->controller->showBlogAction();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller showBlogPost action.
     */
    public function testShowBlogPostAction()
    {
        $res = $this->controller->showBlogPostAction("nu-har-sommaren-kommit");
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Help method to check if the object constains the location, if so
     * it returns true otherwise false.
     *
     * @return boolean as if location is found.
     */
    public function checkLocation($res, string $location)
    {
        // Check where to the redirect is (do you really need to assert this?)
        $headers = $res->getHeaders();
        $hasLocationHeader = false;
        foreach ($headers as $header) {
            if (substr($header, 0, 10) === "Location: ") {
                $hasLocationHeader = true;
                // The last part (a) is the url whereto redirect
                $this->assertContains($location, $header);
            }
        }
        return $hasLocationHeader;
    }
}
