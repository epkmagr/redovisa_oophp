<?php

namespace Epkmagr\Guess;

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
class GuessController implements AppInjectableInterface
{
    use AppInjectableTrait;



    /**
     * @var string $db a sample member variable that gets initialised
     */
    // private $db = "not active";



    /**
     * The initialize method is optional and will always be called before the
     * target method/action. This is a convienient method where you could
     * setup internal properties that are commonly used by several methods.
     *
     * @return void
     */
    public function initialize() : void
    {
        // Use to initialise member variables.
        $this->db = "active";

        // Use $this->app to access the framework services.
    }

    /**
     * This is the init method action that initiate the Guess game, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function initAction() : object
    {
        // Framework services
        $session = $this->app->session;
        $response = $this->app->response;

        // init the session for starting the game
        $game = new Guess();
        $session->set("game", $game);

        return $response->redirect("guess1/play");
    }

    /**
     * This is the play method action which shows the status of the guess game,
     * it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function playActionGet() : object
    {
        $title = "Play the game";
        // Framework services
        $session = $this->app->session;
        $page = $this->app->page;

        // Session variables
        $game = $session->get("game", null);
        $res = $session->get("res", null);
        $guess = $session->get("guess", null);
        $cheat = $session->get("cheat", null);

        // Clean session variables
        $session->set("res", null);
        $session->set("guess", null);
        $session->set("cheat", null);

        $data = [
            "guess" => $guess ?? null,
            "number" => $game->number(),
            "tries" => $game->tries(),
            "res" => $res,
            "cheat" => $cheat ?? null
        ];

        if ($res == "correct") {
            $page->add("guess1/result", $data);
        } elseif ($game->tries() == 0) {
            $page->add("guess1/gameover", $data);
        } else {
            $page->add("guess1/play", $data);
        }
        $page->add("guess1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the play method action which plays the guess game, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function playActionPost() : object
    {
        // Framework services
        $session = $this->app->session;
        $response = $this->app->response;
        $request = $this->app->request;

        // Incoming variables
        $guess = $request->getPost("guess");
        $doGuess = $request->getPost("doGuess");
        $initGame = $request->getPost("initGame");
        $cheat = $request->getPost("cheat");
        $res = null;

        // Session variables
        $game = $session->get("game"); // null is default from the framework

        if ($doGuess) {
            try {
                $res = $game->makeGuess($guess);
                $session->set("game", $game);
                $session->set("res", $res);
                $session->set("guess", $guess);
            } catch (GuessException $guessExc) {
                // echo "Got exception: " . get_class($guessExc) . "<hr>";
                $session->set("game", $game);
                $session->set("res", $guessExc->getMessage());
            }
        } elseif ($cheat) {
            $session->set("cheat", $cheat);
        } elseif ($initGame) {
            return $response->redirect("guess1/init");
        }

        return $response->redirect("guess1/play");
    }

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
        return "Debug my game";
    }

    /**
     * This is the debug method action, it handles:
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
}
