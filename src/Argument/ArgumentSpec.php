<?php
namespace Tet\Argument;

class ArgumentSpec
{
    /**
     * @var Group[]
     */
    private $group = [];

    /**
     * @var Command[]
     */
    private $command = [];

    /**
     * @var Argument[]
     */
    private $argument = [];

    public function group(string $name, string $description): Group
    {
        $group = new Group($name, $description);
        $this->group[$name] = $group;

        return $group;
    }

    public function command(string $name, string $description): Command
    {
        $command = new Command($name, $description);
        $this->command[$name] = $command;

        return $command;
    }

    public function argument(string $name, string $description): Argument
    {
        $argument = new Argument($name, $description);
        $this->argument[$name] = $argument;

        return $argument;
    }

    /**
     * @return \ArrayIterator|Group[]
     */
    public function getGroupIterator()
    {
        return new \ArrayIterator($this->group);
    }

    /**
     * @return \ArrayIterator|Command[]
     */
    public function getCommandIterator()
    {
        return new \ArrayIterator($this->command);
    }

    /**
     * @return \ArrayIterator|Argument[]
     */
    public function getArgumentIterator()
    {
        return new \ArrayIterator($this->argument);
    }
}
