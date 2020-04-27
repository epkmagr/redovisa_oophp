<?php

namespace Epkmagr\Dice;

use Anax\Controller\SampleAppController;
use Anax\DI\DIMagic;
use PHPUnit\Framework\TestCase;
use Anax\Response\ResponseUtility;

// use Anax\Page\Page;
// use Anax\View\View;
// use Anax\View\ViewCollection;

/**
 * Test the controller like it would be used from the router,
 * simulating the actual router paths and calling it directly.
 */
class DiceControllerStartAndChangeDefaultTest extends TestCase
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
        $this->assertContains("Debug my Dice 100 game", $res);
    }

    /**
     * Call the controller catchAll ANY.
     */
    public function testCatchAllGet()
    {
        $res = $this->controller->catchAll();
        $this->assertNull($res);
    }

    /**
     * Call the controller start action GET.
     */
    public function testStartActionGet()
    {
        $res = $this->controller->startActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $game = $this->app->session->get("dice100game");
        $this->assertInstanceOf(Dice100::class, $game);
    }

    /**
     * Call the controller start action POST not change default values.
     */
    public function testStartActionPostNotChangeDefault()
    {
        $this->app->request->setGlobals([
            "post" => [
                "changeDefault" => true,
                "yesOrNo" => "Nej",
            ]
        ]);
        $res = $this->controller->startActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "dice1/init"));
    }

    /**
     * Call the controller start action POST DO change default values.
     */
    public function testStartActionPostChangeDefault()
    {
        $this->app->request->setGlobals([
            "post" => [
                "changeDefault" => true,
                "yes_no" => "Ja",
            ]
        ]);
        $res = $this->controller->startActionPost();
        // var_dump($res);
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "dice1/changeDefault"));
    }

    /**
     * Call the controller changeDefault action GET.
     */
    public function testChangeDefaultActionGet()
    {
        $res = $this->controller->changeDefaultActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $game = $this->app->session->get("dice100game");
        $this->assertInstanceOf(Dice100::class, $game);
    }

    /**
     * Call the controller start action POST not change default values.
     */
    public function testChangeDefaultPostNoChange()
    {
        $this->app->request->setGlobals([
            "post" => [
                "change" => false,
            ]
        ]);
        $res = $this->controller->changeDefaultActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "dice1/changeDefault"));
    }

    /**
     * Call the controller start action POST change to valid values.
     */
    public function testChangeDefaultPostChangeValid()
    {
        $this->app->request->setGlobals([
            "post" => [
                "change" => true,
                "newNoOfPlayers" => 4,
                "newNoOfDices" => 2,
            ]
        ]);
        $res = $this->controller->changeDefaultActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "dice1/init"));
        $game = $this->app->session->get("dice100game");
        $this->assertSame(4, $game->getTheNumberOfPlayers());
    }

    /**
     * Call the controller start action POST change to unvalid values.
     */
    public function testChangeDefaultPostChangeUnvalid()
    {
        $this->app->request->setGlobals([
            "post" => [
                "change" => true,
                "newNoOfPlayers" => 10,
                "newNoOfDices" => 12,
            ]
        ]);
        $res = $this->controller->changeDefaultActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "dice1/changeDefault"));
        $str = $this->app->session->get("changeMessage");
        $this->assertContains("Antal spelare ska vara mellan 2", $str);
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
