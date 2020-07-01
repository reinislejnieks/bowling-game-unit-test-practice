<?php

use PF\BowlingGame;
use PF\Exceptions\BowlingGameException;
use PHPUnit\Framework\TestCase;

class BowlingGameTest extends TestCase
{
    public function testGetScore_withAllZeroes_getScoreZero()
    {
        // setup part
        $game = new BowlingGame();

        for ($i = 0; $i < 20; $i++) {
            $game->roll(0);
        }
        // test part
        $score = $game->getScore();

        // assert part
        $this->assertEquals(0, $score);
    }

    public function testGetScore_withAllOnes_getScore20()
    {
        // setup part
        $game = new BowlingGame();

        for ($i = 0; $i < 20; $i++) {
            $game->roll(1);
        }
        // test part
        $score = $game->getScore();

        // assert part
        $this->assertEquals(20, $score);
    }

    public function testGetScore_withSpare_getScoreWithSpareBonus()
    {
        // setup part
        $game = new BowlingGame();

        $game->roll(2);
        $game->roll(8);
        $game->roll(5);

        for ($i = 0; $i < 17; $i++) {
            $game->roll(1);
        }
        // test part
        $score = $game->getScore();

        // assert part
        $this->assertEquals(37, $score);
    }

    public function testGetScore_withStrike_getScoreWithStrikeBonus()
    {
        // setup part
        $game = new BowlingGame();

        $game->roll(10);
        $game->roll(3);
        $game->roll(5);

        for ($i = 0; $i < 16; $i++) {
            $game->roll(1);
        }
        // test part
        $score = $game->getScore();

        // assert part
        $this->assertEquals(42, $score);
    }

    public function testGetScore_withAComplicatedGame_getsCorrectScore()
    {
        // setup part
        $game = new BowlingGame();

        $game->roll(10);
        $game->roll(3);
        $game->roll(5);

        $game->roll(10);
        $game->roll(10);
        $game->roll(4);
        $game->roll(4);

        for ($i = 0; $i < 10; $i++) {
            $game->roll(1);
        }
        // test part
        $score = $game->getScore();

        // assert part
        $this->assertEquals(86, $score);
    }

    public function testGetScore_withAPerfectGeme_getScore300()
    {
        // setup part
        $game = new BowlingGame();

        for ($i = 0; $i < 12; $i++) {
            $game->roll(10);
        }
        // test part
        $score = $game->getScore();

        // assert part
        $this->assertEquals(300, $score);
    }

    public function testRoll_withANegativeScore_getException()
    {
        $game = new BowlingGame();

        $this->expectException(BowlingGameException::class);
        $game->roll(-1);
    }

    public function testRoll_withBiggerScoreThanAllowed_getException()
    {
        $game = new BowlingGame();

        $this->expectException(BowlingGameException::class);
        $game->roll(11);
    }
}
