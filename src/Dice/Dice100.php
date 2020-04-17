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
    private const GOAL = 100;
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
        if ($noOfPlayers < 2) {
            throw new DiceException("The number of players must be 2 or more.");
        }
        $this->players  = [];

        for ($i = 0; $i < $noOfPlayers; $i++) {
            $this->players[$i]  = new Player("", $noOfDices);
        }
        $this->currentPlayer = 1;
    }

    /**
     * Get the number of players.
     *
     * @var int $currentPlayer The current player.
     * @return Player as the current player.
     */
    public function getCurrentPlayer(int $currentPlayer)
    {
        return $this->players[$currentPlayer];
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
        return $this->currentPlayer;
    }

    /**
     * Play a round of Dice100 for currectPlayer.
     *
     * @var Player $currentPlayer The current player.
     * @return array with values of the last roll.
     */
    public function doRound(Player $currentPlayer)
    {
        return $currentPlayer->rollAndReturnHand();
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
}
