<?php
namespace Epkmagr\Dice;

/**
 * A game called Dice100, which is a dice game. The winner is the first one
 * to reach 100. One or several players use one or several dices. The default
 * number of players is 2 and the default number of dices is 5.
 *
 * To decide the start order, all the players throw one dice and the one
 * with the highest value starts.
 *
 * In each round the player can throw the dices and count the sum of the dices,
 * which will be the points.
 * The player can choose to throw many times but as soon as a 1 turns up
 * all the points from the round is gone and the turn goes to the next player.
 *
 * @author Marie Grahn, mbfs17@student.bth.se
 */
class Dice100
{
    /**
     * @var int GOAL        The goal to reach first to win.
     * @var Player $players Array consisting of the players.
     * @var int $currentPlayer The current player.
     */
    const GOAL = 100;
    private $players;
    private $currentPlayer;

    /**
     * Constructor to initiate the dicehand with a number of dices.
     *
     * @param int $noOfPlayers  The number of players to create, default = 2.
     * @param int $noOfDices  The number of players to create, default = 5.
     */
    public function __construct(int $noOfPlayers = 2, int $noOfDices = 5)
    {
        if ($noOfPlayers < 2 || $noOfPlayers > 6) {
            throw new DiceException("The number of players must be between 2 and 6.");
        }
        $this->players  = [];

        for ($i = 0; $i < $noOfPlayers; $i++) {
            $this->players[$i]  = new Player("", $noOfDices);
        }
        $this->currentPlayer = 0;
    }

    /**
     * Get the number of players.
     *
     * @var int $currentPlayer The current player.
     * @return Player as the current player.
     */
    public function getCurrentPlayer(int $currentPlayer)
    {
        if ($currentPlayer < $this->getTheNumberOfPlayers()) {
            return $this->players[$currentPlayer];
        } else {
            return null;
        }
    }

    /**
     * Get the number of players.
     *
     * @return int as the number of the players.
     */
    public function getTheNumberOfPlayers()
    {
        return count($this->players);
    }


    /**
     * Desides the start order of the players, the one with the highest value
     * starts the game.
     *
     * @return int as the number of the player to start, default is 1, which
     * is the first player in the array after the computer.
     */
    public function startOrder()
    {
        $startValues = array();
        for ($i = 0; $i < $this->getTheNumberOfPlayers(); $i++) {
            $value = $this->players[$i]->rollAndReturnHand()[0];
            array_push($startValues, $value);
        }
        arsort($startValues);

        if (!function_exists('array_key_first')) {
            function array_key_first(array $arr)
            {
                reset($arr);
                return key($arr);
            }
        }
        $currentPlayer = array_key_first($startValues);
        return $currentPlayer;
    }

    /**
     * Play a round of Dice100 for currectPlayer.
     *
     * @var Player $currentPlayer The current player.
     * @return boolean with true if the last roll is valid. If the roll contains
     *  1 then false will be returned.
     */
    public function doRound(Player $currentPlayer)
    {
        $values = $currentPlayer->rollAndReturnHand();
        if (in_array(1, $values, true)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Save the round of Dice100 for currectPlayer.
     *
     * @var Player $currentPlayer The current player.
     * @var int $newScore The new score of the current player.
     * @return void.
     */
    public function endRound(Player $currentPlayer, int $newScore)
    {
        $currentPlayer->setScore($currentPlayer->getScore() + $newScore);
    }

    /**
     * Desides if the player has enough score to win.
     *
     * @var int $tmpScore The temporary score of the player.
     * @return boolean as true if the temporary score is higher than GOAL,
     * otherwise it return false.
     */
    public function win(int $tmpScore)
    {
        if ($tmpScore >= self::GOAL) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Desides if the computer should roll again or end its round.
     * 1 dices: No 1 = (5/6)=83%, 83/17*0.83 = 4 => limit 4*6= 24
     * 2 dices: No 1 = (5/6*5/6)=69%, 69/31*0.69 = 1.5 => limit 1.5*12= 18
     * 3 dices: No 1 => 58%, limit 1.2*18= 22
     * 4 dices: No 1 => 48%, Limit = 0.48*24= 12
     * 5 dices: No 1 => 40%, Limit = 0.4*30= 12
     *
     * @var int $tmpScore The temporary score of the player.
     * @return boolean as false if the temporary score is higher than 60%
     * of the sum of the dices, otherwise it return true.
     */
    public function computerContinues(int $tmpScore)
    {
        $noOfDices = $this->getCurrentPlayer(0)->getNoOfDices();
        switch ($noOfDices) {
            case '1': // limit 24
                $limit = 6 * $noOfDices * 4;
                break;
            case '2': // limit 18
                $limit = 6 * $noOfDices * 1.5;
                break;
            case '3': // limit 22
                $limit = 6 * $noOfDices * 1.2;
                break;
            case '4': // limit 12
                $limit = 6 * $noOfDices * 0.48;
                break;
            case '5': // limit 12
                $limit = 6 * $noOfDices * 0.4;
                break;
            default:
                $limit = 6 * $noOfDices * 0.2;
                break;
        }
        $computerScore = $this->getCurrentPlayer(0)->getScore();
        if ($tmpScore > $limit) {
            if ((self::GOAL-$computerScore) < $limit) {
                return true;
            }
            if ($this->compareWithHighScore($computerScore, $limit)) {
                return true;
            }
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get the highest score of the computer's competitor.
     *
     * @return int as the highest score of the computer's competitor.
     */
    private function getHighScore()
    {
        $highScore = 0;
        for ($i = 1; $i < $this->getTheNumberOfPlayers(); $i++) {
            if ($highScore < $this->getCurrentPlayer($i)->getScore()) {
                $highScore = $this->getCurrentPlayer($i)->getScore();
            }
        }
        return $highScore;
    }

    /**
     * Returns true if the highscore of the computer.
     *
     * @var int $computerScore The score of the computer.
     * @var int $limit The limit for that number of dices.
     * @return boolean as continue or not for the computer.
     */
    private function compareWithHighScore(int $computerScore, int $limit)
    {
        $highScore = $this->getHighScore();
        if ($highScore > $computerScore) { // computer is behind
            if (($highScore - $computerScore) < $limit) {
                return true; // computer tries again
            } else {
                return false; // computer is behind but will play safe
            }
        } else {
            return false; // computer is in lead but will play safe
        }
    }
}
