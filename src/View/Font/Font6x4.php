<?php
namespace Tet\View\Font;

use Tet\Common\Dimension;

class Font6x4 implements FontInterface
{
    protected $size;

    public function __construct()
    {
        $this->size = new Dimension(6, 4);
    }

    protected $chars = [
        ' ' => [
            '      ',
            '      ',
            '      ',
            '      ',
            ],
        'A' => [
            ' ▄▄▄  ',
            '█   █ ',
            '█▀▀▀█ ',
            '▀   ▀ ',
        ],
        'E' => [
            '▄▄▄▄▄ ',
            '█▄▄▄  ',
            '█     ',
            '▀▀▀▀▀ ',
        ],
        'G' => [
            ' ▄▄▄▄ ',
            '█     ',
            '█  ▀█ ',
            ' ▀▀▀▀ ',
        ],
        'I' => [
            '  ▄   ',
            '  █   ',
            '  █   ',
            '  ▀   ',
        ],
        'M' => [
            '▄   ▄ ',
            '█▀▄▀█ ',
            '█   █ ',
            '▀   ▀ ',
        ],
        'N' => [
            '▄   ▄ ',
            '█▀▄ █ ',
            '█ ▀▄█ ',
            '▀   ▀ ',
        ],
        'O' => [
            ' ▄▄▄  ',
            '█   █ ',
            '█   █ ',
            ' ▀▀▀  ',
        ],
        'P' => [
            '▄▄▄▄  ',
            '█▄▄▄▀ ',
            '█     ',
            '▀     ',
        ],
        'R' => [
            '▄▄▄▄  ',
            '█▄▄▄▀ ',
            '█ ▀▄  ',
            '▀   ▀ ',
        ],
        'S' => [
            ' ▄▄▄▄ ',
            '▀▄▄▄  ',
            '    █ ',
            '▀▀▀▀  ',
        ],
        'U' => [
            '▄   ▄ ',
            '█   █ ',
            '█   █ ',
            ' ▀▀▀  ',
        ],
        'V' => [
            '▄   ▄ ',
            '█   █ ',
            ' █ █  ',
            '  ▀   ',
        ],
        'W' => [
            '▄   ▄ ',
            '█   █ ',
            '█▄▀▄█ ',
            '▀   ▀ ',
        ],
        'Y' => [
            '▄   ▄ ',
            '▀▄ ▄▀ ',
            '  █   ',
            '  ▀   ',
        ],
        '!' => [
            '  ▄   ',
            '  █   ',
            '  ▀   ',
            '  ▀   ',
        ],
    ];

    public function size(): Dimension
    {
        return $this->size;
    }

    public function text($text) : array
    {
        $result = ['', '', '', ''];
        for ($i = 0; $i < strlen($text); $i ++) {
            $char = $text{$i};
            $lines = isset($this->chars[$char]) ? $this->chars[$char] : $this->chars[' '];
            $result[0] .= $lines[0];
            $result[1] .= $lines[1];
            $result[2] .= $lines[2];
            $result[3] .= $lines[3];
        }
        return $result;
    }
}
