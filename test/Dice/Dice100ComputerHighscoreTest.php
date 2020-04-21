<?php

namespace Epkmagr\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Player.
 */
class Dice100ComputerHighscoreTest extends TestCase
{
    /**
     * Construct object and test that the method returns true if the computer
     * is behind and the difference is below limit.
     * Use a valid arguments, 2 players and 3 dices per hand.
     */
    public function testComputerContinuesCompareWithHighscoreTrue()
    {
        $game = new Dice100(2, 3);
        $game->getCurrentPlayer(0)->setScore(70);
        $game->getCurrentPlayer(1)->setScore(80);

        $res = $game->computerContinues(30);
        $exp = true;
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and test that the method returns false if the computer
     * is behind and the difference is above limit.
     * Use a valid arguments, 2 players and 3 dices per hand.
     */
    public function testComputerContinuesCompareWithHighscoreFalse()
    {
        $game = new Dice100(2, 3);
        $game->getCurrentPlayer(0)->setScore(20);
        $game->getCurrentPlayer(1)->setScore(80);

        $res = $game->computerContinues(30);
        $exp = false;
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and test that the method returns false because another
     * player has 60 and computer has 20 and tmpScore is 40 which is larger
     * than 2*limit that is 36.
     * Use a valid arguments, 2 players and 2 dices per hand.
     */
    public function testComputerContinuesBigHighScore()
    {
        $game = new Dice100(2, 2);

        $player1 = $game->getCurrentPlayer(0);
        $player1->setScore(20);
        $player2 = $game->getCurrentPlayer(0);
        $player2->setScore(60);
        $res = $game->computerContinues(40);
        $exp = false;
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and test that the method returns false because another
     * player has 60 and computer has 50 and tmpScore is 40 which is larger
     * than 2*limit that is 36.
     * Use a valid arguments, 2 players and 2 dices per hand.
     */
    public function testComputerContinuesSmallHighScore()
    {
        $game = new Dice100(2, 2);

        $player1 = $game->getCurrentPlayer(0);
        $player1->setScore(50);
        $player2 = $game->getCurrentPlayer(0);
        $player2->setScore(60);
        $res = $game->computerContinues(40);
        $exp = false;
        $this->assertEquals($exp, $res);
    }
}
