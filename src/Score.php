<?php

namespace App;

class Score
{
    private int $playerOne;

    private int $playerTwo;

    private function __construct(int $playerOne, int $playerTwo)
    {
        if ($playerOne < 0 || $playerTwo < 0) {
            throw new \InvalidArgumentException('Scores cannot be negative.');
        }

        $this->playerOne = $playerOne;
        $this->playerTwo = $playerTwo;
    }

    public static function start(): self
    {
        return new self(0, 0);
    }

    public function pointWonBy(Player $player): self
    {
        if ($this->hasWinner()) {
            throw new \LogicException('Cannot award points after the game has been won.');
        }

        return match ($player) {
            Player::One => new self($this->playerOne + 1, $this->playerTwo),
            Player::Two => new self($this->playerOne, $this->playerTwo + 1),
        };
    }

    public function hasWinner(): bool
    {
        if ($this->playerOne < 4 && $this->playerTwo < 4) {
            return false;
        }

        return abs($this->playerOne - $this->playerTwo) >= 2;
    }

    public function winner(): ?Player
    {
        if (! $this->hasWinner()) {
            return null;
        }

        return $this->playerOne > $this->playerTwo
            ? Player::One
            : Player::Two;
    }

    public function isDeuce(): bool
    {
        return $this->playerOne >= 3
            && $this->playerOne === $this->playerTwo;
    }

    public function isAdvantage(): bool
    {
        if ($this->hasWinner()) {
            return false;
        }

        if ($this->playerOne < 4 && $this->playerTwo < 4) {
            return false;
        }

        return abs($this->playerOne - $this->playerTwo) === 1;
    }

    public function leadingPlayer(): Player
    {
        if ($this->playerOne === $this->playerTwo) {
            throw new \LogicException('No leading player when scores are equal.');
        }

        return $this->playerOne > $this->playerTwo
            ? Player::One
            : Player::Two;
    }

    public function pointsFor(Player $player): int
    {
        return $player === Player::One
            ? $this->playerOne
            : $this->playerTwo;
    }
}
