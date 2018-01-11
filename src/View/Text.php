<?php
namespace Tet\View;

use Tet\Output\Output;
use Tet\View\Font\FontInterface;

class Text extends ViewNode
{
    protected $font;
    protected $text = '';

    public function __construct($posX, $posY, $width, $height, FontInterface $font, $text = '')
    {
        $this->font = $font;
        $this->text = $text;
        parent::__construct($posX, $posY, $width, $height);
    }

    public function setText($text)
    {
        $this->text = (string) $text;

        return $this;
    }

    public function render(Output $out)
    {
        $out->setContext($out->newContext());
        $pos = $this->getPosition();
        $size = $this->getSize();

        // clear
        $line = str_repeat(' ', $size->width);
        for ($y = 0; $y < $size->height; $y ++) {
            $out->cursor()->set($pos->x, $pos->y + $y);
            $out->write($line);
        }

        // out text
        $fontSize = $this->font->size();
        $textWidth = $fontSize->width * mb_strlen($this->text);
        $textHeight = $fontSize->height;
        $pos->x += round($size->width / 2 - $textWidth / 2);
        $pos->y += round($size->height / 2 - $textHeight / 2);
        $lines = $this->font->text($this->text);
        for ($h = 0; $h < $this->font->size()->height; $h ++) {
            $out->cursor()->set($pos->x, $pos->y + $h);
            $out->write($lines[$h]);
        }
    }
}
