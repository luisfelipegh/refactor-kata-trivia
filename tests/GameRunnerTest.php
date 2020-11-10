<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use RefactorGame\Game;
use RefactorGame\GameRunner;

class GameRunnerTest extends TestCase
{
    public function testRun()
    {
        $gameRunner = new GameRunner();

        $gameRunner->run();

        $this->assertEquals(3, $gameRunner->game->howManyPlayers());
    }

    public function test__construct()
    {
        $gameRunner = new GameRunner();

        $this->assertInstanceOf(Game::class, $gameRunner->game);
    }
}
