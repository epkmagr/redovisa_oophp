<?php

namespace Epkmagr\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class DiceTest extends TestCase
{
    /**
     * Construct object and verify that the object is of expected instance.
     * Use no arguments.
     */
    public function testCreateObjectNoArguments()
    {
        $dice = new Dice();
        $this->assertInstanceOf("\Epkmagr\Dice\Dice", $dice);
    }

    /**
     * Construct object and verify that the object is of expected instance.
     * Use a valid argument.
     */
    public function testCreateObjectWithArgument()
    {
        $dice = new Dice(4);
        $this->assertInstanceOf("\Epkmagr\Dice\Dice", $dice);
    }

    /**
     * Construct object and verify that the object is of expected instance.
     * Use a faulty argument.
     */
    public function testCreateObjectWithFaultyArgument()
    {
        $this->expectException(DiceException::class);
        new Dice(-1);
    }

    /**
     * Construct object and roll the dice.
     * Use no arguments.
     */
    public function testRollAndCheckValue()
    {
        $dice = new Dice();
        $dice->roll();
        $res = $dice->getLastRoll();
        $this->assertLessThanOrEqual(6, $res);
        $this->assertGreaterThanOrEqual(1, $res);
    }

    /**
     * Construct object and roll the dice.
     * Use a strange argument when performing the roll.
     */
    public function testRollOneSide()
    {
        $dice = new Dice(1);
        $dice->roll();
        $res = $dice->getLastRoll();
        $this->assertLessThanOrEqual(1, $res);
        $this->assertGreaterThanOrEqual(1, $res);
    }
}
