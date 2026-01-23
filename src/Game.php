<?php

namespace App;

class Game
{
    private Score $score;

    public function __construct()
    {
        $this->score = Score::start();
    }

    public function pointWonBy(Player $player): void
    {
        $this->score = $this->score->pointWonBy($player);
    }

    public function scoreLine(): string
    {
        return ScoreLine::from($this->score);
    }
}
