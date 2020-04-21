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
     * Use no arguments. Testing against default values.
     */
    public function testCreateObjectNoArguments()
    {
        $player = new Player();
        $this->assertInstanceOf("\Epkmagr\Dice\Player", $player);

        $res = $player->getName();
        $exp = "";
        $this->assertEquals($exp, $res);

        $res = $player->getNoOfDices();
        $exp = 2;
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and verify that the object is of expected instance.
     * Use a valid arguments.
     */
    public function testCreateObjectWithArgument()
    {
        $player = new Player("Marie", 5);
        $this->assertInstanceOf("\Epkmagr\Dice\Player", $player);

        $res = $player->getName();
        $exp = "Marie";
        $this->assertEquals($exp, $res);

        $res = $player->getNoOfDices();
        $exp = 5;
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
     * Test to set score.
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
     * Test to set score.
     */
    public function testGetGraphicValues()
    {
        $player = new Player("Marie");
        $player->rollAndReturnHand();
        $res = $player->getGraphicValues();
        $exp = "dice-";
        $this->assertStringContainsString($exp, $res[0]);
    }

    /**
     * Test to get sum of hand.
     */
    public function testGetSumOfHand()
    {
        $player = new Player("Marie");
        $player->rollAndReturnHand();
        $res = $player->getSumOfHand();
        $values = $player->getGraphicValues();
        $exp = 0;
        $limit = count($values);
        for ($i=0; $i<$limit; $i++) {
            $exp += substr($values[$i], 5, 1);
        }
        $this->assertStringContainsString($exp, $res);
    }

    /**
     * Construct object and test to roll the dices and get values in return.
     * Use no arguments.
     */
    public function testRollAndReturnHand()
    {
        $player = new Player("Marie");
        $res = $player->rollAndReturnHand();
        $values = $player->getGraphicValues();
        $this->assertGreaterThanOrEqual(2, array_sum($res));
        $this->assertStringContainsString($res[0], $values[0]);
        $this->assertStringContainsString($res[1], $values[1]);
    }
}
