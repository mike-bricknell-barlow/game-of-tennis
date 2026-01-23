<?php

/*
 * These tests drive the tennis scoring domain by modelling how points are won,
 * rather than asserting against internal score representations.
 */

use App\Game;
use App\Player;
use App\Score;
use App\ScoreLine;

it('starts at love all', function () {
    $score = Score::start();

    expect(ScoreLine::from($score))
        ->toBe('The score is love all');
});

it('describes a normal score correctly', function () {
    $score = Score::start()
        ->pointWonBy(Player::One);

    expect(ScoreLine::from($score))
        ->toBe('The score is fifteen - love');
});

it('returns deuce when both players reach forty', function () {
    $score = Score::start()
        ->pointWonBy(Player::One)
        ->pointWonBy(Player::One)
        ->pointWonBy(Player::One)
        ->pointWonBy(Player::Two)
        ->pointWonBy(Player::Two)
        ->pointWonBy(Player::Two);

    expect(ScoreLine::from($score))
        ->toBe('The score is deuce');
});

it('returns advantage when a player leads after deuce', function () {
    $score = Score::start();

    for ($i = 0; $i < 3; $i++) {
        $score = $score->pointWonBy(Player::One);
        $score = $score->pointWonBy(Player::Two);
    }

    $score = $score->pointWonBy(Player::One);

    expect(ScoreLine::from($score))
        ->toBe('The score is Advantage - Player 1');
});

it('returns to deuce when advantage is lost', function () {
    $score = Score::start();

    for ($i = 0; $i < 3; $i++) {
        $score = $score->pointWonBy(Player::One);
        $score = $score->pointWonBy(Player::Two);
    }

    $score = $score->pointWonBy(Player::One);
    $score = $score->pointWonBy(Player::Two);

    expect(ScoreLine::from($score))
        ->toBe('The score is deuce');
});

it('ends the game when a player wins by two clear points after deuce', function () {
    $score = Score::start();

    for ($i = 0; $i < 3; $i++) {
        $score = $score->pointWonBy(Player::One);
        $score = $score->pointWonBy(Player::Two);
    }

    $score = $score->pointWonBy(Player::One);
    $score = $score->pointWonBy(Player::One);

    expect(ScoreLine::from($score))
        ->toBe('Player 1 has won the game');
});

it('does not allow points to be awarded after the game is won', function () {
    $score = Score::start()
        ->pointWonBy(Player::One)
        ->pointWonBy(Player::One)
        ->pointWonBy(Player::One)
        ->pointWonBy(Player::One);

    expect(fn () => $score->pointWonBy(Player::One)
    )->toThrow(LogicException::class);
});

it('allows a game to be driven point by point', function () {
    $game = new Game;

    $game->pointWonBy(Player::One);
    $game->pointWonBy(Player::Two);
    $game->pointWonBy(Player::One);

    expect($game->scoreLine())
        ->toBe('The score is thirty - fifteen');
});
