<?php

namespace Epkmagr\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Player.
 */
class Dice100ComputerTest extends TestCase
{
    /**
     * Construct object and test that the method returns true if the sum
     * of the dices of this round is above the limit.
     * Use a valid arguments, 2 players and 5 dices per hand.
     */
    public function testComputerContinuesAboveLimit2Dices()
    {
        $game = new Dice100(2, 5);

        $res = $game->computerContinues(22);
        $exp = false;
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and test that the method returns false if the sum
     * of the dices of this round  is below the limit.
     * Use a valid arguments, 2 players and 1 dices per hand.
     */
    public function testComputerContinuesBelowLimit1Dice()
    {
        $game = new Dice100(2, 1);

        $res = $game->computerContinues(23);
        $exp = true;
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and test that the method returns false if the sum
     * of the dices of this round  is below the limit.
     * Use a valid arguments, 2 players and 5 dices per hand.
     */
    public function testComputerContinuesBelowLimit2Dices()
    {
        $game = new Dice100(2, 2);

        $res = $game->computerContinues(12);
        $exp = true;
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and test that the method returns false if the sum
     * of the dices of this round  is below the limit.
     * Use a valid arguments, 2 players and 5 dices per hand.
     */
    public function testComputerContinuesBelowLimit3Dices()
    {
        $game = new Dice100(2, 3);

        $res = $game->computerContinues(21);
        $exp = true;
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and test that the method returns false if the sum
     * of the dices of this round  is below the limit.
     * Use a valid arguments, 2 players and 4 dices per hand.
     */
    public function testComputerContinuesBelowLimit4Dices()
    {
        $game = new Dice100(2, 4);

        $res = $game->computerContinues(11);
        $exp = true;
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and test that the method returns false if the sum
     * of the dices of this round  is below the limit.
     * Use a valid arguments, 2 players and 8 dices per hand.
     */
    public function testComputerContinuesBelowLimit8Dices()
    {
        $game = new Dice100(2, 8);

        $res = $game->computerContinues(9);
        $exp = true;
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and test that the method returns true if 100 - the sum
     * of the dices of this round is less than the limit.
     * Use a valid arguments, 2 players and 5 dices per hand.
     */
    public function testComputerContinuesCloseTo100With2Dices()
    {
        $game = new Dice100(2, 2);

        $player = $game->getCurrentPlayer(0);
        $player->setScore(88);
        $res = $game->computerContinues(20);
        $exp = true;
        $this->assertEquals($exp, $res);
    }
}
