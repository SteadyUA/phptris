<?php
namespace Tet\View;

class WindowView extends ViewNode
{
    /**
     * @var ViewNode
     */
    private $content;

    public function __construct(int $posX = 0, int $posY = 0, int $width = 0, int $height = 0)
    {
        parent::__construct($posX, $posY, $width, $height);

        $innerWidth = $width - 2;
        $innerHeight = $height - 2;
        $this->content = new ViewNode(1, 1, $innerWidth, $innerHeight);

        parent::addChild(new FillView(0, 0, 1, 1, '╭'));
        parent::addChild(new FillView(1, 0, $innerWidth, 1, '─'));
        parent::addChild(new FillView($width - 1, 0, 1, 1, '╮'));

        parent::addChild(new FillView(0, 1, 1, $height - 2, '│'));
        parent::addChild($this->content);
        parent::addChild(new FillView($width - 1, 1, 1, $height - 2, '│'));

        parent::addChild(new FillView(0, $height - 1, 1, 1, '╰'));
        parent::addChild(new FillView(1, $height - 1, $innerWidth, 1, '─'));
        parent::addChild(new FillView($width - 1, $height - 1, 1, 1, '╯'));
    }

    public function addChild(ViewNode $view)
    {
        $this->content->addChild($view);
    }
}
