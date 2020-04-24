<?php

namespace Epkmagr\Guess;

use Anax\Controller\SampleAppController;
use Anax\DI\DIMagic;
use PHPUnit\Framework\TestCase;
use Anax\Response\ResponseUtility;
use Anax\Page\Page;

/**
 * Test the controller like it would be used from the router,
 * simulating the actual router paths and calling it directly.
 */
class GuessControllerTest extends TestCase
{
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

        // Create and initiate the controller
        $this->controller = new GuessController();
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
     * Call the controller debug action.
     */
    public function testDebugAction()
    {
        $res = $this->controller->debugAction();
        $this->assertIsString($res);
        $this->assertContains("Debug my game", $res);
    }

    /**
     * Call the controller init action.
     */
    public function testInitAction()
    {
        $res = $this->controller->initAction();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller play action GET.
     */
    public function testPlayActionGet()
    {
        $res = $this->controller->playActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller play action GET with correct result.
     */
    public function testPlayActionGetResCorrect()
    {
        $game = new Guess();
        $this->app->session->set("res", "correct");
        $this->app->session->set("game", $game);
        $res = $this->controller->playActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller play action GET with gameover.
     */
    public function testPlayActionGetGameover()
    {
        $game = new Guess(-1, 0);
        $this->app->session->set("res", "");
        $this->app->session->set("game", $game);
        $res = $this->controller->playActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }


    /**
     * Call the controller play action POST.
     */
    public function testPlayActionPost()
    {
        $this->app->request->setGlobals([
            "post" => [
                "guess" => 1,
            ]
        ]);
        $res = $this->controller->playActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        // Check where to the redirect is (do you really need to assert this?)
        $headers = $res->getHeaders();
        $hasLocationHeader = false;
        foreach ($headers as $header) {
            if (substr($header, 0, 10) === "Location: ") {
                $hasLocationHeader = true;
                // The last part (a) is the url whereto redirect
                $this->assertContains('Location: /guess1/play', $header);
            }
        }
        $this->assertTrue($hasLocationHeader);
    }

    /**
     * Call the controller play action POST.
     */
    public function testPlayActionPostCheat()
    {
        $this->app->request->setGlobals([
            "post" => [
                "cheat" => true,
            ]
        ]);
        $res = $this->controller->playActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        $exp = $this->app->session->get("cheat");
        $this->assertTrue($exp);
    }
}
