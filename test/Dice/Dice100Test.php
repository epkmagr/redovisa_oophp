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
    public function testCreateObjectWithFaultyArgument()
    {
        $this->expectException(DiceException::class);
        new Dice100(-1);
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
     * Test the start order.
     */
    public function testStartOrder()
    {
        $game = new Dice100();
        $res = $game->startOrder();
        $exp = 1;
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and test to roll the dices and get values in return.
     * Use valid arguments.
     */
    public function testDoRound()
    {
        $game = new Dice100();
        $player1 = $game->getCurrentPlayer(0);
        $values = $game->doRound($player1);
        $exp = $player1->getNoOfDices();
        $this->assertGreaterThanOrEqual(2, array_sum($values));
    }

    /**
     * Construct object and test to end the round and save score. The score of
     * the current player is 0. The round score is 43. The new score is 43.
     * Use valid arguments.
     */
    public function testEndRound43()
    {
        $game = new Dice100();
        $player1 = $game->getCurrentPlayer(0);
        $expScore = 43;
        $game->endRound($player1, $expScore);
        $this->assertEquals($expScore, $player1->getScore());
    }

    /**
     * Construct object and test to end the round and save score. The score of
     * the current player is 20. The round score is 43. The new score is 63.
     * Use valid arguments.
     */
    public function testEndRound63()
    {
        $game = new Dice100();
        $player1 = $game->getCurrentPlayer(0);
        $player1->setScore(20);
        $roundScore = 43;
        $game->endRound($player1, $roundScore);
        $expScore = 63;
        $this->assertEquals($expScore, $player1->getScore());
    }

    /**
     * Construct object and test to end the round and save score. The score of
     * the current player is 20. The round score is 0. The new score is 20.
     * Use valid arguments.
     */
    public function testEndRound20()
    {
        $game = new Dice100();
        $player1 = $game->getCurrentPlayer(0);
        $player1->setScore(20);
        $roundScore = 0;
        $game->endRound($player1, $roundScore);
        $expScore = 20;
        $this->assertEquals($expScore, $player1->getScore());
    }

    /**
     * Test win when score is lower than GOAL.
     */
    public function testWinFalse()
    {
        $game = new Dice100();
        $res = $game->win(19);
        $exp = false;
        $this->assertEquals($exp, $res);
    }

    /**
     * Test win true when score is higher than GOAL.
     */
    public function testWinTrue()
    {
        $game = new Dice100();
        $res = $game->win(102);
        $exp = true;
        $this->assertEquals($exp, $res);
    }

    /**
     * Test win true when score is equal to GOAL.
     */
    public function testWinTrue100()
    {
        $game = new Dice100();
        $res = $game->win(100);
        $exp = true;
        $this->assertEquals($exp, $res);
    }
}
