<?php
namespace Epkmagr\Dice;

/**
 * A dicehand, consisting of dices.
 *
 * @author Marie Grahn, mbfs17@student.bth.se
 */
class Player
{
    /**
     * @var string  $name  The name of the player.
     * @var DiceHand $hand The hand with dices.
     * @var int  $score    The score of the player.
     */
    private $name;
    private $hand;
    private $score;

    /**
     * Constructor to initiate the dicehand with a number of dices.
     *
     * @param string $name The name of the player, default = "".
     * @param int $noOfDices  The number of players to create, default = 2.
*/
    public function __construct(string $name = "", int $noOfDices = 2)
    {
        $this->name = $name;
        $this->hand = new DiceHand($noOfDices);
        $this->score = 0;
    }

    /**
     * Get the name of the player.
     *
     * @return string as the name of the player.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name of the player.
     *
     * @param string $name The name of the player.
     * @return void.
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the score of the player.
     *
     * @return int as the score of the player.
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set the score of the player.
     *
     * @param int $score The score of the player.
     * @return void.
     */
    public function setScore(int $score)
    {
        $this->score = $score;
    }

    /**
     * Get the number of dices in a hand.
     *
     * @return string as the name of the player.
     */
    public function getNoOfDices()
    {
        return $this->hand->getNoOfDices();
    }

    /**
     * Do a round and return the sum of points of the round. If the round is
     * unvalid, i.e. containing 1, then the sum is 0.
     *
     * @return int $sum with the sum of points of this round, 0 if unvalid hand.
     */
    public function doRound()
    {
        $this->hand->roll();
        $values = $this->hand->values();
        if (in_array(1, $values)) {
            $sum = 0;
        } else {
            $sum = $this->hand->sum();
        }

        return $sum;
    }

    /**
     * Roll the dices
     *
     * @return array with values of the last roll.
     */
    public function rollAndReturnHand()
    {
        $this->hand->roll();

        return $this->hand->values();
    }

    /**
     * Roll the dices
     *
     * @return array with a graphical representation of last roll of the dices.
     */
    public function getGraphicValues()
    {
        return $this->hand->graphicValues();
    }
}
