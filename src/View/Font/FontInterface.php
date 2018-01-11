<?php
namespace Tet\View\Font;

use Tet\Common\Dimension;

interface FontInterface
{
    public function size() : Dimension;
    public function text($text) : array;
}