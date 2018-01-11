<?php
namespace Tet\Game\Randomizer;

class StubRandomizer implements RandomizerInterface
{
    private $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function value(): int
    {
        return $this->type;
    }
}
