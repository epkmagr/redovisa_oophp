<?php

namespace Epkmagr\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DiceHand.
 */
class DiceHandTest extends TestCase
{
    /**
     * Construct object and verify that the object is of expected instance.
     * Use no arguments.
     */
    public function testCreateObjectNoArguments()
    {
        $diceHand = new DiceHand();
        $this->assertInstanceOf("\Epkmagr\Dice\DiceHand", $diceHand);

        $res = $diceHand->getNoOfDices();
        $exp = 5;
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and verify that the object is of expected instance.
     * Use a valid argument.
     */
    public function testCreateObjectWithArgument()
    {
        $diceHand = new DiceHand(4);
        $this->assertInstanceOf("\Epkmagr\Dice\DiceHand", $diceHand);

        $res = $diceHand->getNoOfDices();
        $exp = 4;
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and verify that the object is of expected instance.
     * Use a faulty argument.
     */
    public function testCreateObjectWithFaultyArgument()
    {
        $this->expectException(DiceException::class);
        new DiceHand(-1);
    }

    /**
     * Construct object and roll the DiceHand.
     * Use no arguments.
     */
    public function testRollAndCheckValue()
    {
        $diceHand = new DiceHand();
        $diceHand->roll();
        $res = $diceHand->values();
        $exp = 5; // 5 values
        $this->assertEquals($exp, count($res));
        $this->assertGreaterThanOrEqual($exp, $res);
    }

    /**
     * Construct object and check the sum.
     * Use no arguments.
     */
    public function testSum()
    {
        $diceHand = new DiceHand();
        $diceHand->roll();
        $res = $diceHand->sum();
        $exp = array_sum($diceHand->values());
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and check the average.
     * Use no arguments.
     */
    public function testAverage()
    {
        $diceHand = new DiceHand();
        $diceHand->roll();
        $res = $diceHand->average();
        $exp = round((array_sum($diceHand->values())) / 5, 2);
        $this->assertGreaterThanOrEqual($exp, $res);
    }
}
