<?php
namespace Tet\Output;

class Context
{
    const COLOR_BLACK = 0;
    const COLOR_RED = 1;
    const COLOR_GREEN = 2;
    const COLOR_YELLOW = 3;
    const COLOR_BLUE = 4;
    const COLOR_PURPLE = 5;
    const COLOR_CYAN = 6;
    const COLOR_WHITE = 7;

    const COLOR_DEF = 9;

    protected $isLight = false;
    protected $color = self::COLOR_DEF;
    protected $background = self::COLOR_DEF;

    public function light($value = true)
    {
        $this->isLight = $value;

        return $this;
    }

    public function isEqual(Context $with)
    {
        return $this->isLight == $with->isLight
            && $this->color == $with->color
            && $this->background == $with->background;
    }

    public function background(int $color)
    {
        $this->background = $color;

        return $this;
    }

    public function color(int $color)
    {
        $this->color = $color;

        return $this;
    }

    public function toString()
    {
        $params = $this->isLight ? '1' : '22';
        $params .= ';3' . $this->color;
        $params .= ';4' . $this->background;

        return "\033[" . $params . 'm';
    }
}
