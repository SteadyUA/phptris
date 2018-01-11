<?php
namespace Tet\Game\Randomizer;

class Bag7Randomizer implements RandomizerInterface
{
    private $bag = [];
    private $allValues = [];

    public function __construct(array $startSet, array $allValues)
    {
        $this->allValues = $allValues;
        $this->bag[] = $startSet[array_rand($startSet)];
    }

    protected function fillBag()
    {
        $this->bag = $this->allValues;
        shuffle($this->bag);
    }

    public function value(): int
    {
        if (empty($this->bag)) {
            $this->fillBag();
        }

        return array_pop($this->bag);
    }
}
