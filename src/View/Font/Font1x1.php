<?php
namespace Tet\View\Font;

use Tet\Common\Dimension;

class Font1x1 implements FontInterface
{
    public function size(): Dimension
    {
        return new Dimension(1, 1);
    }

    public function text($text) : array
    {
        return [$text];
    }
}
