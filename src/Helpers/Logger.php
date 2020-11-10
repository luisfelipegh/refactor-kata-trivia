<?php

namespace RefactorGame\Helpers;

use RefactorGame\Category;
use RefactorGame\Player;
use RefactorGame\Question;

class Logger
{
    public static function newPlayer(Player $player, $countPlayers): void
    {
        self::message($player->getName() . " was added");
        self::message("They are player number " . $countPlayers);
    }

    public static function currentPlayer(Player $player, $roll): void
    {
        self::message($player->getName() . " is the current player");
        self::message("They have rolled a " . $roll);
    }

    public static function correctAnswer(Player $player): void
    {
        self::message("Answer was correct!!!!");
        self::message($player->getName()
        . " now has "
        . $player->getPurses()
        . " Gold Coins.");
    }

    public static function wrongAnswer(Player $player): void
    {
        self::message("Question was incorrectly answered");
        self::message($player->getName() . " was sent to the penalty box");
    }

    public static function isGettingOutOfPenalty(Player $player)
    {
        self::message($player->getName(). " is getting out of the penalty box");
    }

    public static function isNotGettingOutOfPenalty(Player $player)
    {
        self::message($player->getName() . " is not getting out of the penalty box");
    }

    public static function move(Player $player, Category $category)
    {
        self::message($player->getName() . "'s new location is " . $player->getPosition());
        self::message("The category is " . $category->getName());
    }

    public static function question(Question $question)
    {
        self::message($question->getText());
    }

    public static function message(string $message): void
    {
        echo $message."\n";
    }
}
