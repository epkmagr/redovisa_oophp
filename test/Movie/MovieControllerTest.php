<?php

namespace Epkmagr\Movie;

use Anax\Controller\SampleAppController;
use Anax\DI\DIMagic;
use PHPUnit\Framework\TestCase;
use Anax\Response\ResponseUtility;

/**
 * Test the controller like it would be used from the router,
 * simulating the actual router paths and calling it directly.
 */
class MovieControllerTest extends TestCase
{
    /**
     * @var MovieController $controller   The DiceController to be tested.
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

        $this->controller = new MovieController();
        $this->controller->setApp($app);
        $this->controller->initialize();
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
     * Call the controller reset action GET.
     */
    public function testResetActionGet()
    {
        $res = $this->controller->resetActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller reset action GET with reset.
     */
    public function testResetActionGetReset()
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
        $this->assertTrue($this->checkLocation($res, "movie1/reset"));
        $reset = $this->app->session->get("reset");
        $exp = "Reset database";
        $this->assertEquals($exp, $reset);
    }

    /**
     * Call the controller select action.
     */
    public function testSelectAction()
    {
        $res = $this->controller->selectAction();
        $this->assertInstanceOf(ResponseUtility::class, $res);
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
        $this->assertTrue($this->checkLocation($res, "movie1/showAll"));
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
        $this->assertTrue($this->checkLocation($res, "movie1/login"));
        $msg = $this->app->session->get("loginMessage");
        $exp = "Faulty login, try again!";
        $this->assertEquals($exp, $msg);
    }

    /**
     * Call the controller logout action GET.
     */
    public function testLogoutActionGet()
    {
        $this->app->session->set("movieUser", "doe");

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
        $this->assertTrue($this->checkLocation($res, "movie-db"));
    }


    /**
     * Help method to check if the object constains the location, if so
     * it returns true otherwise false.
     *
     * @return boolean as if location is found.
     */
    private function checkLocation($res, string $location)
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
