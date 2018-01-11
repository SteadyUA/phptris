<?php
namespace Tet\Common;

class Vector implements \ArrayAccess, \Countable, \IteratorAggregate
{
    protected $defValue;
    protected $elements = [];
    protected $offset = 0;

    public function __construct($defValue = null)
    {
        $this->defValue = $defValue;
        $this->elements[0] = $this->getDefValue();
    }

    public function offsetExists($offset)
    {
        $offset = (int) $offset;
        return $offset <= $this->offset;
    }

    public function offsetGet($offset)
    {
        if (false == $this->offsetExists($offset)) {
            $this->offsetSet($offset, $this->getDefValue());
        }
        return $this->elements[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $offset = (int) $offset;
        if ($offset > $this->offset) {
            $delta = $offset - $this->offset;
            while ($delta --) {
                $this->elements[] = $this->getDefValue();
            }
            $this->offset = $offset;
        }
        $this->elements[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            $this->elements[$offset] = $this->getDefValue();
        }
    }

    public function count()
    {
        return $this->offset + 1;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    public function getDefValue()
    {
        return is_object($this->defValue) ? clone $this->defValue : $this->defValue;
    }
}
