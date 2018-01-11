<?php
namespace Tet\Game\Event;

use Tet\Game\Block\Block;

interface OnHoldBlockInterface extends ListenerInterface
{
    public function onHoldBlock(Block $block);
}
