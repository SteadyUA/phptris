<?php
namespace Tet\Game\PlayField;

use Tet\Common\Dimension;
use Tet\Game\Block\Block;

abstract class AbstractPlayField
{
    protected $size;

    /**
     * @var array[]
     */
    protected $field = [];
    protected $deletedLines = [];

    public function __construct($width, $height)
    {
        $this->size = new Dimension($width, $height);
        for ($y = 0; $y < $height; $y++) {
            $row = [];
            for ($x = 0; $x < $width; $x++) {
                $row[$x] = null;
            }
            $this->field[$y] = $row;
        }
    }

    public function size()
    {
        return $this->size;
    }

    public function checkField(): int
    {
        // check full lines
        $deletedLines = [];
        for ($y = 0; $y < $this->size->height; $y++) {
            $hasEmpty = false;
            for ($x = 0; $x < $this->size->width; $x++) {
                $pixel = $this->field[$y][$x];
                if ($pixel == null) {
                    $hasEmpty = true;
                }
            }
            if ($hasEmpty == false) {
                $deletedLines[] = $y;
            }
        }
        $this->deletedLines = $deletedLines;
        return count($deletedLines);
    }

    public function getDeletedLines()
    {
        return $this->deletedLines;
    }

    abstract public function clearLines();

    abstract public function placeOnField(Block $block);

    abstract public function get($x, $y);
}
