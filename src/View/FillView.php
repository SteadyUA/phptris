<?php
namespace Tet\View;

use Tet\Output\Output;

class FillView extends ViewNode
{
    private $fillChar = '';

    public function __construct(int $x = 0, int $y = 0, int $width = 0, int $height = 0, $fillChar = ' ')
    {
        $this->fillChar = $fillChar;
        parent::__construct($x, $y, $width, $height);
    }

    public function setFillChar($fillChar)
    {
        $this->fillChar = $fillChar;
    }

    public function render(Output $output)
    {
        $cursor = $output->cursor();
        $pos = $this->getPosition();
        $size = $this->getSize();
        for ($y = 0; $y < $size->height; $y ++) {
            for ($x = 0; $x < $size->width; $x ++) {
                $cursor->set($pos->x + $x, $pos->y + $y);
                $output->write($this->fillChar);
            }
        }

        parent::render($output);
    }
}
