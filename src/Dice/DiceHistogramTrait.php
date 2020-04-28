<?php
namespace Epkmagr\Dice;

/**
 * A trait implementing histogram for integers.
 */
trait DiceHistogramTrait
{
    /**
     * @var array $serie  The numbers stored in sequence.
     */
    private $serie = [];

    /**
     * Get the serie.
     *
     * @return array with the serie.
     */
    public function getHistogramSerie()
    {
        return $this->serie;
    }

    /**
     * Append $serie at the end of the serie.
     *
     * @param int $serie  Array of numbers to be stored in sequence.
     * @return void.
     */
    public function appendHistogramSerie($serie)
    {
        foreach ($serie as $value) {
            $this->serie[] = $value;
        }
    }

    /**
     * Reset the serie.
     *
     * @return void.
     */
    public function resetHistogramSerie()
    {
        return $this->serie = [];
    }

    /**
     * Get min value for the histogram.
     *
     * @return int with the min value.
     */
    public function getHistogramMin()
    {
        return 1;
    }

    /**
     * Get max value for the histogram.
     *
     * @return int with the max value.
     */
    public function getHistogramMax()
    {
        if (count($this->serie) === 0) {
            return 0;
        } else {
            return max($this->serie);
        }
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
        $minDice = $this->getHistogramMin();
        $maxDice = $this->getHistogramMax();
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
        return $res;
    }
}
