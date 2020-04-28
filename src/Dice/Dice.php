<?php
namespace Epkmagr\Dice;

/**
 * Class Dice, a class that represents a dice with
 * using a namespace
 *
 * @author Marie Grahn, mbfs17@student.bth.se
 */
class Dice
{
    /**
     * @var int  $sides     The number of sides on the dice.
     * @var int  $lastRoll  The last rolled number of the dice.
     */
    private $sides;
    private $lastRoll;

    /**
     * Constructor to create an object of Dice.
     *
     * @param 6|int    $sides  The number of sides of the dice. Default = 6.
     */
    public function __construct(int $sides = 6)
    {
        if ($sides < 1) {
            throw new DiceException("The dice must have 1 or more sides.");
        } else {
            $this->sides = $sides;
        }
        $this->lastRoll = 0;
    }

    /**
     * Destroy an object of Dice.
     */
    public function __destruct()
    {
        // echo __METHOD__;
    }

    /**
     * Roll the Dice and return the value.
     *
     * @return void
     */
    public function roll()
    {
        $this->lastRoll = mt_rand(1, $this->sides);
    }

    /**
     * Return the value of the lastRoll.
     *
     * @return int as the value from the last roll.
     */
    public function getLastRoll()
    {
        return $this->lastRoll;
    }
}
