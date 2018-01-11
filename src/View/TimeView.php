<?php
namespace Tet\View;

use Tet\View\Font\Font1x1;

class TimeView extends Text
{
    protected $time = 0;

    public function __construct($posX, $posY, $width, $height)
    {
        parent::__construct($posX, $posY, $width, $height, new Font1x1);
    }

    public function setTime($time)
    {
        $minutes = intdiv($time, 60);
        $seconds = $time % 60;
        $text = str_pad($minutes, 2, '0', STR_PAD_LEFT);
        $text .= ':';
        $text .= str_pad($seconds, 2, '0', STR_PAD_LEFT);
        $this->setText($text);
    }
}
