<?php
namespace Tet\View;

use Tet\Game\Block\Block;
use Tet\Game\Movement;
use Tet\Game\PlayField\AbstractPlayField;
use Tet\Game\WallKick\NoWallKick;

class BlockShadowView extends BlockView
{
    private $movement;

    public function __construct(AbstractPlayField $field)
    {
        parent::__construct();
        $this->opacity(4);
        $this->movement = new Movement(new NoWallKick(), $field);
    }

    public function setBlock(Block $block)
    {
        $this->block = clone $block;
        do {
            $res = $this->movement->moveDown($this->block);
        } while ($res);
    }
}
