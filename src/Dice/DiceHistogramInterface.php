<?php

namespace Epkmagr\Dice;

/**
 * A interface for a classes supporting histogram reports.
 */
interface DiceHistogramInterface
{
    /**
     * Get the serie.
     *
     * @return array with the serie.
     */
    public function getHistogramSerie();

    /**
     * Append $serie at the end of the serie.
     *
     * @param int $serie  Array of numbers to be stored in sequence.
     * @return void.
     */
    public function appendHistogramSerie($serie);

    /**
     * Get min value for the histogram.
     *
     * @return int with the min value.
     */
    public function getHistogramMin();

    /**
     * Get max value for the histogram.
     *
     * @return int with the max value.
     */
    public function getHistogramMax();
}
