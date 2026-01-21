<?php

use App\Game;

/**
 * Returns a Game object with the specified score state
 *
 * @param  array<int, int>  $scores
 */
function gameWithScore(array $scores): Game
{
    $game = new Game;

    $reflection = new ReflectionClass($game);
    $property = $reflection->getProperty('scores');
    $property->setAccessible(true);
    $property->setValue($game, $scores);

    return $game;
}

it('starts at love all', function () {
    $game = gameWithScore([0, 0]);
    expect($game->getScore())->toBe('The score is love all');
});

it('describes a normal score correctly', function () {
    $game = gameWithScore([1, 0]);
    expect($game->getScore())->toBe('The score is fifteen - love');
});

it('returns deuce when both players have forty', function () {
    $game = gameWithScore([3, 3]);
    expect($game->getScore())->toBe('The score is deuce');
});

it('returns advantage when one player leads after deuce', function () {
    $game = gameWithScore([4, 3]);
    expect($game->getScore())->toBe('The score is Advantage - Player 1');
});

it('returns advantage for player two when they lead', function () {
    $game = gameWithScore([3, 4]);
    expect($game->getScore())->toBe('The score is Advantage - Player 2');
});

it('describes all possible tennis score states correctly', function (array $scores, string $expected) {
    $game = gameWithScore($scores);
    expect($game->getScore())->toBe($expected);
})->with([
    [[0, 0], 'The score is love all'],
    [[1, 0], 'The score is fifteen - love'],
    [[0, 1], 'The score is love - fifteen'],
    [[2, 0], 'The score is thirty - love'],
    [[0, 2], 'The score is love - thirty'],
    [[3, 0], 'The score is forty - love'],
    [[0, 3], 'The score is love - forty'],
    [[1, 1], 'The score is fifteen all'],
    [[2, 1], 'The score is thirty - fifteen'],
    [[1, 2], 'The score is fifteen - thirty'],
    [[3, 1], 'The score is forty - fifteen'],
    [[1, 3], 'The score is fifteen - forty'],
    [[2, 2], 'The score is thirty all'],
    [[3, 2], 'The score is forty - thirty'],
    [[2, 3], 'The score is thirty - forty'],
    [[3, 3], 'The score is deuce'],
    [[4, 3], 'The score is Advantage - Player 1'],
    [[3, 4], 'The score is Advantage - Player 2'],
    [[5, 5], 'The score is deuce'], // Scores above 3 represent post-deuce advantage states.
    [[5, 4], 'The score is Advantage - Player 1'],
    [[4, 5], 'The score is Advantage - Player 2'],
]);

it('ends the game when a player has won', function () {
    $game = gameWithScore([4, 0]);
    $result = $game->end();
    expect($result)->toBe('Player 1 has won the game');
});

it('simulates game start to end and declares a winner', function () {
    $game = new Game;
    $result = $game->start();
    expect($result)->toBeArray();
    expect($result)->each->toBeString();
    expect($result[array_key_last($result)])->toBeIn([
        'Player 1 has won the game',
        'Player 2 has won the game',
    ]);
});
