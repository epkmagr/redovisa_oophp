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
     * Get the sum of dices in a hand.
     *
     * @return int as the sum of the dices in the hand of the player.
     */
    public function getSumOfHand()
    {
        return $this->hand->sum();
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
}
