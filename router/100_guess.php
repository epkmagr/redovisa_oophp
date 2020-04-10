<?php
/**
 * Create routes using $app programming style.
 */
//var_dump(array_keys(get_defined_vars()));

/**
 * Start the game "Guess my number" and redirect to the game.
 */
$app->router->get("guess/init", function () use ($app) {
    // init the session for starting the game
    $game = new Epkmagr\Guess\Guess();
    $_SESSION["game"] = $game;
    return $app->response->redirect("game/play");
});

/**
 * Play the game "Guess my number" - show game status
 */
$app->router->get("game/play", function () use ($app) {
    $title = "Play the game";

    // Session variables
    $game = $_SESSION["game"];
    $res = $_SESSION["res"] ?? null;
    $guess = $_SESSION["guess"] ?? null;
    $cheat = $_SESSION["cheat"] ?? null;

    // Clean session variables
    $_SESSION["res"] = null;
    $_SESSION["guess"] = null;
    $_SESSION["cheat"] = null;

    $data = [
        "guess" => $guess ?? null,
        "number" => $game->number(),
        "tries" => $game->tries(),
        "res" => $res,
        "doGuess" => $doGuess ?? null,
        "cheat" => $cheat ?? null
    ];

    if ($res == "correct") {
        $app->page->add("guess/result", $data);
    } elseif ($game->tries() == 0) {
        $app->page->add("guess/gameover", $data);
    } else {
        $app->page->add("guess/play", $data);
    }
    // $app->page->add("guess/debug");

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
 * Play the game "Guess my number" - play the game
 */
$app->router->post("game/play", function () use ($app) {
    // Incoming variables
    $guess = $_POST["guess"] ?? null;
    $doGuess = $_POST["doGuess"] ?? null;
    $initGame = $_POST["initGame"] ?? null;
    $cheat = $_POST["cheat"] ?? null;
    $res = null;

    // Session variables
    $game = $_SESSION["game"];

    if ($doGuess) {
        try {
            $res = $game->makeGuess($guess);
            $_SESSION["game"] = $game;
            $_SESSION["res"] = $res;
            $_SESSION["guess"] = $guess;
        } catch (\Epkmagr\Guess\GuessException $guessExc) {
            // echo "Got exception: " . get_class($guessExc) . "<hr>";
            $_SESSION["game"] = $game;
            $_SESSION["res"] = $guessExc->getMessage();
        }
    } elseif ($cheat) {
        $_SESSION["cheat"] = $cheat;
    } elseif ($initGame) {
        return $app->response->redirect("guess/init");
    }

    return $app->response->redirect("game/play");
});
