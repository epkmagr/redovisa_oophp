<?php
namespace Epkmagr\Dice;

/**
 * Class DiceHistogram, a Dice which has the ability to show a histogram.
  */
class Histogram
{
    /**
     * @var array $serie  The numbers stored in sequence.
     * @var int   $min    The lowest possible number.
     * @var int   $max    The highest possible number.
     */
    private $serie = [];
    private $min;
    private $max;

    /**
     * Constructor to initiate the dice histogram.
     *
     */
    public function __construct()
    {
        // resetHistogramSerie();
        $this->serie = [];
    }

    /**
     * Get the serie.
     *
     * @return array with the serie.
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * Reset the serie.
     *
     * @return void .
     */
    public function resetSerie()
    {
        $this->serie = [];
    }

    /**
     * Injects the dice values from the histogram interface.
     *
     * @param HistogramInterface    $aHistogram  The histogram.
     * @return void
     */
    public function injectData(DiceHistogramInterface $aHistogram)
    {
        foreach ($aHistogram->getHistogramSerie() as $value) {
            $this->serie[] = $value;
        }
        $this->min   = $aHistogram->getHistogramMin();
        $this->max   = max($this->serie);
    }

    /**
     * Print out the histogram, default is to print out only the numbers
     * in the serie, but when $min and $max is set then print also empty
     * values in the serie, within the range $min, $max.
     *
     * @return string representing the histogram.
     */
    public function printHistogram()
    {
        $res = "";
        if (count($this->serie) != 0) {
            $minDice = $this->min;
            $maxDice = $this->max;
            $noOfRolls = count($this->serie);
            for ($i=$minDice; $i<=$maxDice; $i++) {
                $res .= $i . ": ";
                for ($j=0; $j<$noOfRolls; $j++) {
                    if ($this->serie[$j] === $i) {
                        $res .= "*";
                    }
                }
                $res .= "<br/>";
            }
        }
        return $res;
    }
}
