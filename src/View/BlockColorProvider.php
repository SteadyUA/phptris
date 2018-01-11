<?php
namespace Tet\View;

use Tet\Output\Context;
use Tet\Game\Block\Block;

class BlockColorProvider
{

    public function setColor(Context $context, int $type): Context
    {
        switch ($type) {
            case Block::TYPE_I:
                return $context->color($context::COLOR_CYAN);

            case Block::TYPE_O:
                return $context->color($context::COLOR_WHITE);

            case Block::TYPE_T:
                return $context->color($context::COLOR_PURPLE);

            case Block::TYPE_S:
                return $context->color($context::COLOR_GREEN);

            case Block::TYPE_Z:
                return $context->color($context::COLOR_RED);

            case Block::TYPE_J:
                return $context->color($context::COLOR_BLUE);

            case Block::TYPE_L:
                return $context->color($context::COLOR_YELLOW);
        }

        throw new \RuntimeException('Unknown type: ' . $type);
    }
}
