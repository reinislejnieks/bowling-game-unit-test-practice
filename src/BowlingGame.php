<?php

namespace PF;

use PF\Exceptions\BowlingGameException;

class BowlingGame
{
    private const MIN_SCORE_FOR_A_ROLL = 0;
    private const MAX_ROLL_COUNT = 21;
    private const MIN_ROLL_COUNT = 12;
    private const SCORE_FOR_A_STRIKE = 10;
    private const MAX_FRAMES_PER_GAME = 10;
    private array $rolls = [];

    /**
     * @param int $score
     * @throws BowlingGameException
     */
    public function roll(int $score): void
    {
        $this->validateSingleRollScore($score);

        $this->rolls[] = $score;
    }

    /**
     * @return int
     * @throws BowlingGameException
     */
    public function getScore(): int
    {
        $score = 0;
        $roll = 0;

        $this->validateRollCount();

        for ($frame = 0; $frame < self::MAX_FRAMES_PER_GAME; $frame++) {
            if ($this->isStrike($roll)) {
                $score += self::SCORE_FOR_A_STRIKE + $this->getStrikeBonus($roll);
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
        return $this->getNormalScore($roll) === self::SCORE_FOR_A_STRIKE;
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
        return $this->rolls[$roll] === self::SCORE_FOR_A_STRIKE;
    }

    /**
     * @param int $score
     * @throws BowlingGameException
     */
    private function validateSingleRollScore(int $score): void
    {
        if ($score < self::MIN_SCORE_FOR_A_ROLL) {
            throw new BowlingGameException('Score can\'t be negative!');
        }

        if ($score > self::SCORE_FOR_A_STRIKE) {
            throw new BowlingGameException('Score can\'t be more than ' . self::SCORE_FOR_A_STRIKE);
        }
    }

    private function validateRollCount(): void
    {
        $rollCount = count($this->rolls);
        if ($rollCount > self::MAX_ROLL_COUNT) {
            throw new BowlingGameException(
                'Maximum allowed rolls in game can\'t be more than' . self::MAX_ROLL_COUNT
            );
        }

        if ($rollCount < self::MIN_ROLL_COUNT) {
            throw new BowlingGameException(
                'Minimum allowed rolls in game can\'t be less than' . self::MIN_ROLL_COUNT
            );
        }
    }
}
