<?php
namespace Tet\Game\Block;

abstract class AbstractBlockFactory
{
    /**
     * @var Block[]
     */
    protected $instance = [];

    protected $matrix = [];

    /**
     * @param int $type
     * @return Block
     * @throws \Exception
     */
    public function get(int $type) : Block
    {
        if (!isset($this->instance[$type])) {
            $this->makeInstance($type);
        }

        return clone $this->instance[$type];
    }

    /**
     * @param int $type
     * @throws \Exception
     */
    private function makeInstance(int $type)
    {
        if (!isset($this->matrix[$type])) {
            throw new \Exception('Unknown type');
        }

        $this->instance[$type] = new Block($this->matrix[$type], $type);
    }
}
