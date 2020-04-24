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
class DiceControllerTest extends TestCase
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
        $this->controller = new DiceController();
        $this->controller->setApp($app);
        // $this->controller->initialize();
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
     * Call the controller play action GET with a winner.
     */
    public function testPlayActionGetWinner()
    {
        $this->app->session->set("winner", new Player("Datorn"));
        $res = $this->controller->playActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller play action POST and init a new game.
     */
    public function testPlayActionPostinitGame()
    {
        $this->app->request->setGlobals([
            "post" => [
                "initGame" => true,
            ]
        ]);
        $res = $this->controller->playActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "dice1/start"));
        $tmpScore = $this->app->session->get("tmpScore");
        $this->assertEquals(0, $tmpScore);
    }

    /**
     * Call the controller play action POST make roll and do not continue.
     */
    public function testPlayOrderActionPostRoll()
    {
        $this->app->request->setGlobals([
            "post" => [
                "roll" => true,
            ]
        ]);
        $res = $this->controller->playActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        // $tmpScore = $this->app->session->get("tmpScore");
        // $this->assertEquals(0, $tmpScore);
        // $invalidNumber = $this->app->session->get("invalidNumber");
        // $this->assertEquals(true, $invalidNumber);
    }

    /**
     * Call the controller play action POST make the round end.
     */
    public function testPlayOrderActionPostEndRound()
    {
        $this->app->request->setGlobals([
            "post" => [
                "roll" => null,
                "roundEnd" => "Spara & avsluta rundan",
            ]
        ]);
        $this->app->session->set("startOrder", 0);
        $this->app->session->set("tmpScore", 20);
        $game = new Dice100();
        $game->getCurrentPlayer(0)->setScore(10);
        $this->app->session->set("dice100game", $game);
        $res = $this->controller->playActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $newStartOrder = $this->app->session->get("startOrder");
        $this->assertEquals(1, $newStartOrder);
        $newScore = $game->getCurrentPlayer(0)->getScore();
        $this->assertEquals(30, $newScore);
    }

    /**
     * Call the controller play action POST make the round end and
     * give turn to the computer, who is player 0.
     */
    public function testPlayOrderActionPostEndRoundComputerTurn()
    {
        $this->app->request->setGlobals([
            "post" => [
                "roll" => null,
                "roundEnd" => "Spara & avsluta rundan",
            ]
        ]);
        $this->app->session->set("startOrder", 5);
        $this->app->session->set("tmpScore", 20);
        $game = new Dice100(6, 2);
        $game->getCurrentPlayer(5)->setScore(10);
        $this->app->session->set("dice100game", $game);
        $res = $this->controller->playActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $newStartOrder = $this->app->session->get("startOrder");
        $this->assertEquals(0, $newStartOrder);
        $newScore = $game->getCurrentPlayer(5)->getScore();
        $this->assertEquals(30, $newScore);
    }

    // /**
    //  * Call the controller play action POST make the round end.
    //  */
    // public function testPlayOrderActionPostEndRoundGoToFirst()
    // {
    //     $this->app->request->setGlobals([
    //         "post" => [
    //             "endRound" => true,
    //         ]
    //     ]);
    //     $this->app->session->set("startOrder", 1);
    //     $res = $this->controller->playActionPost();
    //     $this->assertInstanceOf(ResponseUtility::class, $res);
    //     $newStartOrder = $this->app->session->get("startOrder");
    //     $this->assertEquals(0, $newStartOrder);
    // }

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
