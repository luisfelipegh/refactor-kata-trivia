<?php

namespace RefactorGame;

class Question
{
    private string $text;

    public function __construct($text)
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
