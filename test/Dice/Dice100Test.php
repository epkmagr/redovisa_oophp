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
    }

    /**
     * Construct object and verify that the object is of expected instance.
     * Use a valid argument.
     */
    public function testCreateObjectWithArgument()
    {
        $game = new Dice100(6);
        $this->assertInstanceOf("\Epkmagr\Dice\Dice100", $game);

        $res = $game->getTheNumberOfPlayers();
        $exp = 6;
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
        $exp = 0;
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
        $this->assertGreaterThanOrEqual(5, array_sum($values));
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
        $values = $game->endRound($player1, $expScore);
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
        $values = $game->endRound($player1, $roundScore);
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
        $values = $game->endRound($player1, $roundScore);
        $expScore = 20;
        $this->assertEquals($expScore, $player1->getScore());
    }

    // /**
    //  * Test play.
    //  */
    // public function testPlay()
    // {
    //     $game = new Dice100();
    //     $res = $game->startOrder();
    //     $exp = 0;
    //     $this->assertEquals($exp, $res);
    // }
}
