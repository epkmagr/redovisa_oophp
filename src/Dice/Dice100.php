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
     */
    public function __construct(int $noOfPlayers = 2)
    {
        if ($noOfPlayers < 1) {
            throw new DiceException("The number of players must be 1 or more.");
        }
        $this->players  = [];

        for ($i = 0; $i < $noOfPlayers; $i++) {
            $this->players[$i]  = new Player();
        }
        $currentPlayer = 0;
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
     * @return int as the number of the player to start, default is 0, which
     * is the first player in the array.
     */
    public function startOrder()
    {
        return 0;
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

    // /**
    //  * Play a round of Dice100
    //  *
    //  * @return void.
    //  */
    // public function play()
    // {
    //     $noOfPlayers = getTheNumberOfPlayers();
    //     $tmpScore = 0;
    //     for ($i = 0; $i < $noOfPlayers; $i++) {
    //         // playOnePlayer($this->players[$i]);
    //         $scoreBefore = $this->players[$i]->getScore();
    //         $roundScore = $this->players[$i]->doRound();
    //         if ($roundScore == 0) {
    //             // Nothing happens and the turn goes to the next player.
    //         } else {
    //             $tmpScore += $roundScore;
    //             // Does this player want to do another round?
    //             // Mer logik hos spelaren?
    //         }
    //     }
    // }

    // /**
    //  * Play a round of Dice100
    //  *
    //  * @param Player $player  The actual player to in turn to play a round.
    //  * @param boolean $continue  True if the player wants to play another round.
    //  * @return void.
    //  */
    // public function playOnePlayer(Player $player, boolean $continue = true)
    // {
    //     $scoreBefore = $this->players[$i]->getScore();
    //     $tmpScore = 0;
    //     do {
    //         $roundScore = $this->players[$i]->doRound();
    //         if ($roundScore != 0) {
    //             // $continue = false;
    //             $tmpScore += $roundScore;
    //         } else {
    //
    //         }
    //     } while ($roundScore > 0 && $continue);
    // }
}
