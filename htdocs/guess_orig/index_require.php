<?php
/**
 * Main program for teh game Guess my number.
 */
require __DIR__ . "/autoload.php";
require __DIR__ . "/config.php";
require __DIR__ . "/view/header.php";

if (!isset($_SESSION["game"])) {
    $_SESSION["game"] = new Guess();
}

$game = $_SESSION["game"];
$number = $game->number();
$tries = $game->tries();

$number = $_POST["number"] ?? null;
$tries = $_POST["tries"] ?? null;
$aGuess = $_POST["aGuess"] ?? null;
$doGuess = $_POST["doGuess"] ?? null;
$initGame = $_POST["initGame"] ?? null;
$cheat = $_POST["cheat"] ?? null;
$game = null;
$res = null;
// var_dump($_POST);

if ($initGame || $number === null) {
    $game = new Guess();
    $number = $game->number();
    $tries = $game->tries();
    $_SESSION["game"] = $game;
} elseif ($doGuess) {
    $game = $_SESSION["game"];
    try {
        $res = $game->makeGuess($aGuess);
        $tries = $game->tries();
        $_SESSION["game"] = $game;
        if ($res == "correct") {
            require __DIR__ .  "/view/result.php";
        } else {
            if ($tries == 0) {
                require __DIR__ .  "/view/gameover.php";
            }
        }
    } catch (GuessException $guessExc) {
        // echo "Got exception: " . get_class($guessExc) . "<hr>";
        $tries = $game->tries();
        $res = $guessExc->getMessage();
        if ($tries == 0) {
            require __DIR__ .  "/view/gameover.php";
        }
    }
}

//Render the page
if ($res != "correct" && $tries >= 0) {
    require __DIR__ . "/view/guessMyNumber.php";
}
require __DIR__ . "/view/footer.php";
