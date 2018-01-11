<?php
namespace Tet\Game\PlayField;

use Tet\Game\Block\Block;

class NativePlayField extends AbstractPlayField
{
    public function clearLines()
    {
        foreach ($this->deletedLines as $y) {
            if ($y == 0) { // first line
                continue;
            }
            for ($x = 0; $x < $this->size->width; $x++) {
                $posY = $y;
                while ($posY) {
                    $this->field[$posY][$x] = $this->field[$posY - 1][$x];
                    $posY --;
                }
            }
        }
        $this->deletedLines = [];
    }

    public function placeOnField(Block $block)
    {
        $matrix = $block->getMatrix();
        $location = $block->location();
        foreach ($matrix as $point) {
            $x = $location->x + $point[0];
            $y = $location->y + $point[1];
            $this->field[$y][$x] = $block->getType();
        }
    }

    public function get($x, $y)
    {
        if (!isset($this->field[$y][$x])) {
            return null;
        }
        return $this->field[$y][$x];
    }

    public function set($x, $y, $type)
    {
        $this->field[$y][$x] = $type;
    }

}
