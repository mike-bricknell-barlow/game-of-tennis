<?php

namespace App;

class Game
{
    /** @var int[] */
    private array $scores;

    /** @var array<int, string> */
    private array $scoreMapping = [
        0 => 'love',
        1 => 'fifteen',
        2 => 'thirty',
        3 => 'forty',
    ];

    private bool $deuce;

    private bool $gameEnded;

    /** @return array<int, string> */
    public function start(): array
    {
        $this->scores = [
            0,
            0,
        ];
        $this->deuce = false;
        $this->gameEnded = false;

        return $this->run();
    }

    /** @return array<int, string> */
    public function run(): array
    {
        $output = [];
        /**
         * Long running loop to repeat rounds until there's a winner
         * Stops after 500 rounds to avoid infinite looping
         */
        for ($i = 0; $i < 500; $i++) {
            $output[] = $this->runRound();

            if ($this->gameEnded) {
                break;
            }
        }

        return $output;
    }

    public function runRound(?int $winner = null): string
    {
        // Use the passed $winner if available, or choose at random
        $roundWinner = ($winner) ? $winner : rand(0, 1);

        // Determine if this round is 'deuce'
        $this->deuce = false;
        if ($this->scores[0] >= 3 && $this->scores[1] >= 3) {
            $this->deuce = true;
        }

        // Increment $roundWinner's score
        $this->awardPointTo($roundWinner);
        if ($this->scores[$roundWinner] >= 4) {
            if (! $this->deuce) {
                // $roundWinner wins the game, end it
                return $this->end();
            }

            if ($this->scores[$roundWinner] - $this->scores[! $roundWinner] > 1) {
                // $roundWinner wins the game, end it
                return $this->end();
            }
        }

        // get current score
        return $this->getScore();
    }

    private function awardPointTo(int $player): void
    {
        $this->scores[$player]++;
    }

    public function getScore(): string
    {
        /**
         * If the scores are equal:
         *  - If both are 3 or more (forty each), the score is 'deuce'
         *  - Otherwise, the score is 'x all', e.g. 'thirty all'
         */
        if ($this->scores[0] == $this->scores[1]) {
            if ($this->scores[0] >= 3) {
                return 'The score is deuce';
            }

            return sprintf(
                'The score is %s all',
                $this->scoreMapping[$this->scores[0]]
            );
        }

        /**
         * If the scores are not equal:
         *  - If at least one of the scores is above 3, and no winner has been declared yet, the score is 'Advantage' to one of the players
         *  - Otherwise, the score is 'x - y', e.g. 'fifteen - love'
         */
        if ($this->scores[0] >= 4 || $this->scores[1] >= 4) {
            return sprintf(
                'The score is Advantage - Player %s',
                $this->currentWinner()
            );
        }

        return sprintf(
            'The score is %s - %s',
            $this->scoreMapping[$this->scores[0]],
            $this->scoreMapping[$this->scores[1]]
        );
    }

    /**
     * End the game and terminate the program
     */
    public function end(): string
    {
        $this->gameEnded = true;

        return sprintf(
            'Player %s has won the game',
            $this->currentWinner()
        );
    }

    /**
     * Determine the current leader by sorting the scores array DESC, and taking the first key
     */
    private function currentWinner(): int
    {
        $scores = $this->scores;
        arsort($scores);

        return (int) array_key_first($scores) + 1;
    }
}
