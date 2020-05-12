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
class ContentControllerLogInOutResetTest extends TestCase
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
     * Call the controller login action GET.
     */
    public function testLoginActionGet()
    {
        $res = $this->controller->loginActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller login action POST Ok.
     */
    public function testLoginActionPostOk()
    {
        $this->app->request->setGlobals([
            "post" => [
                "user" => "doe",
                "password" => "doe",
            ]
        ]);
        $res = $this->controller->loginActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "content1/showAll"));
        $contentUser = $this->app->session->get("contentUser");
        $exp = "doe";
        $this->assertEquals($exp, $contentUser);
    }

    /**
     * Call the controller login action POST Not Ok.
     */
    public function testLoginActionPostNotOk()
    {
        $this->app->request->setGlobals([
            "post" => [
                "user" => "doe",
                "password" => "HEJ",
            ]
        ]);
        $res = $this->controller->loginActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "content1/login"));
        $msg = $this->app->session->get("loginMessage");
        $exp = "Faulty login, try again!";
        $this->assertEquals($exp, $msg);
    }

    /**
     * Call the controller logout action GET.
     */
    public function testLogoutActionGet()
    {
        $this->app->session->set("contentUser", "doe");

        $res = $this->controller->logoutActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller logout action POST Ok.
     */
    public function testLogoutActionPostOk()
    {
        $res = $this->controller->logoutActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "content1/showAll"));
    }

    /**
     * Call the controller reset action GET with reset.
     */
    public function testResetActionGet()
    {
        $this->app->session->set("reset", "Reset database");

        $res = $this->controller->resetActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller reset action POST.
     */
    public function testResetActionPost()
    {
        $this->app->request->setGlobals([
            "post" => [
                "reset" => "Reset database",
            ]
        ]);
        $res = $this->controller->resetActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "content1/reset"));
        $reset = $this->app->session->get("reset");
        $exp = "Reset database";
        $this->assertEquals($exp, $reset);
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
