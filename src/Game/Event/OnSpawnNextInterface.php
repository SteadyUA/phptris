<?php
namespace Tet\Game\Event;

use Tet\Game\Block\Block;
use Tet\Game\NextQueue;

interface OnSpawnNextInterface extends ListenerInterface
{
    public function onSpawnNext(Block $block, NextQueue $nextQueue);
}
