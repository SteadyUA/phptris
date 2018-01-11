<?php
namespace Tet\Input;

class Input
{
    public function __construct()
    {
        stream_set_blocking(STDIN, false);
    }

    /**
     * @return KeyPress[]
     */
    public function read()
    {
        $keyMap = [
            'A' => 'up',
            'B' => 'down',
            'D' => 'left',
            'C' => 'right'
        ];
        $buffer = [];
        while (($char = fgetc(STDIN)) !== false) {
            $mask = 0;
            if (ord($char) == 27) {
                if (fgetc(STDIN) == '[') {
                    $char = fgetc(STDIN);
                    if ($char == '1' && fgetc(STDIN) == ';') {
                        $mask = (int) fgetc(STDIN);
                        $char = fgetc(STDIN);
                    }
                    $key = isset($keyMap[$char]) ? $keyMap[$char] : '';
                    $buffer[] = new KeyPress($key, $mask);
                    continue;
                }
            }
            $code = ord($char);
            if ($code < 32) {
                $key = $code;
            } else {
                $key = strtolower($char);
                if ($char != $key) {
                    $mask = 2;
                }
            }
            $buffer[] = new KeyPress($key, $mask);
        }
        return $buffer;
    }
}
