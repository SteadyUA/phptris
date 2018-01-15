<?php
namespace Tet\View;

use Tet\Output\Output;

class PlayFieldBorderView extends ViewNode
{
    protected $fillChar = ' ';

    public function __construct($posX, $posY, $width, $height)
    {
        parent::__construct($posX, $posY, $width, $height);
    }

    /**
     * @param Output $output
     */
    public function render(Output $output)
    {
        $position = $this->getPosition();
        $size = $this->getSize();
        $cursor = $output->cursor();
        for ($x = $position->x + 1; $x < $position->x + $size->width - 1; $x ++) {
            for ($y = $position->y; $y < $position->y + $size->height - 1; $y ++) {
                $cursor->set($x, $y);
                $output->write($this->fillChar);
            }
        }

        // draw border
        for ($x = 1; $x < $size->width - 1; $x ++) {
            $cursor->set($position->x + $x, $position->y + $size->height - 1);
            $output->write('─');
        }
        for ($y = 1; $y < $size->height - 1; $y ++) {
            $cursor->set($position->x, $position->y + $y);
            $output->write('│');
            $cursor->set($position->x + $size->width - 1, $position->y + $y);
            $output->write('│');
        }
        $cursor->set($position->x, $position->y);
        $output->write('╮');
        $cursor->set($position->x + $size->width - 1, $position->y);
        $output->write('╭');
        $cursor->set($position->x, $position->y + $size->height - 1);
        $output->write('╰');
        $cursor->set($position->x + $size->width - 1, $position->y + $size->height - 1);
        $output->write('╯');
    }
}
