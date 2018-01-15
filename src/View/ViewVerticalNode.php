<?php
namespace Tet\View;

class ViewVerticalNode extends ViewNode
{
    protected function setChildOffset(ViewNode $view)
    {
        if ($view->prev) {
            $view->offsetPosition->set(
                $this->offsetPosition->x + $view->position->x,
                $view->prev->offsetPosition->y + $view->prev->size->height + $view->position->y
            );
        } else {
            $view->offsetPosition->set(
                $this->offsetPosition->x + $view->position->x,
                $this->offsetPosition->y + $view->position->y
            );
        }

        $outerHeight = ($this->last->offsetPosition->y + $this->last->size->height) - $this->offsetPosition->y;
        if ($this->size->height < $outerHeight) {
            $this->size->height = $outerHeight;
        }

        $outerWidth = $view->size->width + $view->position->x;
        if ($this->size->width < $outerWidth) {
            $this->size->width = $outerWidth;
        }

        foreach ($view->child as $child) {
            $view->setChildOffset($child);
        }
    }
}
