<?php

namespace RefactorGame;

use RefactorGame\Helpers\Categories;
use RefactorGame\Helpers\Logger;

class Game
{
    public const MINIMUM_PLAYERS = 2;
    public const MAX_VALUE_PLACES = 6;
    public const LENGTH_BOARD = 12;
    public const INITIAL_POSITION = 0;

    public array $players = [];
    public array $categories = [];

    public $currentPlayer = 0;

    public function __construct()
    {
        $this->players = [];
        $this->categories = Categories::createCategories();
    }

    public function isPlayable()
    {
        return ($this->howManyPlayers() >= self::MINIMUM_PLAYERS);
    }

    public function addPlayer(string $name)
    {
        $player = new Player($name, self::INITIAL_POSITION);
        array_push($this->players, $player);

        Logger::newPlayer($player, $this->howManyPlayers());
    }

    public function howManyPlayers()
    {
        return count($this->players);
    }

    public function roll($roll)
    {
        $player = $this->getCurrentPlayer();
        Logger::currentPlayer($player, $roll);

        $player->gettingOutOfPenaltyBox($roll % 2 != 0);
        if ($player->isInPenaltyBox() && !$player->isGettingOutOfPenaltyBox()) {
            Logger::isNotGettingOutOfPenalty($player);

            return;
        }
        if ($player->isInPenaltyBox()) {
            Logger::isGettingOutOfPenalty($player);
        }

        $this->playTurn($roll);
    }

    public function askQuestion(Category $category): void
    {
        Logger::question($category->getQuestion());
    }

    public function currentCategory($position): Category
    {
        $categories = array_keys($this->categories);

        return $this->categories[$categories[($position % 4)]];
    }

    public function wasCorrectlyAnswered()
    {
        $isInPenaltyBox = $this->getCurrentPlayer()->isInPenaltyBox();
        if (!$isInPenaltyBox || $this->getCurrentPlayer()->isGettingOutOfPenaltyBox()) {
            $this->getCurrentPlayer()->winPurses();
            Logger::correctAnswer($this->getCurrentPlayer());
        }

        $this->nextPlayer();

        return $this->didPlayerWin();
    }

    public function wrongAnswer()
    {
        $player = $this->getCurrentPlayer();
        Logger::wrongAnswer($player);
        $player->goToPenaltyBox();

        return false;
    }

    public function didPlayerWin()
    {
        return !(self::MAX_VALUE_PLACES === $this->getCurrentPlayer()->getPurses());
    }

    public function getCurrentPlayer(): Player
    {
        return $this->players[$this->currentPlayer];
    }

    /**
     * @param $roll
     */
    private function playTurn($roll)
    {
        $player = $this->getCurrentPlayer();
        $player->move($roll);

        $currentCategory = $this->currentCategory($player->getPosition());
        Logger::move($player, $currentCategory);
        $this->askQuestion($currentCategory);
    }

    private function nextPlayer(): void
    {
        $this->currentPlayer++;
        if ($this->currentPlayer == $this->howManyPlayers()) {
            $this->currentPlayer = 0;
        }
    }
}
