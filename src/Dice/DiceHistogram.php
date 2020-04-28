<?php
namespace Epkmagr\Dice;

/**
 * Class DiceHistogram, a Dice which has the ability to show a histogram.
  */
class DiceHistogram extends Dice implements DiceHistogramInterface
{
    use DiceHistogramTrait;

    /**
     * Roll the dice, remember its value in the serie and return
     * its value.
     *
     * @return int  returns the last roll
     */
    public function roll()
    {
        parent::roll();
        $this->serie[] = parent::getLastRoll();
    }
}
