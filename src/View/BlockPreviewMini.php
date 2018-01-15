<?php
namespace Tet\View;

use Tet\Output\Output;
use Tet\Game\Block\Block;

class BlockPreviewMini extends BlockView
{
    private $minoUp = '▀';
    private $minoDown = '▄';
    private $prevWidth = 0;

    public function __construct()
    {
        parent::__construct(0, 0, 4, 2);
    }

    public function setBlock(Block $block)
    {
        $this->prevWidth = $this->size->width;
        $this->block = $block;
        $this->size->width = $block->getWidth();
    }

    /**
     * @param Output $output
     */
    public function render(Output $output)
    {
        $pixels = $this->block->getMatrix();
        if (empty($pixels)) {
            return;
        }

        $position = $this->getPosition();
        $text = str_repeat(' ', $this->prevWidth);
        $output->cursor()->set($position->x, $position->y);
        $output->write($text);
        $output->cursor()->move(0, 1);
        $output->write($text);

        $output->setContext($this->getBlockContext($output));

        $set = [];
        foreach ($pixels as $pixel) {
            $set[$pixel[1]][$pixel[0]] = 1;
        }
        $text1 = '';
        $text2 = '';
        for ($x = 0; $x < $this->block->getWidth(); $x ++) {
            $up = isset($set[0]) ? isset($set[0][$x]) : isset($set[1][$x]);
            $down = isset($set[0]) ? isset($set[1][$x]): isset($set[2][$x]);
            if ($up) {
                $text1 .= $this->minoDown;
            } else {
                $text1 .= ' ';
            }
            if ($down) {
                $text2 .= $this->minoUp;
            } else {
                $text2 .= ' ';
            }
        }
        $output->cursor()->copy($this->getPosition());
        $output->write($text1);
        $output->cursor()->move(0, 1);
        $output->write($text2);
    }
}
