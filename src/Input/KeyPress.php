<?php
namespace Tet\Input;

class KeyPress
{
    private $key = '';
    private $isShift = false;
    private $isCtrl = false;
    private $isAlt = false;

    const SHIFT = 1;
    const ALT = 2;
    const CTRL = 4;

    public function __construct($key, $mask = 0)
    {
        $this->key = $key;
        if ($mask > 0) {
            $mask --;
            $this->isShift = $mask & self::SHIFT;
            $this->isCtrl = $mask & self::CTRL;
            $this->isAlt = $mask & self::ALT;
        }
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function isShift(): bool
    {
        return $this->isShift;
    }

    public function isCtrl(): bool
    {
        return $this->isCtrl;
    }

    public function isAlt(): bool
    {
        return $this->isAlt;
    }
}
