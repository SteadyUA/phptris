<?php
namespace Tet\View;

class ViewHorizontalNode extends ViewNode
{
    protected function setChildOffset(ViewNode $view)
    {
        if ($view->prev) {
            $view->offsetPosition->set(
                $view->prev->offsetPosition->x + $view->prev->size->width + $view->position->x,
                $this->offsetPosition->y + $view->position->y
            );
        } else {
            $view->offsetPosition->set(
                $this->offsetPosition->x + $view->position->x,
                $this->offsetPosition->y + $view->position->y
            );
        }
        $outerHeight = $view->size->height + $view->position->y;
        if ($this->size->height < $outerHeight) {
            $this->size->height = $outerHeight;
        }
        $outerWidth = ($this->last->offsetPosition->x + $this->last->size->width) - $this->offsetPosition->x;
        if ($this->size->width < $outerWidth) {
            $this->size->width = $outerWidth;
        }

        foreach ($view->child as $child) {
            $view->setChildOffset($child);
        }
    }
}
