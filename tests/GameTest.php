<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use RefactorGame\Category;
use RefactorGame\Game;

class GameTest extends TestCase
{
    /** @var Game */
    private $game;

    protected function setUp(): void
    {
        parent::setUp();

        $this->game = new Game();
    }

    public function testIsCreatedAllQuestions()
    {
        $this->assertEquals(50, count($this->game->categories[Category::POP]->getQuestions()));
        $this->assertEquals(50, count($this->game->categories[Category::SCIENCE]->getQuestions()));
        $this->assertEquals(50, count($this->game->categories[Category::SPORTS]->getQuestions()));
        $this->assertEquals(50, count($this->game->categories[Category::ROCK]->getQuestions()));
    }

    public function testTheGameIsNotPayableWithoutPlayers()
    {
        $this->assertFalse($this->game->isPlayable());
    }

    public function testTheGameIsNotPayableWithOnePlayers()
    {
        $this->game->addPlayer('first player');

        $this->assertFalse($this->game->isPlayable());
    }

    public function testTheGameIsPayable()
    {
        $this->game->addPlayer('first player');
        $this->game->addPlayer('second player');

        $this->assertTrue($this->game->isPlayable());
    }

    public function testThePlayerIsAddedSuccessfully()
    {
        $playerName = 'Chet';

        ob_start();
        $this->game->addPlayer($playerName);

        $this->assertEquals(ob_get_clean(), "{$playerName} was added\nThey are player number 1\n");
    }

    public function testAssertPlayersIsPlaying()
    {
        $this->game->addPlayer('first player');
        $this->game->addPlayer('second player');

        $quantity = $this->game->howManyPlayers();

        $this->assertEquals(2, $quantity);
    }

    /**
     * @dataProvider providerEnRollPlayers
     * @param mixed $roll
     * @param mixed $category
     */
    public function testEnroll($roll, $category)
    {
        $namePlayer ='first player' ;
        $this->game->addPlayer($namePlayer);

        ob_clean();
        ob_start();
        $this->game->roll($roll);

        $this->assertEquals(
            $namePlayer . " is the current player\nThey have rolled a " . $roll . "\n" . $namePlayer . "'s new location is " . $roll . "\nThe category is " . $category . "\n" . $category . " Question 0\n",
            ob_get_clean(),
        );
    }

    public function testEnrollHigher11()
    {
        $namePlayer ='first player' ;
        $this->game->addPlayer($namePlayer);

        ob_clean();
        ob_start();
        $this->game->roll(17);

        $this->assertStringContainsString("new location is " . (17-12), ob_get_clean());
    }

    /**
     * @dataProvider providerPairEnRollPlayers
     * @param mixed $roll
     */
    public function testEnrollWithPenaltyBox($roll)
    {
        $namePlayer ='first player' ;
        $this->game->addPlayer($namePlayer);

        $this->game->roll($roll);
        $this->game->wrongAnswer();
        ob_clean();
        ob_start();
        $this->game->roll($roll);

        $this->assertStringContainsString(
            $namePlayer . " is the current player\nThey have rolled a " . $roll . "\n". $namePlayer . " is not getting out of the penalty box\n",
            ob_get_clean()
        );
    }

    /**
     * @dataProvider providerOddEnRollPlayers
     * @param mixed $roll
     */
    public function testEnrollWithNotPenaltyBox($roll)
    {
        $namePlayer ='first player' ;
        $this->game->addPlayer($namePlayer);

        $this->game->roll($roll);
        $this->game->wrongAnswer();
        ob_clean();
        ob_start();
        $this->game->roll($roll);

        $this->assertStringContainsString(
            $namePlayer . " is the current player\nThey have rolled a " . $roll . "\n". $namePlayer . " is getting out of the penalty box\n",
            ob_get_clean()
        );
    }

    /**
     * @dataProvider providerEnRollPlayers
     * @param mixed $roll
     * @param mixed $category
     */
    public function testCurrentCategory($roll, $category)
    {
        $namePlayer ='first player' ;
        $this->game->addPlayer($namePlayer);

        $this->game->roll($roll);

        $this->assertEquals($category, $this->game->currentCategory($this->game->getCurrentPlayer()->getPosition())->getName());
    }

    public function testWasCorrectAnswer()
    {
        $namePlayer ='first player' ;
        $this->game->addPlayer($namePlayer);
        ob_clean();
        ob_start();

        $this->game->wasCorrectlyAnswered();

        $this->assertEquals("Answer was correct!!!!\n" . $namePlayer . " now has 1 Gold Coins.\n", ob_get_clean());
    }

    public function testWasCorrectAnswerAndWinnerPlayer()
    {
        $namePlayer ='first player' ;
        $this->game->addPlayer($namePlayer);

        $this->game->roll(1);
        $this->game->wrongAnswer();
        $this->game->roll(1);
        ob_clean();
        ob_start();
        $this->game->wasCorrectlyAnswered();
        $this->assertStringContainsString("Answer was correct!!!!", ob_get_clean());
    }

    public function testWasCorrectAnswerAndWinnerPlayerAndCurrentPlayerSum()
    {
        $namePlayer ='first player' ;
        $this->game->addPlayer($namePlayer);
        $this->game->wrongAnswer();
        $this->game->roll(2);

        $this->game->wasCorrectlyAnswered();

        $this->assertEquals(0, $this->game->currentPlayer);
        //$this->assertStringContainsString("Answer was corrent!!!!", ob_get_clean());
    }

    /**
     * @dataProvider providerEnRollPlayers
     * @param mixed $roll
     * @param mixed $category
     */
    public function testAskQuestionsReturnCorrectlyCategory($roll, $category)
    {
        $namePlayer ='first player' ;
        $this->game->addPlayer($namePlayer);

        $this->game->roll($roll);

        ob_clean();
        ob_start();
        $this->game->askQuestion($this->game->currentCategory($this->game->getCurrentPlayer()->getPosition()));

        $this->assertEquals($category . " Question 1\n", ob_get_clean());
    }

    public function testWasWrongAnswer()
    {
        $namePlayer ='first player' ;
        $this->game->addPlayer($namePlayer);
        ob_clean();
        ob_start();

        $this->game->wrongAnswer();

        $this->assertEquals("Question was incorrectly answered\n" . $namePlayer . " was sent to the penalty box\n", ob_get_clean());
    }

    public function providerEnRollPlayers(): array
    {
        return [
            'roll 0' => [0,'Pop'],
            'roll 1' => [1,'Science'],
            'roll 2' => [2,'Sports'],
            'roll 3' => [3,'Rock'],
            'roll 4' => [4,'Pop'],
            'roll 5' => [5,'Science'],
            'roll 6' => [6,'Sports'],
            'roll 7' => [7,'Rock'],
            'roll 8' => [8,'Pop'],
            'roll 9' => [9,'Science'],
            'roll 10' => [10,'Sports'],
        ];
    }

    public function providerPairEnRollPlayers(): array
    {
        return [
            'roll 2' => [2],
            'roll 4' => [4],
            'roll 6' => [6],
            'roll 8' => [8],
            'roll 10' => [10],
        ];
    }

    public function providerOddEnRollPlayers(): array
    {
        return [
            'roll 1' => [1],
            'roll 3' => [3],
            'roll 5' => [5],
            'roll 7' => [7],
            'roll 9' => [9],
        ];
    }
}
