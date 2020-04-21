<?php

namespace Epkmagr\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Player.
 */
class Dice100Test extends TestCase
{
    /**
     * Construct object and verify that the object is of expected instance.
     * Use no arguments.
     */
    public function testCreateObjectNoArguments()
    {
        $game = new Dice100();
        $this->assertInstanceOf("\Epkmagr\Dice\Dice100", $game);

        $res = $game->getTheNumberOfPlayers();
        $exp = 2;
        $this->assertEquals($exp, $res);
        $player = $game->getCurrentPlayer(0);
        $res = $player->getName();
        $exp = "";
        $this->assertEquals($exp, $res);
        $res = $player->getNoOfDices();
        $exp = 5;
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and verify that the object is of expected instance.
     * Use a valid arguments, 6 players and 5 dices per hand.
     */
    public function testCreateObjectWithArguments()
    {
        $game = new Dice100(6, 2);
        $this->assertInstanceOf("\Epkmagr\Dice\Dice100", $game);

        $res = $game->getTheNumberOfPlayers();
        $exp = 6;
        $this->assertEquals($exp, $res);
        $player = $game->getCurrentPlayer(0);
        $res = $player->getName();
        $exp = "";
        $this->assertEquals($exp, $res);
        $res = $player->getNoOfDices();
        $exp = 2;
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and verify that the object is of expected instance.
     * Use a faulty argument.
     */
    public function testCreateObjectWithFaultyNegativeArgument()
    {
        $this->expectException(DiceException::class);
        new Dice100(-1);
    }

    /**
     * Construct object and verify that the object is of expected instance.
     * Use a faulty argument. Only 6 players are allowed.
     */
    public function testCreateObjectWithFaultyArgument()
    {
        $this->expectException(DiceException::class);
        new Dice100(7);
    }


    /**
     * Test get current player.
     */
    public function testGetCurrentPlayer()
    {
        $game = new Dice100();
        $player1 = $game->getCurrentPlayer(0);
        $this->assertInstanceOf("\Epkmagr\Dice\Player", $player1);
        $player2 = $game->getCurrentPlayer(1);
        $this->assertInstanceOf("\Epkmagr\Dice\Player", $player2);
    }

    /**
     * Test get current player. Try to get a player that not exists and
     * null will be returned.
     */
    public function testGetCurrentPlayerNull()
    {
        $game = new Dice100();
        $res = $game->getCurrentPlayer(10);
        $exp = null;
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and test if the roll of the dices is valid or not.
     * Use valid arguments.
     */
    public function testDoRound()
    {
        $game = new Dice100();
        $player1 = $game->getCurrentPlayer(0);
        $res = $game->doRound($player1);
        $values = $player1->getGraphicValues();
        if (in_array("dice-1", $values, true)) {
            $exp = false;
        } else {
            $exp = true;
        }
        $this->assertEquals($exp, $res);
    }

    /**
     * Run the testDoRound 10 times to test unvalid roll of dice.
     * Use valid arguments.
     */
    public function testDoRound10Times()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->testDoRound();
        }
    }

    /**
     * Construct object and test the startOrder from the first dice in the hand.
     * of the dices of this round is above the limit.
     * Use a valid arguments, 2 players and 5 dices per hand.
     */
    public function testStartOrder2Players()
    {
        $game = new Dice100(2, 5);
        $startValues = array();

        $res = $game->startOrder();
        for ($i = 0; $i < $game->getTheNumberOfPlayers(); $i++) {
            $startValues[$i] = $game->getCurrentPlayer($i)->getGraphicValues()[0];
        }
        arsort($startValues);
        $exp = array_key_first($startValues);
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and test the startOrder from the first dice in the hand.
     * of the dices of this round is above the limit.
     * Use a valid arguments, 5 players and 5 dices per hand.
     */
    public function testStartOrder5Players()
    {
        $game = new Dice100(5, 5);
        $startValues = array();

        $res = $game->startOrder();
        for ($i = 0; $i < $game->getTheNumberOfPlayers(); $i++) {
            $startValues[$i] = $game->getCurrentPlayer($i)->getGraphicValues()[0];
        }
        arsort($startValues);
        $exp = array_key_first($startValues);
        $this->assertEquals($exp, $res);
    }
}
