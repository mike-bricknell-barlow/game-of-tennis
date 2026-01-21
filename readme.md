# Tennis Game Simulation

This project implements a simple simulation of a single tennis game.

## Overview

The Game class models the scoring rules of a single tennis game between two players. It tracks point progression, handles deuce and advantage states, and determines when a game has been won.

## Usage

 - Clone the repo
 - Run `composer install`
 - See below for running tests

## Game class

The Game class is responsible for:

 - Tracking player scores internally
 - Translating numeric scores into tennis scoring terminology
 - Handling all valid score states:
    - Love through forty
    - Deuce
    - Advantage
    - Game win
 - Simulating a full game from start to finish

The public API:

`start()`
Resets the game and runs rounds until a winner is declared. Returns an array of score updates as strings.

`run()`
Executes the game loop and returns all intermediate score descriptions, ending with a win message.

`getScore()`
Returns a human-readable description of the current score.

## Tests

The test suite is written using Pest.

The tests cover:

 - All distinct tennis score states, including extended deuce and advantage scenarios
 - Correct score descriptions for tied and non-tied states
 - Full game simulation from start to end
 - Verification that a game always ends with a valid win message for either player

## Tooling and code quality

This project includes common PHP tooling to demonstrate code quality practices.

### Pest (unit tests)

Run the test suite:

`composer test`

Current tests:

 - If a game starts with a score of 'love all' (0, 0)
 - If a representative mid-game score outputs a correctly formatted result
 - If score is returned as 'deuce' when both players are on 'forty'
 - If score is returned as 'advantage' when one player wins a round after 'deuce;
 - If all possible score combinations are correctly returned
 - If correct status is returned when a game ends
 - If a game can be run from start to finish, with each round assigning a random winner

### PHPStan (static analysis)

PHPStan is configured to analyse both source code and tests.

Run the analysis:

`composer analyse`

### Pint (linting and formatting)

Check code style without modification:

`composer lint`

Automatically format code:

`composer format`
