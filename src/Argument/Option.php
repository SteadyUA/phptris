<?php
namespace Tet\Argument;

class Option extends AbstractOption
{
    private $default;
    private $argumentName;
    private $range;

    public function default(string $value): Option
    {
        $this->default = $value;

        return $this;
    }

    public function argument(string $argumentName): Option
    {
        $this->argumentName = $argumentName;

        return $this;
    }

    public function range(int $from, int $to): Option
    {
        $this->range = [$from, $to];

        return $this;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function getArgument()
    {
        return $this->argumentName;
    }

    public function getRange()
    {
        return $this->range;
    }
}
