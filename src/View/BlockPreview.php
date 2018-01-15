<?php
namespace Tet\View;

use Tet\Output\Output;
use Tet\Game\Block\Block;

class BlockPreview extends BlockView
{
    protected $mino = '██';
    protected $minoUp = '▀▀';
    protected $minoDown = '▄▄';

    protected $prevHeight = 0;
    protected $blockWidth = 0;
    protected $blockHeight = 0;
    protected $blockOffsetLeft = 0;
    protected $set = [];

    public function __construct($width, $height = 3)
    {
        parent::__construct(0, 0, $width, $height);
    }

    public function setBlock(Block $block)
    {
        $this->block = $block;
        $this->prevHeight = $this->blockHeight + 1;
        $this->set = [];
        $minRow = $minCol = $this->block->getWidth();
        $maxRow = $maxCol = 0;
        $pixels = $this->block->getMatrix();
        foreach ($pixels as $pixel) {
            if ($pixel[1] > $maxRow) {
                $maxRow = $pixel[1];
            } elseif ($pixel[1] < $minRow) {
                $minRow = $pixel[1];
            }
            if ($pixel[0] > $maxCol) {
                $maxCol = $pixel[0];
            } elseif ($pixel[0] < $minCol) {
                $minCol = $pixel[0];
            }
        }
        foreach ($pixels as $pixel) {
            $this->set[$pixel[1] - $minRow][$pixel[0] - $minCol] = 1;
        }
        $this->blockHeight = $maxRow - $minRow + 1;
        $this->blockWidth = $maxCol - $minCol + 1;
        $this->blockOffsetLeft = $minCol;
    }

    /**
     * @param Output $output
     */
    public function render(Output $output)
    {
        if (empty($this->set)) {
            return;
        }

        $position = $this->getPosition();
        $size = $this->getSize();
        $text = str_repeat(' ', $size->width);
        for ($i = 0; $i < $this->prevHeight; $i ++) {
            $output->cursor()->set($position->x, $position->y + $i);
            $output->write($text);
        }

        $output->setContext($this->getBlockContext($output));

        $textWidth = $this->blockWidth * 2;
        if ($textWidth < $size->width) {
            $position->x += floor(($size->width - $textWidth) / 2);
        }
        if ($this->blockHeight < $size->height) {
            $position->y += floor(($size->height - $this->blockHeight) / 2);
        }

        if ($this->blockHeight == 1) {
            foreach ($this->set as $y => $line) {
                foreach ($line as $x => $isset) {
                    $output->cursor()->set($x * 2 + $position->x, $y + $position->y);
                    $output->write($this->mino);
                }
            }
        } else {
            foreach ($this->set as $y => $line) {
                foreach ($line as $x => $isset) {
                    $output->cursor()->set($x * 2 + $position->x, $y + $position->y);
                    if (isset($this->set[$y - 1][$x])) {
                        $output->write($this->mino);
                    } else {
                        $output->write($this->minoDown);
                    }
                    $output->cursor()->set($x * 2 + $position->x, $y + $position->y + 1);
                    $output->write($this->minoUp);
                }
            }
        }
    }
}
