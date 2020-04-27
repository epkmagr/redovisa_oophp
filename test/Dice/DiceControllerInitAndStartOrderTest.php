<?php

namespace Epkmagr\Dice;

use Anax\Controller\SampleAppController;
use Anax\DI\DIMagic;
use PHPUnit\Framework\TestCase;
use Anax\Response\ResponseUtility;

/**
 * Test the controller like it would be used from the router,
 * simulating the actual router paths and calling it directly.
 */
class DiceControllerInitAndStartOrderTest extends TestCase
{
    /**
     * @var DiceController $controller   The DiceController to be tested.
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

        // Create and initiate the controller
        $this->controller = new DiceController();
        $this->controller->setApp($app);
        // $this->controller->initialize();

        // Create a game and store in session.
        $game = new Dice100();
        $this->app->session->set("dice100game", $game);
    }

    /**
     * Call the controller init action GET.
     */
    public function testInitActionGet()
    {
        $res = $this->controller->initActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $game = $this->app->session->get("dice100game");
        $this->assertInstanceOf(Dice100::class, $game);
    }

    /**
     * Call the controller init action POST with default names.
     */
    public function testInitActionPost()
    {
        $this->app->request->setGlobals([
            "post" => [
                "name1" => "Datorn",
                "name2" => "Spelare 2",
            ]
        ]);
        $res = $this->controller->initActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "dice1/startOrder"));
        $game = $this->app->session->get("dice100game");
        $this->assertSame("Datorn", $game->getCurrentPlayer(0)->getName());
        $this->assertSame("Spelare 2", $game->getCurrentPlayer(1)->getName());
    }

    /**
     * Call the controller init action POST with changed names.
     */
    public function testInitActionPostChangeName()
    {
        $this->app->request->setGlobals([
            "post" => [
                "name1" => "Datorn",
                "name2" => "Marie",
            ]
        ]);
        $res = $this->controller->initActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "dice1/startOrder"));
        $game = $this->app->session->get("dice100game");
        $this->assertSame("Marie", $game->getCurrentPlayer(1)->getName());
    }

    /**
     * Call the controller startOrder action GET.
     */
    public function testStartOrderActionGet()
    {
        $res = $this->controller->startOrderActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $startOrder = $this->app->session->get("startOrder");
        $this->assertNull($startOrder);
    }

    /**
     * Call the controller init action POST with changed names.
     */
    public function testStartOrderActionPost()
    {
        $res = $this->controller->startOrderActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "dice1/startOrder"));
        $startOrder = $this->app->session->get("startOrder");
        $this->assertNull($startOrder);
    }

    /**
     * Call the controller init action POST make roll to decide start order.
     */
    public function testStartOrderActionPostRoll()
    {
        $this->app->request->setGlobals([
            "post" => [
                "roll" => true,
            ]
        ]);
        $res = $this->controller->startOrderActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "dice1/startOrder"));
        $startOrder = $this->app->session->get("startOrder");
        $this->assertGreaterThanOrEqual(0, $startOrder);
        $this->assertLessThanOrEqual(5, $startOrder);
    }

    /**
     * Call the controller init action POST start order decides, start to play.
     */
    public function testStartOrderActionPostPlay()
    {
        $this->app->request->setGlobals([
            "post" => [
                "start" => true,
            ]
        ]);
        $res = $this->controller->startOrderActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "dice1/play"));
        $startOrder = $this->app->session->get("startOrder");
        $this->assertGreaterThanOrEqual(0, $startOrder);
        $this->assertLessThanOrEqual(5, $startOrder);
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
