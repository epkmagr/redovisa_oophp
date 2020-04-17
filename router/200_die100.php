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
    $_SESSION["noOfPlayers"] = null;
    $_SESSION["noOfDices"] = null;

    $data = [
        "noOfPlayers" => $game->getTheNumberOfPlayers(),
        "noOfDices" => $game->getCurrentPlayer(0)->getNoOfDices()
    ];

    $app->page->add("dice/start", $data);
    $app->page->add("dice/debug");

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

    // Session variables
    $_SESSION["changeDefault"] = $changeDefault;
    $_SESSION["yes_no"] = $yes_no;

    if ($yes_no === "Nej") {
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
    $change = $_SESSION["change"] ?? null;
    $noOfPlayers = $_SESSION["noOfPlayers"] ?? null;
    $noOfDices = $_SESSION["noOfDices"] ?? null;
    $_SESSION["change"] = null;
    $_SESSION["yes_no"] = null;
    $_SESSION["noOfPlayers"] = null;
    $_SESSION["noOfDices"] = null;

    $data = [
        "noOfPlayers" => $noOfPlayers ?? $game->getTheNumberOfPlayers(),
        "noOfDices" => $noOfDices ?? $game->getCurrentPlayer(0)->getNoOfDices()
    ];

    // if ($change) {
    //     if ($noOfPlayers != null && $noOfDices != null) {
    //         $_SESSION["dice100game"] = null;
    //         $game = new Epkmagr\Dice\Dice100($noOfPlayers, $noOfDices);
    //         $_SESSION["dice100game"] = $game;
    //         $app->page->add("dice/init");
    //     }
    // } else {
    //     $app->page->add("dice/changeDefault", $data);
    // }
    $app->page->add("dice/changeDefault", $data);
    $app->page->add("dice/debug");

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

    // Session variables
    $_SESSION["noOfPlayers"] = $noOfPlayers;
    $_SESSION["noOfDices"] = $noOfDices;
    $_SESSION["change"] = $change;

    if ($change) {
        $_SESSION["dice100game"] = null;
        $game = new Epkmagr\Dice\Dice100($noOfPlayers, $noOfDices);
        $_SESSION["dice100game"] = $game;
        return $app->response->redirect("dice100/init");
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

    $app->page->add("dice/init");
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
    $startOrder = $_SESSION["startOrder"] ?? 1;
    $values = $_SESSION["values"] ?? null;
    $graphicValues = $_SESSION["graphicValues"] ?? null;
    $tmpScore = $_SESSION["tmpScore"] ?? 0;
    $roll = $_SESSION["roll"] ?? null;
    $invalidNumber = $_SESSION["invalidNumber"] ?? false;
    $_SESSION["invalidNumber"] = false;
    $winner = $_SESSION["winner"] ?? null;
    $_SESSION["winner"] = null;
    // $noOfDices = $game->getCurrentPlayer($startOrder)->getNoOfDices();

    $data = [
        "startOrder" => $startOrder,
        "game" => $game,
        "values" => $values,
        "graphicValues" => $graphicValues,
        "tmpScore" => $tmpScore,
        "roll" => $roll,
        "invalidNumber" => $invalidNumber,
        "winner" => $winner,
        "noOfDices" => $game->getCurrentPlayer(0)->getNoOfDices()
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
    $startOrder = $_SESSION["startOrder"] ?? 1;
    $thePlayer = $game->getCurrentPlayer($startOrder);
    $tmpScore = $_SESSION["tmpScore"] ?? 0;

    if ($roll) {
        $values = $game->doRound($thePlayer);
        $graphicValues = $thePlayer->getGraphicValues();
        $_SESSION["values"] = $values;
        $_SESSION["graphicValues"] = $graphicValues;
        if (in_array(1, $values, true)) {
            $_SESSION["tmpScore"] = 0;
            $_SESSION["invalidNumber"] = true;
        } else {
            $_SESSION["tmpScore"] += array_sum($values);
            if ($game->win($_SESSION["tmpScore"] + $thePlayer->getScore())) {
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
        $_SESSION["graphicValues"] = null;
    }

    if ($initGame) {
        $_SESSION["game"] = null;
        $_SESSION["roll"] = null;
        $_SESSION["values"] = null;
        $_SESSION["graphicValues"] = null;
        $_SESSION["tmpScore"] = 0;
        $_SESSION["winner"] = null;
        return $app->response->redirect("dice100/start");
    }

    return $app->response->redirect("dice100/play");
});
