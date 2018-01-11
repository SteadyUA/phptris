<?php
namespace Tet\Game\PlayField;

use Tet\Game\Block\Block;
use SplObjectStorage;

class CascadePlayField extends AbstractPlayField
{
    /**
     * @var SplObjectStorage
     */
    protected $polymino;

    /**
     * @var Polymino[][]
     */
    protected $field;

    public function __construct($width, $height)
    {
        parent::__construct($width, $height);
        $this->polymino = new SplObjectStorage();
    }

    public function setAt($x, $y, $value)
    {
        $this->field[$y][$x] = $value;
    }

    public function get($x, $y)
    {
        if (!isset($this->field[$y][$x])) {
            return null;
        }
        return $this->field[$y][$x]->getType();
    }

    public function clearLines()
    {
        /**
         * @var Polymino $polimino
         */

        // split polyminos
        foreach ($this->deletedLines as $y) {
            $toSplit = new SplObjectStorage();
            for ($x = 0; $x < $this->size->width; $x ++) {
                $polimino = $this->field[$y][$x];
                if (!$toSplit->contains($polimino)) {
                    $toSplit->attach($polimino);
                }
            }
            foreach ($toSplit as $polimino) {
                if ($bottom = $polimino->split($y, $this)) {
                    $this->polymino->attach($bottom);
                }
                if ($polimino->isEmpty()) {
                    $this->polymino->detach($polimino);
                }
            }
        }
        $this->deletedLines = [];

        // do while one or more polimino drop
        do {
            // check each polimino for drop
            $hasDrop = false;
            foreach ($this->polymino as $polimino) {
                $moved = $polimino->down($this);
                if ($moved > 0) {
                    $hasDrop = true;
                }
            }
        } while ($hasDrop);
    }

    public function placeOnField(Block $block)
    {
        $polymino = new Polymino($block->getType(), $block->getMatrix(), $block->location());
        $location = $block->location();
        foreach ($block->getMatrix() as $point) {
            $x = $location->x + $point[0];
            $y = $location->y + $point[1];
            $this->setAt($x, $y, $polymino);
        }
        $this->polymino->attach($polymino);
    }
}
