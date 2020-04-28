<?php
namespace Epkmagr\Dice;

/**
 * Class DiceGraphic, a class that extends the dice class with graphic
 * representation.
 *
 * @author Marie Grahn, mbfs17@student.bth.se
 */
class DiceGraphic extends Dice
{
    /**
     * @var integer SIDES The number of sides of the Dice.
     */
    const SIDES = 6;

    /**
     * Constructor to initiate the dice with six number of sides.
     */
    public function __construct()
    {
        parent::__construct(self::SIDES);
    }

    /**
     * Get a graphic value of the last rolled dice.
     *
     * @return string as graphical representation of last rolled dice.
     */
    public function graphic()
    {
        return "dice-" . $this->getLastRoll();
    }
}
