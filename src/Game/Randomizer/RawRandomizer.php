<?php
namespace Tet\Game\Randomizer;

class RawRandomizer implements RandomizerInterface
{
    private $allValues = [];

    public function __construct(array $allValues)
    {
        $this->allValues = $allValues;
    }

    public function value(): int
    {
        return $this->allValues[array_rand($this->allValues)];
    }
}
