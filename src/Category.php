<?php

namespace RefactorGame;

class Category
{
    public const POP = "Pop";
    public const SCIENCE = "Science";
    public const SPORTS = "Sports";
    public const ROCK = "Rock";
    public const NUMBER_OF_QUESTIONS = 50;

    private $questions = [];

    /** @var  string */
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
        foreach (range(0, 49) as $index) {
            $this->addQuestion(new Question($this->name . " Question " . $index));
        }
    }

    public function addQuestion(Question $question): void
    {
        array_push($this->questions, $question);
    }

    public function getQuestion(): Question
    {
        return array_shift($this->questions);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getQuestions():array
    {
        return $this->questions;
    }
}
