<?php
namespace Tet\Game\Event;

use Tet\Game\Block\Block;

interface OnBlockLockInterface extends ListenerInterface
{
    public function onBlockLock(Block $block);
}
