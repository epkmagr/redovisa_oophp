<?php

namespace Epkmagr\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DiceHand.
 */
class HistogramTest extends TestCase
{
    /**
     * Construct object of Histogram and verify that the object is of expected instance.
     */
    public function testCreateObject()
    {
        $histogram = new Histogram();
        $this->assertInstanceOf("\Epkmagr\Dice\Histogram", $histogram);

        $res = count($histogram->getSerie());
        $exp = 0; // serie should be 0
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object of DiceHistogram and verify that the object is of expected instance.
     */
    public function testCreateDiceHistogramObject()
    {
        $diceHistogram = new DiceHistogram();
        $this->assertInstanceOf("\Epkmagr\Dice\DiceHistogram", $diceHistogram);

        $res = $diceHistogram->getHistogramMax();
        $exp = 0; // serie should be 0
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and verify that to inject a DiceHistogram object.
     * And also test to reset the serie.
     */
    public function testInjectObject()
    {
        $histogram = new Histogram();
        $this->assertInstanceOf("\Epkmagr\Dice\Histogram", $histogram);
        $diceHistogram = new DiceHistogram();
        $this->assertInstanceOf("\Epkmagr\Dice\DiceHistogram", $diceHistogram);

        $diceHand = new DiceHand();
        $diceHand->roll();
        $exp = $diceHand->values();
        $diceHistogram->appendHistogramSerie($exp);
        $histogram->injectData($diceHistogram);
        $res = $histogram->getSerie();
        $this->assertEquals($exp, $res);

        $diceHistogram->resetHistogramSerie();
        $histogram->resetSerie();
        $res = count($histogram->getSerie());
        $exp = 0; // serie should be 0 after reset
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and verify that to it works to inject a
     * DiceHistogram object 3 times.
     */
    public function testInjectObject3Times()
    {
        $histogram = new Histogram();
        $this->assertInstanceOf("\Epkmagr\Dice\Histogram", $histogram);
        $diceHistogram = new DiceHistogram();
        $this->assertInstanceOf("\Epkmagr\Dice\DiceHistogram", $diceHistogram);

        $diceHand = new DiceHand();
        $diceHand->roll();
        $exp = $diceHand->values();
        $diceHistogram->appendHistogramSerie($exp);
        $exp2 = $diceHand->values();
        $diceHistogram->appendHistogramSerie($exp2);
        $exp3 = $diceHand->values();
        $diceHistogram->appendHistogramSerie($exp3);
        $histogram->injectData($diceHistogram);

        foreach ($exp2 as $value) {
            $exp[] = $value;
        }
        foreach ($exp3 as $value) {
            $exp[] = $value;
        }
        $res = $histogram->getSerie();
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and verify that to inject a DiceHistogram object.
     */
    public function testPrintHistogram()
    {
        $histogram = new Histogram();
        $this->assertInstanceOf("\Epkmagr\Dice\Histogram", $histogram);
        $diceHistogram = new DiceHistogram();
        $this->assertInstanceOf("\Epkmagr\Dice\DiceHistogram", $diceHistogram);

        for ($i = 0; $i < 10; $i++) {
            $diceHistogram->roll();
        }
        $exp = $diceHistogram->printHistogram();
        $histogram->injectData($diceHistogram);
        $res = $histogram->printHistogram();
        $this->assertEquals($exp, $res);
    }
}
