<?php
namespace Tet\Game\Spawn;

use Tet\Game\Block\Block;

class FollowSpawn extends CenterSpawn
{
    /**
     * @var Block
     */
    protected $previous;

    public function locateBlock(Block $block)
    {
        if ($this->previous) {
            $x = $this->previous->location()->x;
            if ($x < 0) {
                $x = 0;
            } elseif ($block->getWidth() + $x > $this->field->size()->width) {
                $x = $this->field->size()->width - $block->getWidth();
            }
            $block->location()->set($x, -1);
        } else {
            parent::locateBlock($block);
        }
        $this->previous = $block;
    }
}
