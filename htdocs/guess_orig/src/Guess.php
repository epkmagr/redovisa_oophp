<?php
/**
 * Guess my number, a class supporting the game through GET, POST and SESSION.
 */
class Guess
{
    /**
    * @var int $number   The current secret number.
    * @var int $tries    Number of tries a guess has been made.
    */
    private $number;
    private $tries;

    /**
    * Constructor to initiate the object with current game settings,
    * if available. Randomize the current number if no value is sent in.
    *
    * @param int $number The current secret number, default -1 to initiate
    *                    the number from start.
    * @param int $tries  Number of tries a guess has been made,
    *                    default 6.
    */
    public function __construct(int $number = -1, int $tries = 6)
    {
        $this->number = $number;
        if ($number === -1) {
            $this->number = mt_rand(1, 100);
        }
        $this->tries = $tries;
    }

    /**
    * Destroy an object of Guess. Unnessesary method.
    */
    public function __destruct()
    {
     // echo __METHOD__;
    }

    /**
     * Randomize the secret number between 1 and 100 to initiate a new game.
     *
     * @return void
     */
    public function random() : void
    {
        $this->number = mt_rand(1, 100);
    }

    /**
     * Get number of tries left.
     *
     * @return int as number of tries made.
     */
    public function tries() : int
    {
        return $this->tries;
    }

    /**
     * Get the secret number.
     *
     * @return int as the secret number.
     */
    public function number() : int
    {
        return $this->number;
    }

    /**
     * Make a guess, decrease remaining guesses and return a string stating
     * if the guess was correct, too low or to high or if no guesses remains.
     *
     * @throws GuessException when guessed number is out of bounds.
     * @throws GameoverException when number of tries is out of bounds.
     *
     * @return string to show the status of the guess made.
     */
    public function makeGuess($number)
    {
        $resultStr = "correct";

        $this->tries -= 1; // reduce the number of tries
        if ($number < 1 || $number > 100) {
            throw new GuessException(" faulty. The number should be between 1 and 100, 1 and 100 included.");
        }
        if ($number > $this->number) { // the guessed number is too high
            $resultStr = "too high";
        } elseif ($number < $this->number) { // the guessed number is too low
            $resultStr = "too low";
        }

        return $resultStr;
    }
}
