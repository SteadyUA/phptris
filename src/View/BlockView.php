<?php
namespace Tet\View;

use Tet\Game\Block\Block;
use Tet\Output\Output;

class BlockView extends ViewNode
{
    private $mino = '';
    private $opacityLevel = [
        1 => '██',
        2 => '▓▓',
        3 => '▒▒',
        4 => '░░',
        5 => '  '
    ];

    /**
     * @var BlockColorProvider
     */
    private $colorProvider;

    /**
     * @var Block
     */
    protected $block = null;

    public function __construct($x = 0, $y = 0, $width = 4, $height = 4)
    {
        $this->colorProvider = new BlockColorProvider();
        parent::__construct($x, $y, $width, $height);
    }

    protected function getBlockContext(Output $output)
    {
        $context = $output->newContext();
        $this->colorProvider
            ->setColor($context, $this->block->getType())
            ->light(true);
        return $context;
    }

    public function opacity($level)
    {
        $this->mino = $this->opacityLevel[$level];
    }

    public function setBlock(Block $block)
    {
        $this->block = $block;
    }

    /**
     * @param Output $output
     */
    public function render(Output $output)
    {
        if ($this->block === null) {
            return;
        }
        $pixels = $this->block->getMatrix();
        if (empty($pixels)) {
            return;
        }
        $output->setContext($this->getBlockContext($output));
        $position = $this->getPosition();
        $blockLocation = $this->block->location();
        foreach ($pixels as $pixel) {
            $x = $pixel[0] + $blockLocation->x;
            $y = $pixel[1] + $blockLocation->y;
            $output->cursor()->set($x * 2 + $position->x, $y + $position->y);
            $output->write($this->mino);
        }
    }
}
