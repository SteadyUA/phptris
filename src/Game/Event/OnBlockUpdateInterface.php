<?php
namespace Tet\Game\Event;

use Tet\Game\Block\Block;

interface OnBlockUpdateInterface extends ListenerInterface
{
    public function onBlockUpdate(Block $block);
}
