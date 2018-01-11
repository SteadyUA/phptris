<?php
namespace Tet\Game\Spawn;

use Tet\Game\Block\Block;

interface SpawnInterface
{
    public function locateBlock(Block $block);
}
