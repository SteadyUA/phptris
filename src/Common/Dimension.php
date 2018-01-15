<?php
namespace Tet\Common;

class Dimension
{
    public $width = 0;
    public $height = 0;

    public function __construct($width = 0, $height = 0)
    {
        $this->width = $width;
        $this->height = $height;
    }
}
