<?php
namespace Tet\View;

use Tet\Common\Dimension;
use Tet\Common\Location;
use Tet\Output\Output;

class ViewNode implements ViewInterface
{
    /**
     * @var ViewNode
     */
    protected $prev = null;

    /**
     * @var ViewNode
     */
    protected $last;

    /**
     * @var ViewNode[]
     */
    protected $child = [];

    protected $size;
    protected $position;
    protected $offsetPosition;

    protected $hidden = false;

    public function __construct($x = 0, $y = 0, $width = 0, $height = 0)
    {
        $this->size = new Dimension($width, $height);
        $this->position = new Location($x, $y);
        $this->offsetPosition = new Location($x, $y);
    }

    public function addChild(ViewNode $view)
    {
        $this->child[] = $view;
        $view->prev = $this->last;
        $this->last = $view;
        $this->setChildOffset($view);
    }

    public function hide()
    {
        $this->hidden = true;
    }

    public function show()
    {
        $this->hidden = false;
    }

    protected function setChildOffset(ViewNode $view)
    {
        $view->offsetPosition->set(
            $this->offsetPosition->x + $view->position->x,
            $this->offsetPosition->y + $view->position->y
        );
        $outerWidth = $view->position->x + $view->size->width;
        if ($this->size->width < $outerWidth) {
            $this->size->width = $outerWidth;
        }
        $outerHeight = $view->position->y + $view->size->height;
        if ($this->size->height < $outerHeight) {
            $this->size->height = $outerHeight;
        }

        foreach ($view->child as $child) {
            $view->setChildOffset($child);
        }
    }

    /**
     * @return Location
     */
    public function getPosition()
    {
        return clone $this->offsetPosition;
    }

    /**
     * @return Dimension
     */
    public function getSize()
    {
        return clone $this->size;
    }

    public function render(Output $output)
    {
        foreach ($this->child as $view) {
            if ($view->hidden) {
                continue;
            }
            $output->setContext($output->newContext());
            $view->render($output);
        }
    }
}
