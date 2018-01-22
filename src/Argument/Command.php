<?php
namespace Tet\Argument;

class Command extends Argument implements \IteratorAggregate
{
    private $argument = [];

    public function optionalArg(string $param, string $argument): Command
    {
        $option = new Option($param, '');
        $option->argument($argument);
        $this->argument[] = $option;

        return $this;
    }

    public function requiredArg(string $param, string $argument): Command
    {
        $option = new Option($param, '', Option::REQUIRED);
        $option->argument($argument);
        $this->argument[] = $option;

        return $this;
    }

    public function optionalGroup(string $group): Command
    {
        $group = new OptionGroup($group, '', Option::OPTIONAL);
        $this->argument[] = $group;

        return $this;
    }

    public function requiredGroup(string $group): Command
    {
        $group = new OptionGroup($group, '', Option::REQUIRED);
        $this->argument[] = $group;

        return $this;
    }

    /**
     * @return \ArrayIterator|AbstractOption[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->argument);
    }
}
