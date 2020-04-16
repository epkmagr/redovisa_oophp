<?php

namespace Epkmagr\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Player.
 */
class PlayerTest extends TestCase
{
    /**
     * Construct object and verify that the object is of expected instance.
     * Use no arguments.
     */
    public function testCreateObjectNoArguments()
    {
        $player = new Player();
        $this->assertInstanceOf("\Epkmagr\Dice\Player", $player);
    }

    /**
     * Construct object and verify that the object is of expected instance.
     * Use a valid argument.
     */
    public function testCreateObjectWithArgument()
    {
        $player = new Player("Marie");
        $this->assertInstanceOf("\Epkmagr\Dice\Player", $player);

        $res = $player->getName();
        $exp = "Marie";
        $this->assertEquals($exp, $res);
    }

    /**
     * Test set name.
     * Use a valid argument.
     */
    public function testSetName()
    {
        $player = new Player();
        $player->setName("Marie");
        $res = $player->getName();
        $exp = "Marie";
        $this->assertEquals($exp, $res);
    }

    /**
     * Test get score.
     */
    public function testGetScore()
    {
        $player = new Player("Marie");
        $res = $player->getScore();
        $exp = 0;
        $this->assertEquals($exp, $res);
    }

    /**
     * Test get score.
     */
    public function testSetScore()
    {
        $player = new Player("Marie");
        $player->setScore(90);
        $res = $player->getScore();
        $exp = 90;
        $this->assertEquals($exp, $res);
    }


    /**
     * Construct object and do a round until $sum = 0. Dices with a 1.
     * Use no arguments.
     */
    public function testDoRoundUntilZero()
    {
        $player = new Player("Marie");
        do {
            $sum = $player->doRound();
            $this->assertGreaterThanOrEqual(0, $sum);
        } while ($sum === 0);
    }

    /**
     * Construct object and do a round until $sum != 0. Dices without a 1.
     * Use no arguments.
     */
    public function testDoRound()
    {
        $player = new Player("Marie");
        do {
            $sum = $player->doRound();
            $this->assertGreaterThanOrEqual(0, $sum);
        } while ($sum > 0);
    }

    /**
     * Construct object and test to roll the dices and get values in return.
     * Use no arguments.
     */
    public function testRollAndReturnHand()
    {
        $player = new Player("Marie");
        $values = $player->rollAndReturnHand();
        $this->assertGreaterThanOrEqual(5, array_sum($values));
    }
}
