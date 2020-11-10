<?php

namespace RefactorGame;

class Player
{
    private string $name;
    private bool $inPenaltyBox;
    private int $position;
    private int $purses;
    private bool $isGettingOutOfPenaltyBox;

    /**
     * @param $name
     * @param $position
     */
    public function __construct($name, $position)
    {
        $this->name = $name;
        $this->purses = 0;
        $this->inPenaltyBox = false;
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isInPenaltyBox(): bool
    {
        return $this->inPenaltyBox;
    }

    /**
     * @param bool $inPenaltyBox
     */
    public function goToPenaltyBox(): void
    {
        $this->inPenaltyBox = true;
    }

    /**
     * @param boolean $gettingOut
     */
    public function gettingOutOfPenaltyBox($gettingOut)
    {
        $this->isGettingOutOfPenaltyBox = $gettingOut;
    }


    /**
     * @return bool
     */
    public function isGettingOutOfPenaltyBox()
    {
        return $this->isGettingOutOfPenaltyBox;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return int
     */
    public function getPurses(): int
    {
        return $this->purses;
    }

    public function winPurses(): void
    {
        $this->purses++;
    }

    /**
     * @param $roll
     */
    public function move($roll)
    {
        $this->position = ($this->position + $roll) % Game::LENGTH_BOARD;
    }
}
