<?php
namespace Tet\Argument;

class OptionOneOf extends AbstractOption implements \IteratorAggregate
{
    /**
     * @var Argument[]
     */
    private $value = [];
    private $default;

    public function value(string $value, string $description): OptionOneOf
    {
        $this->value[$value] = new Argument($value, $description);

        return $this;
    }

    public function default(string $value): OptionOneOf
    {
        $this->default = $value;

        return $this;
    }

    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @return \ArrayIterator|Argument[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->value);
    }
}
