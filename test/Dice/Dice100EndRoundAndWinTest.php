<?php

namespace Epkmagr\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Player.
 */
class Dice100EndRoundAndWinTest extends TestCase
{
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

    /**
     * Test to print a histogram.
     */
    public function testPrintHistogram()
    {
        $game = new Dice100();
        $histogram = new Histogram();
        $diceHistogram = new DiceHistogram();

        for ($i = 0; $i < 10; $i++) {
            $diceHistogram->roll();
        }
        $exp = $diceHistogram->printHistogram();
        $histogram->injectData($diceHistogram);
        $game->setHistogram($histogram);
        $res = $game->getHistogram();
        $this->assertEquals($exp, $res);
    }
}
