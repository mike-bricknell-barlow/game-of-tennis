<?php

namespace App;

class ScoreLine
{
    private const POINTS = ['love', 'fifteen', 'thirty', 'forty'];

    public static function from(Score $score): string
    {
        if ($score->hasWinner()) {
            return sprintf(
                'Player %d has won the game',
                $score->winner()->value
            );
        }

        if ($score->isDeuce()) {
            return 'The score is deuce';
        }

        if ($score->isAdvantage()) {
            return sprintf(
                'The score is Advantage - Player %d',
                $score->leadingPlayer()->value
            );
        }

        $p1 = $score->pointsFor(Player::One);
        $p2 = $score->pointsFor(Player::Two);

        if ($p1 === $p2) {
            return sprintf(
                'The score is %s all',
                self::POINTS[$p1]
            );
        }

        return sprintf(
            'The score is %s - %s',
            self::POINTS[$p1],
            self::POINTS[$p2]
        );
    }
}
