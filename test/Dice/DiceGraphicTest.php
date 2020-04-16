<?php

namespace Epkmagr\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class DiceGraphicTest extends TestCase
{
    /**
     * Construct object and verify that the object is of expected instance.
     * Use no arguments.
     */
    public function testCreateObject()
    {
        $diceGraphic = new DiceGraphic();
        $this->assertInstanceOf("\Epkmagr\Dice\DiceGraphic", $diceGraphic);
    }

    /**
     * Construct object and verify that the object is of expected instance.
     * Use a valid argument.
     */
    public function testGraphic()
    {
        $diceGraphic = new DiceGraphic();
        $res = $diceGraphic->graphic();
        $this->assertStringContainsString("dice-", $res);
    }
}
