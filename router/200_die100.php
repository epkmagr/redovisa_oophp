<?php
/**
 * Create routes using $app programming style.
 */
//var_dump(array_keys(get_defined_vars()));

/**
 * Start the game "Dice 100" and redirect to post to get information.
 */
$app->router->get("dice100/start", function () use ($app) {
    $game = new Epkmagr\Dice\Dice100();
    $_SESSION["dice100game"] = $game;
    return $app->response->redirect("dice100/init");
});

/**
 * Play the game "Dice 100" - initiate the game.
 */
$app->router->get("dice100/init", function () use ($app) {
    $title = "Initiate the game";

    // Session variables
    $game = $_SESSION["dice100game"];

    $data = [
        "noOfPlayers" => $game->getTheNumberOfPlayers()
    ];

    $app->page->add("dice/init", $data);
    $app->page->add("dice/debug");

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
 * Play the game "Dice 100" - get the information to start the game.
 */
$app->router->post("dice100/init", function () use ($app) {
    $title = "Initiate the game";
    // Incoming variables
    $name1 = $_POST["name1"] ?? null;
    $name2 = $_POST["name2"] ?? null;

    // Session variables
    $game = $_SESSION["dice100game"] ?? null;
    $game->getCurrentPlayer(0)->setName($name1);
    $game->getCurrentPlayer(1)->setName($name2);
    $_SESSION["dice100game"] = $game;
    $_SESSION["startOrder"] = $game->startOrder();

    return $app->response->redirect("dice100/play");
});

/**
 * Play the game "Dice 100" - show game status
 */
$app->router->get("dice100/play", function () use ($app) {
    $title = "Play the game";

    // Session variables
    $game = $_SESSION["dice100game"] ?? null;
    $startOrder = $_SESSION["startOrder"] ?? 0;
    $values = $_SESSION["values"] ?? null;
    $roll = $_SESSION["roll"] ?? null;
    $invalidNumber = $_SESSION["invalidNumber"] ?? false;
    $_SESSION["invalidNumber"] = false;
    $winner = $_SESSION["winner"] ?? null;
    $_SESSION["winner"] = null;

    $data = [
        "startOrder" => $startOrder,
        "game" => $game,
        "values" => $values,
        "roll" => $roll,
        "invalidNumber" => $invalidNumber,
        "winner" => $winner
    ];

    if ($winner != null) {
        $app->page->add("dice/result", $data);
    } else {
        $app->page->add("dice/play", $data);
    }

    $app->page->add("dice/debug");

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
 * Play the game "Dice 100" - play the game
 */
$app->router->post("dice100/play", function () use ($app) {
    $title = "Play the game";

    // Incoming variables
    $roll = $_POST["roll"] ?? null;
    $roundEnd = $_POST["roundEnd"] ?? null;
    $initGame = $_POST["initGame"] ?? null;

    // Session variables
    $game = $_SESSION["dice100game"];
    $startOrder = $_SESSION["startOrder"] ?? 0;
    $thePlayer = $game->getCurrentPlayer($startOrder);
    $tmpScore = $_SESSION["tmpScore"] ?? 0;

    if ($roll) {
        $values = $game->doRound($thePlayer);
        $_SESSION["values"] = $values;
        if (in_array(1, $values, true)) {
            $_SESSION["tmpScore"] = 0;
            $_SESSION["invalidNumber"] = true;
        } else {
            $_SESSION["tmpScore"] += array_sum($values);
            if (($_SESSION["tmpScore"] + $thePlayer->getScore()) > 20) {
                $_SESSION["winner"] = $thePlayer;
            }
        }
        $_SESSION["roll"] = $roll;
    }

    if ($roundEnd) {
        $tmpScore = $_SESSION["tmpScore"];
        $game->endRound($thePlayer, $tmpScore);
        $_SESSION["tmpScore"] = 0;
        if ($startOrder === 1) { // noOfPlayers
            $_SESSION["startOrder"] = 0;
        } else {
            $_SESSION["startOrder"] = $startOrder + 1;
        }
        $_SESSION["roll"] = null;
        $_SESSION["values"] = null;
    }

    if ($initGame) {
        $_SESSION["game"] = null;
        $_SESSION["roll"] = null;
        $_SESSION["values"] = null;
        $_SESSION["tmpScore"] = 0;
        $_SESSION["winner"] = null;
        return $app->response->redirect("dice100/start");
    }

    return $app->response->redirect("dice100/play");
});
