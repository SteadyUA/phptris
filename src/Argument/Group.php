<?php
namespace Tet\Argument;

class Group extends Argument implements \IteratorAggregate
{
    /**
     * @var AbstractOption[]
     */
    private $option = [];

    public function optionOneOf(string $param, string $description): OptionOneOf
    {
        $option = new OptionOneOf($param, $description);
        $this->option[] = $option;

        return $option;
    }

    public function option(string $param, string $description): Option
    {
        $option = new Option($param, $description);
        $this->option[] = $option;

        return $option;
    }

    /**
     * @return \ArrayIterator|AbstractOption[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->option);
    }
}
