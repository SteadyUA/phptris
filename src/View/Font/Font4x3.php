<?php
namespace Tet\View\Font;

use Tet\Common\Dimension;

class Font4x3 implements FontInterface
{
    /*
   █ ▀▀█ ▀▀█ █ █ █▀▀ █▀▀ ▀▀█ █▀█ █▀█ █▀█
   █ █▀▀  ▀█ ▀▀█ ▀▀█ █▀█   █ █▀█ ▀▀█ █ █
   ▀ ▀▀▀ ▀▀▀   ▀ ▀▀▀ ▀▀▀   ▀ ▀▀▀ ▀▀▀ ▀▀▀
    */

    protected $size;

    public function __construct()
    {
        $this->size = new Dimension(4, 3);
    }

    protected $chars = [
        '1' => [
            '  ▄▄',
            '   █',
            '   █',
            ],
        '2' => [
            ' ▄▄▄',
            ' ▄▄█',
            ' █▄▄',
            ],
        '3' => [
            ' ▄▄▄',
            '  ▄█',
            ' ▄▄█',
            ],
        '4' => [
            ' ▄ ▄',
            ' █▄█',
            '   █',
            ],
        '5' => [
            ' ▄▄▄',
            ' █▄▄',
            ' ▄▄█',
            ],
        '6' => [
            ' ▄▄▄',
            ' █▄▄',
            ' █▄█',
            ],
        '7' => [
            ' ▄▄▄',
            '   █',
            '   █',
            ],
        '8' => [
            ' ▄▄▄',
            ' █▄█',
            ' █▄█',
            ],
        '9' => [
            ' ▄▄▄',
            ' █▄█',
            ' ▄▄█',
            ],
        '0' => [
            ' ▄▄▄',
            ' █ █',
            ' █▄█',
            ],
        ' ' => [
            '    ',
            '    ',
            '    ',
            ],
    ];

    public function size(): Dimension
    {
        return $this->size;
    }

    public function text($text) : array
    {
        $result = ['', '' , ''];
        for ($i = 0; $i < strlen($text); $i ++) {
            $char = $text{$i};
            $lines = isset($this->chars[$char]) ? $this->chars[$char] : $this->chars[' '];
            $result[0] .= $lines[0];
            $result[1] .= $lines[1];
            $result[2] .= $lines[2];
        }
        return $result;
    }
}
