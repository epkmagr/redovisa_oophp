<?php
namespace Epkmagr\Dice;

/**
 * A dicehand, consisting of dices.
 *
 * @author Marie Grahn, mbfs17@student.bth.se
 */
class DiceHand
{
    /**
     * @var DiceGraphic $dices   Array consisting of dices.
     * @var int  $values  Array consisting of last roll of the dices.
     * @var string  $graphicValues  Array consisting of a graphical
     *              representation of last roll of the dices.
     */
    private $dices;
    private $values;
    private $graphicValues;

    /**
     * Constructor to initiate the dicehand with a number of dices.
     *
     * @param int $dices The number of dices to create, default = 5.
     */
    public function __construct(int $dices = 5)
    {
        if ($dices < 1) {
            throw new DiceException("The number of dices must be 1 or more.");
        }

        $this->dices  = [];
        $this->values = [];
        $this->graphicValues = [];

        for ($i = 0; $i < $dices; $i++) {
            $this->dices[$i]  = new DiceGraphic();
            $this->values[$i] = null;
            $this->graphicValues = null;
        }
    }

    /**
     * Get the number of dices that has been created, default is 5.
     *
     * @return int as the number of dices that has been created, default is 5.
     */
    public function getNoOfDices()
    {
        return count($this->dices);
    }

    /**
     * Roll all the dices and save their values.
     *
     * @return void.
     */
    public function roll()
    {
        $dices = $this->getNoOfDices();
        for ($i = 0; $i < $dices; $i++) {
            $this->dices[$i]->roll();
            $this->graphicValues[$i] = $this->dices[$i]->graphic();
            $this->values[$i] = $this->dices[$i]->getLastRoll();
        }
    }

    /**
     * Get the values of dices from last roll.
     *
     * @return array with values of the last roll.
     */
    public function values()
    {
        return $this->values;
    }

    /**
     * Get the graphical representation of dices from last roll.
     *
     * @return array with values of the last roll.
     */
    public function graphicValues()
    {
        return $this->graphicValues;
    }

    /**
     * Get the sum of all dices.
     *
     * @return int as the sum of all dices.
     */
    public function sum()
    {
        $sum = 0;
        $dices = $this->getNoOfDices();

        for ($i = 0; $i < $dices; $i++) {
            $sum += $this->values[$i];
        }

        return $sum;
    }

    /**
     * Get the average of all dices.
     *
     * @return float as the average of all dices.
     */
    public function average()
    {
        $dices = $this->getNoOfDices();

        return round($this->sum() / $dices, 2);
    }
}
