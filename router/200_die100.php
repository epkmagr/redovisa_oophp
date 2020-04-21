<?php
/**
 * Create routes using $app programming style.
 */
//var_dump(array_keys(get_defined_vars()));

/**
 * Start the game "Dice 100" and show default information.
 */
$app->router->get("dice100/start", function () use ($app) {
    $title = "Start page";

    $game = new Epkmagr\Dice\Dice100();
    $_SESSION["dice100game"] = $game;
    $_SESSION["tmpScore"] = 0;
    $_SESSION["values"] = null;
    $_SESSION["graphicValues"] = null;
    $_SESSION["start"] = null;
    $_SESSION["roll"] = null;

    $data = [
        "noOfPlayers" => $game->getTheNumberOfPlayers(),
        "noOfDices" => $game->getCurrentPlayer(0)->getNoOfDices()
    ];

    $app->page->add("dice/start", $data);
    // $app->page->add("dice/debug");

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
 * Start the game "Dice 100" and redirect to post to get information.
 */
$app->router->post("dice100/start", function () use ($app) {
    // Incoming variables
    $changeDefault = $_POST["changeDefault"] ?? null;
    $yes_no = $_POST["yes_no"] ?? null;

    if ($changeDefault && $yes_no === "Nej") {
        return $app->response->redirect("dice100/init");
    } else {
        return $app->response->redirect("dice100/changeDefault");
    }
});

/**
 * Start the game "Dice 100" and change default information.
 */
$app->router->get("dice100/changeDefault", function () use ($app) {
    $title = "Default values";

    // Session variables
    $game = $_SESSION["dice100game"];
    $changeMessage = $_SESSION["changeMessage"] ?? null;
    $_SESSION["changeMessage"] = null;

    $data = [
        "noOfPlayers" => $game->getTheNumberOfPlayers(),
        "noOfDices" => $game->getCurrentPlayer(0)->getNoOfDices(),
        "changeMessage" => $changeMessage
    ];

    $app->page->add("dice/changeDefault", $data);
    // $app->page->add("dice/debug");

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
 * Start the game "Dice 100" and redirect to post to get information.
 */
$app->router->post("dice100/changeDefault", function () use ($app) {
    // Incoming variables
    $noOfPlayers = $_POST["newNoOfPlayers"] ?? null;
    $noOfDices = $_POST["newNoOfDices"] ?? null;
    $change = $_POST["change"] ?? null;

    if ($change) {
        if ($noOfPlayers >= 2 && $noOfPlayers <= 6 && $noOfDices >= 1 && $noOfDices <= 10) {
            $_SESSION["dice100game"] = null;
            $game = new Epkmagr\Dice\Dice100($noOfPlayers, $noOfDices);
            $_SESSION["dice100game"] = $game;
            return $app->response->redirect("dice100/init");
        } else {
            $_SESSION["changeMessage"] = "Antal spelare ska vara mellan 2 och 6 samt antalet tärningar mellan 1 och 10!";
            return $app->response->redirect("dice100/changeDefault");
        }
    } else {
        return $app->response->redirect("dice100/changeDefault");
    }
});

/**
 * Play the game "Dice 100" - initiate the game.
 */
$app->router->get("dice100/init", function () use ($app) {
    $title = "Initiate the game";

    // Session variables
    $game = $_SESSION["dice100game"] ?? null;

    $data = [
        "noOfPlayers" => $noOfPlayers ?? $game->getTheNumberOfPlayers(),
    ];

    $app->page->add("dice/init", $data);
    // $app->page->add("dice/debug");

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
    $name3 = $_POST["name3"] ?? null;
    $name4 = $_POST["name4"] ?? null;
    $name5 = $_POST["name5"] ?? null;
    $name6 = $_POST["name6"] ?? null;

    // Session variables
    $game = $_SESSION["dice100game"] ?? null;
    $game->getCurrentPlayer(0)->setName($name1);
    $game->getCurrentPlayer(1)->setName($name2);
    $game->getCurrentPlayer(2) != null ? $game->getCurrentPlayer(2)->setName($name3) : null;
    $game->getCurrentPlayer(3) != null ? $game->getCurrentPlayer(3)->setName($name4) : null;
    $game->getCurrentPlayer(4) != null ? $game->getCurrentPlayer(4)->setName($name5) : null;
    $game->getCurrentPlayer(5) != null ? $game->getCurrentPlayer(5)->setName($name6) : null;
    $_SESSION["dice100game"] = $game;
    // $_SESSION["startOrder"] = $game->startOrder();

    return $app->response->redirect("dice100/startOrder");
});

/**
 * Play the game "Dice 100" - present the start order
 */
$app->router->get("dice100/startOrder", function () use ($app) {
    $title = "Start order";

    // Session variables
    $game = $_SESSION["dice100game"] ?? null;
    $startOrder = $_SESSION["startOrder"] ?? null;
    $roll = $_SESSION["roll"] ?? null;

    $data = [
        "game" => $game,
        "roll" => $roll,
        "startOrder" => $startOrder,
        "noOfPlayers" => $game->getTheNumberOfPlayers(),
        "noOfDices" => $game->getCurrentPlayer(0)->getNoOfDices()
    ];

    $app->page->add("dice/startOrder", $data);
    // $app->page->add("dice/debug");

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
 * Play the game "Dice 100" - get the start order
 */
$app->router->post("dice100/startOrder", function () use ($app) {
    $title = "Play the game";

    // Incoming variables
    $roll = $_POST["roll"] ?? null;
    $start = $_POST["start"] ?? null;

    // Session variables
    $game = $_SESSION["dice100game"];
    $_SESSION["roll"] = $roll;

    if ($roll == "Bestäm startordning") {
        $_SESSION["startOrder"] = $game->startOrder();
    }

    if ($start) {
        $_SESSION["roll"] = null;
        return $app->response->redirect("dice100/play");
    } else {
        return $app->response->redirect("dice100/startOrder");
    }
});

/**
 * Play the game "Dice 100" - show game status
 */
$app->router->get("dice100/play", function () use ($app) {
    $title = "Play the game";

    // Session variables
    $game = $_SESSION["dice100game"] ?? null;
    $startOrder = $_SESSION["startOrder"] ?? 1;
    $graphicValues = $_SESSION["graphicValues"] ?? null;
    $tmpScore = $_SESSION["tmpScore"] ?? 0;
    $roll = $_SESSION["roll"] ?? null;
    $computerContinues = $_SESSION["computerContinues"] ?? true;
    $invalidNumber = $_SESSION["invalidNumber"] ?? false;
    $_SESSION["invalidNumber"] = false;
    $winner = $_SESSION["winner"] ?? null;
    $_SESSION["winner"] = null;
    $_SESSION["computerContinues"] = true;

    $data = [
        "startOrder" => $startOrder,
        "game" => $game,
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
        $app->page->add("dice/result", $data);
    } else {
        $app->page->add("dice/play", $data);
    }

    // $app->page->add("dice/debug");

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
    // $computerRoll = $_POST["computerRoll"] ?? null;

    // Session variables
    $game = $_SESSION["dice100game"];
    $startOrder = $_SESSION["startOrder"] ?? 1;
    $thePlayer = $game->getCurrentPlayer($startOrder);
    $tmpScore = $_SESSION["tmpScore"] ?? 0;

    if ($roll) {
        $continue = $game->doRound($thePlayer);
        $graphicValues = $thePlayer->getGraphicValues();
        $_SESSION["graphicValues"] = $graphicValues;
        if (!$continue) {
            $_SESSION["tmpScore"] = 0;
            $_SESSION["invalidNumber"] = true;
        } else {
            $_SESSION["tmpScore"] += $thePlayer->getSumOfHand();
            if ($game->win($_SESSION["tmpScore"] + $thePlayer->getScore())) {
                $_SESSION["winner"] = $thePlayer;
            }
            if ($startOrder === 0) {
                if ($game->computerContinues($_SESSION["tmpScore"])) {
                    $_SESSION["computerContinues"] = true;
                } else {
                    $_SESSION["computerContinues"] = false;
                }
            }
        }
        $_SESSION["roll"] = $roll;
    }

    if ($roundEnd) {
        $tmpScore = $_SESSION["tmpScore"];
        $game->endRound($thePlayer, $tmpScore);
        $_SESSION["tmpScore"] = 0;

        if ($startOrder === ($game->getTheNumberOfPlayers() - 1)) {
            $_SESSION["startOrder"] = 0;
        } else {
            $_SESSION["startOrder"] = $startOrder + 1;
        }
        $_SESSION["roll"] = null;
        $_SESSION["graphicValues"] = null;
    }

    if ($initGame) {
        $_SESSION["game"] = null;
        $_SESSION["roll"] = null;
        $_SESSION["graphicValues"] = null;
        $_SESSION["tmpScore"] = 0;
        $_SESSION["winner"] = null;
        $_SESSION["roll"] = null;
        return $app->response->redirect("dice100/start");
    }

    return $app->response->redirect("dice100/play");
});
