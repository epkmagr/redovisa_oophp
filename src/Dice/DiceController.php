<?php

namespace Epkmagr\Dice;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 * The controller will be injected with $app if implementing the interface
 * AppInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class DiceController implements AppInjectableInterface
{
    use AppInjectableTrait;

    /**
     * @var string $db a sample member variable that gets initialised
     */
    // private $db = "not active";

    // /**
    //  * The initialize method is optional and will always be called before the
    //  * target method/action. This is a convienient method where you could
    //  * setup internal properties that are commonly used by several methods.
    //  *
    //  * @return void
    //  */
    // public function initialize() : void
    // {
    //     // Use to initialise member variables.
    //     $this->db = "active";
    //
    //     // Use $this->app to access the framework services.
    // }

    /**
     * This is the debug method action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function debugAction() : string
    {
        // Deal with the action and return a response.
        return "Debug my Dice 100 game";
    }

    /**
     * This is the index method action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function indexAction() : string
    {
        // Deal with the action and return a response.
        return "INDEX!!";
    }

    /**
     * Adding an optional catchAll() method will catch all actions sent to the
     * router. You can then reply with an actual response or return void to
     * allow for the router to move on to next handler.
     * A catchAll() handles the following, if a specific action method is not
     * created:
     * ANY METHOD mountpoint/**
     *
     * @param array $args as a variadic parameter.
     *
     * @return mixed
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function catchAll(...$args)
    {
        // Deal with the request and send an actual response, or not.
        return;
    }

    /**
     * This is the start method GET action which show default variables,
     * it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function startActionGet() : object
    {
        // Framework services
        $session = $this->app->session;
        $page = $this->app->page;

        $title = "Start page";

        $game = new Dice100();
        $session->set("dice100game", $game);

        $data = [
            "noOfPlayers" => $game->getTheNumberOfPlayers(),
            "noOfDices" => $game->getCurrentPlayer(0)->getNoOfDices()
        ];

        $page->add("dice1/start", $data);
        // $this->app->page->add("dice1/debug");

        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the start method POST action which default variables can be
     * changed, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function startActionPost() : object
    {
        // Framework services
        $response = $this->app->response;
        $request = $this->app->request;

        // Incoming variables
        $changeDefault = $request->getPost("changeDefault"); // TODO rensa bort?
        $yesOrNo = $request->getPost("yesOrNo");

        if ($changeDefault && $yesOrNo === "Nej") {
            return $response->redirect("dice1/init");
        } else {
            return $response->redirect("dice1/changeDefault");
        }
    }

    /**
     * This is the changeDefault method GET action which show the
     * to be changed variables,
     * it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function changeDefaultActionGet() : object
    {
        // Framework services
        $session = $this->app->session;
        $page = $this->app->page;

        $title = "Default values";

        $game = $session->get("dice100game");
        $changeMessage = $session->get("changeMessage");
        $session->set("changeMessage", null);

        $data = [
            "noOfPlayers" => $game->getTheNumberOfPlayers(),
            "noOfDices" => $game->getCurrentPlayer(0)->getNoOfDices(),
            "changeMessage" => $changeMessage
        ];

        $page->add("dice1/changeDefault", $data);
        // $this->app->page->add("dice1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the changeDefault method POST action which show the
     * to be changed variables,
     * it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function changeDefaultActionPost() : object
    {
        // Framework services
        $response = $this->app->response;
        $request = $this->app->request;
        $session = $this->app->session;

        // Incoming variables
        $noOfPlayers = $request->getPost("newNoOfPlayers");
        $noOfDices = $request->getPost("newNoOfDices");
        $change = $request->getPost("change"); // TODO rensa bort?

        if ($change) { // TODO rensa bort?
            if ($noOfPlayers >= 2 && $noOfPlayers <= 6 && $noOfDices >= 1 && $noOfDices <= 10) {
                $session->set("dice100game", null);
                $game = new Dice100($noOfPlayers, $noOfDices);
                $session->set("dice100game", $game);
                return $response->redirect("dice1/init");
            } else {
                $session->set("changeMessage", "Antal spelare ska vara mellan 2 och 6 samt antalet tärningar mellan 1 och 10!");
                return $response->redirect("dice1/changeDefault");
            }
        } else {
            return $response->redirect("dice1/changeDefault");
        }
    }

    /**
     * This is the init method GET action which shows the name of
     * the players, if you want to change them,
     * it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function initActionGet() : object
    {
        // Framework services
        $page = $this->app->page;
        $session = $this->app->session;

        $title = "Initiate the game";

        $game = $session->get("dice100game");

        $data = [
            "noOfPlayers" => $game->getTheNumberOfPlayers()
        ];

        $page->add("dice1/init", $data);
        // $this->app->page->add("dice1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the init method POST action which gets the new name of
     * the players, if you had changed them,
     * it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function initActionPost() : object
    {
        // Framework services
        $response = $this->app->response;
        $request = $this->app->request;
        $session = $this->app->session;

        // Incoming variables
        $name1 = $request->getPost("name1", "Datorn");
        $name2 = $request->getPost("name2", "Spelare 2");
        $name3 = $request->getPost("name3");
        $name4 = $request->getPost("name4");
        $name5 = $request->getPost("name5");
        $name6 = $request->getPost("name6");

        // Session variables
        $game = $session->get("dice100game");
        $game->getCurrentPlayer(0)->setName($name1);
        $game->getCurrentPlayer(1)->setName($name2);
        $name3 != null ? $game->getCurrentPlayer(2)->setName($name3) : null;
        $name4 != null ? $game->getCurrentPlayer(3)->setName($name4) : null;
        $name5 != null ? $game->getCurrentPlayer(4)->setName($name5) : null;
        $name6 != null ? $game->getCurrentPlayer(5)->setName($name6) : null;
        $session->set("dice100game", $game);

        return $response->redirect("dice1/startOrder");
    }

    /**
     * This is the startOrder method GET action which shows the start order,
     * it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function startOrderActionGet() : object
    {
        // Framework services
        $page = $this->app->page;
        $session = $this->app->session;

        $title = "Start order";

        $game = $session->get("dice100game");
        $startOrder = $session->get("startOrder");
        $roll = $session->get("roll");

        $data = [
            "game" => $game,
            "roll" => $roll,
            "startOrder" => $startOrder,
            "noOfPlayers" => $game->getTheNumberOfPlayers(),
            "noOfDices" => $game->getCurrentPlayer(0)->getNoOfDices()
        ];

        $page->add("dice1/startOrder", $data);
        // $this->app->page->add("dice1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the startOrder method POST action which decides the start order,
     * it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function startOrderActionPost() : object
    {
        // Framework services
        $response = $this->app->response;
        $request = $this->app->request;
        $session = $this->app->session;

        // Incoming variables
        $roll = $request->getPost("roll");
        $start = $request->getPost("start");

        // Session variables
        $game = $session->get("dice100game");
        $session->set("roll", $roll);

        if ($roll == "Bestäm startordning") {
            $session->set("startOrder", $game->startOrder());
        }

        if ($start) {
            $session->set("roll", null);
            return $response->redirect("dice1/play");
        } else {
            return $response->redirect("dice1/startOrder");
        }
    }

    /**
     * This is the play method GET action which shows the status of the game,
     * it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function playActionGet() : object
    {
        // Framework services
        $page = $this->app->page;
        $session = $this->app->session;

        $title = "Play the game";

        $game = $session->get("dice100game");
        $startOrder = $session->get("startOrder", 1);
        $graphicValues = $session->get("graphicValues");
        $tmpScore = $session->get("tmpScore", 0);
        $roll = $session->get("roll");
        $computerContinues = $session->get("computerContinues", true);
        $invalidNumber = $session->get("invalidNumber", false);
        $winner = $session->get("winner");

        $session->set("computerContinues", true);
        $session->set("invalidNumber", false);
        $session->set("winner", null);

        $data = [
            "game" => $game,
            "startOrder" => $startOrder,
            "graphicValues" => $graphicValues,
            "tmpScore" => $tmpScore,
            "roll" => $roll,
            "computerContinues" => $computerContinues,
            "invalidNumber" => $invalidNumber,
            "winner" => $winner,
            "noOfPlayers" => $game->getTheNumberOfPlayers(),
            "noOfDices" => $game->getCurrentPlayer(0)->getNoOfDices()
        ];

        if ($winner != null) {
            $page->add("dice1/result", $data);
        } else {
            $page->add("dice1/play", $data);
        }
        // $this->app->page->add("dice1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the play method POST action which plays the game,
     * it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function playActionPost() : object
    {
        // Framework services
        $response = $this->app->response;
        $request = $this->app->request;
        $session = $this->app->session;

        // Incoming variables
        $roll = $request->getPost("roll");
        $roundEnd = $request->getPost("roundEnd");
        $initGame = $request->getPost("initGame");

        // Session variables
        $game = $session->get("dice100game");
        $startOrder = $session->get("startOrder", 1);
        $tmpScore = $session->get("tmpScore", 0);
        $thePlayer = $game->getCurrentPlayer($startOrder);

        if ($roll) {
            $continue = $game->doRound($thePlayer);
            $graphicValues = $thePlayer->getGraphicValues();
            $session->set("graphicValues", $graphicValues);
            if (!$continue) {
                $session->set("tmpScore", 0);
                $session->set("invalidNumber", true);
            } else {
                $tmpScore += $thePlayer->getSumOfHand();
                $session->set("tmpScore", $tmpScore);
                if ($game->win($tmpScore + $thePlayer->getScore())) {
                    $session->set("winner", $thePlayer);
                }
                if ($startOrder === 0) {
                    if ($game->computerContinues($tmpScore)) {
                        $session->set("computerContinues", true);
                    } else {
                        $session->set("computerContinues", false);
                    }
                }
            }
            $session->set("roll", $roll);
        }

        if ($roundEnd) {
            $game->endRound($thePlayer, $tmpScore);
            $session->set("tmpScore", 0);

            if ($startOrder === ($game->getTheNumberOfPlayers() - 1)) {
                $session->set("startOrder", 0);
            } else {
                $session->set("startOrder", $startOrder + 1);
            }
            $session->set("roll", null);
            $session->set("graphicValues", null);
        }

        if ($initGame) {
            $session->set("game", null);
            $session->set("roll", null);
            $session->set("graphicValues", null);
            $session->set("tmpScore", null);
            $session->set("winner", null);

            return $response->redirect("dice1/start");
        }

        return $response->redirect("dice1/play");
    }
}
