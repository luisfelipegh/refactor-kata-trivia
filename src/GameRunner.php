<?php

namespace RefactorGame;

class GameRunner
{
    public bool $notAWinner;
    public Game $game;
    
    public function __construct()
    {
        $this->game = new Game();
    }
    
    public function run()
    {
        $this->game->addPlayer('Chet');
        $this->game->addPlayer('Pat');
        $this->game->addPlayer('Sue');

        if (!$this->game->isPlayable()) {
            return ;
        }

        do {
            $this->game->roll(random_int(0, 6));

            $notAWinner = random_int(0, 9) == 7 ? $this->game->wrongAnswer() : $this->game->wasCorrectlyAnswered();
        } while ($notAWinner);
    }
}
