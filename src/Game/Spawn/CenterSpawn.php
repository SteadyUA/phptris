<?php
namespace Tet\Game\Spawn;

use Tet\Game\PlayField\AbstractPlayField;
use Tet\Game\Block\Block;

class CenterSpawn implements SpawnInterface
{
    protected $field;

    public function __construct(AbstractPlayField $field)
    {
        $this->field = $field;
    }

    public function locateBlock(Block $block)
    {
        $center = floor(($this->field->size()->width - $block->getWidth()) / 2);
        $block->location()->set($center, -1);
    }
}
