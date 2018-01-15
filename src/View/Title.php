<?php
namespace Tet\View;

use Tet\Output\Output;

class Title extends ViewNode
{
    protected $name = '';

    public function __construct($name, $x, $y, $width)
    {
        $this->name = $name;
        parent::__construct($x, $y, $width, 1);
    }

    public function render(Output $output)
    {
        $output->cursor()->copy($this->getPosition());
        $size = $this->getSize();
        $text = ' ' . $this->name . ' ';
        $padLen = $size->width - mb_strlen($text);
        if ($padLen < 0) {
            $output->write(mb_substr($text, 0, $size->width));
            return;
        }
        $repeatTimes = $padLen / 2;
        $text = str_repeat('─', floor($repeatTimes)) . $text . str_repeat('─', ceil($repeatTimes));
        $output->write($text);
    }
}
