<?php
namespace Tet\Game\Block;

class SrsFactory extends AbstractBlockFactory
{
    protected $matrix = [
        Block::TYPE_I => [
            0 => [[0, 1], [1, 1], [2, 1], [3, 1]],
            1 => [[2, 0], [2, 1], [2, 2], [2, 3]],
            2 => [[0, 2], [1, 2], [2, 2], [3, 2]],
            3 => [[1, 0], [1, 1], [1, 2], [1, 3]],
        ],
        Block::TYPE_J => [
            0 => [[0, 0], [0, 1], [1, 1], [2, 1]],
            1 => [[1, 0], [2, 0], [1, 1], [1, 2]],
            2 => [[0, 1], [1, 1], [2, 1], [2, 2]],
            3 => [[1, 0], [1, 1], [0, 2], [1, 2]],
        ],
        Block::TYPE_L => [
            0 => [[2, 0], [0, 1], [1, 1], [2, 1]],
            1 => [[1, 0], [1, 1], [1, 2], [2, 2]],
            2 => [[0, 1], [1, 1], [2, 1], [0, 2]],
            3 => [[0, 0], [1, 0], [1, 1], [1, 2]],
        ],
        Block::TYPE_O => [
            0 => [[0, 0], [1, 0], [0, 1], [1, 1]],
            1 => [[0, 0], [1, 0], [0, 1], [1, 1]],
            2 => [[0, 0], [1, 0], [0, 1], [1, 1]],
            3 => [[0, 0], [1, 0], [0, 1], [1, 1]],
        ],
        Block::TYPE_S => [
            0 => [[1, 0], [2, 0], [0, 1], [1, 1]],
            1 => [[1, 0], [1, 1], [2, 1], [2, 2]],
            2 => [[1, 1], [2, 1], [0, 2], [1, 2]],
            3 => [[0, 0], [0, 1], [1, 1], [1, 2]],
        ],
        Block::TYPE_T => [
            0 => [[1, 0], [0, 1], [1, 1], [2, 1]],
            1 => [[1, 0], [1, 1], [2, 1], [1, 2]],
            2 => [[0, 1], [1, 1], [2, 1], [1, 2]],
            3 => [[1, 0], [0, 1], [1, 1], [1, 2]],
        ],
        Block::TYPE_Z => [
            0 => [[0, 0], [1, 0], [1, 1], [2, 1]],
            1 => [[2, 0], [1, 1], [2, 1], [1, 2]],
            2 => [[0, 1], [1, 1], [1, 2], [2, 2]],
            3 => [[1, 0], [0, 1], [1, 1], [0, 2]],
        ],
    ];

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
