# Tennis Scoring – Domain Model Overview

## Purpose

This updated implementation focuses on modelling the rules of tennis scoring as a small domain. The goal is to make the rules and state transitions easy to understand, test, and extend, and not overengineering out of proportion to the size of the problem.

## Domain Concepts

### Score (Core Domain Model)

The Score class is the main part of the solution and represents the current state of a single game.

Key information:

 - Immutable object
    - A Score instance never changes once created. Each point won returns a new Score, making state transitions explicit and preventing accidental mutation.
 - Rules encapsulated in the model
    - All scoring rules, deuce, advantage, winning conditions, are handled by Score. The game cannot be put into an invalid state.
 - Illegal states are prevented
    - Scores cannot be negative
    - Points cannot be awarded after the game has been won
    - Advantage and deuce states are derived from point values rather than explicit flags
 - Domain-driven language
    - Methods such as pointWonBy, hasWinner, isDeuce, and isAdvantage reflect the language of tennis scoring instead of generic operations.

This treats tennis scoring as a small state machine while avoiding the need for explicit state classes, which would add complexity without improving clarity at this scale.

### Player (Domain Identifier)

Player is represented as a small enum rather than a full entity.

This choice was made deliberately:

 - Players have no behaviour/attributes in this scenario
 - Using an enum avoids magic numbers and improves readability
 - Introducing a richer Player object would add complexity without value

### ScoreLine (Presentation Boundary)

ScoreLine is responsible for converting a Score into a human-readable description.

Separating this logic from Score:

 - Keeps the domain model free of presentation
 - Makes it easier to change output formatting independently of scoring rules
 - Defines a clear boundary between logic and output

### Game (Thin Orchestration)

The optional Game class acts as a coordinator:

 - It holds the current Score
 - It forwards domain events (pointWonBy)
 - It exposes the current score line

All business rules remain in the Score model, with game containing no scoring logic.

## Testing Approach

Tests drive the domain by modelling how points are won.

Key information:

 - Tests interact only with public APIs
 - Transitions such as deuce, advantage, and win are reached through realistic sequences of points
 - Illegal actions are explicitly tested

## Deliberate Omissions

Several things were intentionally not included:

 - Randomised gameplay or simulation loops
    - The focus is on modelling scoring rules, not simulating matches.
 - Explicit state classes (e.g. DeuceState, AdvantageState)
    - These would not add much clarity for this problem size, but would increase complexity.
 - Player objects
    - Players are interchangeable and have no domain behaviour in this exercise.
 - Persistence, e.g. set or match-level scoring
    - These would be natural extensions, but are outside the scope of the exercise

## Summary

This design prioritises:

 - Clear domain language
 - Explicit, testable state transitions
 - Prevention of invalid states
 - Proportional complexity