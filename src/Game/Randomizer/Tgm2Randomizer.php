<?php
namespace Tet\Game\Randomizer;

/**
 * Arika's Tetris The Grand Master 2 randomizer
 */
class Tgm2Randomizer implements RandomizerInterface
{
    private $history = [];
    private $variants = [];
    private $value = '';

    public function __construct(array $startSet, array $variants, array $startHistory)
    {
        $this->history = $startHistory;
        $this->variants = $variants;
        $this->value = $startSet[array_rand($startSet)];
        array_shift($this->history);
        array_push($this->history, $this->value);
    }

    public function value(): int
    {
        $nextValue = '';
        for ($i = 0; $i < 6; $i ++) {
            $nextValue = $this->variants[array_rand($this->variants)];
            if (!in_array($nextValue, $this->history)) {
                break;
            }
        }
        array_shift($this->history);
        array_push($this->history, $nextValue);

        $currentValue = $this->value;
        $this->value = $nextValue;

        return $currentValue;
    }
}
