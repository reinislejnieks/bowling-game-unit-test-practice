<?php

namespace PF;

use PF\Exceptions\BowlingGameException;

class BowlingGame
{
    private const MIN_SCORE_FOR_A_ROLL = 0;
    private const MAX_SCORE_FOR_A_STRIKE = 10;
    private const MAX_ROLL_COUNT = 21;
    private array $rolls = [];

    /**
     * @param int $score
     * @throws BowlingGameException
     */
    public function roll(int $score): void
    {
        $this->validateRoll($score);

        $this->rolls[] = $score;
    }

    public function getScore(): int
    {
        $score = 0;
        $roll = 0;

        if (count($this->rolls) > self::MAX_ROLL_COUNT) {
            throw new BowlingGameException(
                'Maximum allowed rolls in game can\'t be more than' . self::MAX_ROLL_COUNT
            );
        }

        for ($frame = 0; $frame < 10; $frame++) {
            if ($this->isStrike($roll)) {
                $score += 10 + $this->getStrikeBonus($roll);
                $roll++;
                continue;
            }

            if ($this->isSpare($roll)) {
                $score += $this->getSpareBonus($roll);

            }

            $score += $this->getNormalScore($roll);
            $roll += 2;
        }


        return $score;
    }

    /**
     * @param int $roll
     * @return int
     */
    private function getNormalScore(int $roll): int
    {
        return $this->rolls[$roll] + $this->rolls[$roll + 1];
    }

    /**
     * @param int $roll
     * @return bool
     */
    private function isSpare(int $roll): bool
    {
        return $this->getNormalScore($roll) === self::MAX_SCORE_FOR_A_STRIKE;
    }

    /**
     * @param int $roll
     * @return int
     */
    private function getSpareBonus(int $roll): int
    {
        return $this->rolls[$roll + 2];
    }

    /**
     * @param int $roll
     * @return int
     */
    private function getStrikeBonus(int $roll): int
    {
        return $this->rolls[$roll + 1] + $this->rolls[$roll + 2];
    }

    /**
     * @param int $roll
     * @return bool
     */
    private function isStrike(int $roll): bool
    {
        return $this->rolls[$roll] === self::MAX_SCORE_FOR_A_STRIKE;
    }

    /**
     * @param int $score
     * @throws BowlingGameException
     */
    private function validateRoll(int $score): void
    {
        if ($score < self::MIN_SCORE_FOR_A_ROLL) {
            throw new BowlingGameException('Score can\'t be negative!');
        }

        if ($score > self::MAX_SCORE_FOR_A_STRIKE) {
            throw new BowlingGameException('Score can\'t be more than ' . self::MAX_SCORE_FOR_A_STRIKE);
        }
    }
}
