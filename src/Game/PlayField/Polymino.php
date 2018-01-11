<?php
namespace Tet\Game\PlayField;

use Tet\Common\Location;

class Polymino
{
    protected $matrix = [];
    protected $type = '';

    /**
     * @var Location
     */
    protected $location;

    public function __construct($type, $matrix, Location $location)
    {
        $this->location = $location;
        $this->type = $type;
        $this->matrix = $matrix;
    }

    public function __clone()
    {
        $this->location = clone $this->location;
    }

    public function getType()
    {
        return $this->type;
    }

    public function down(CascadePlayField $field)
    {
        foreach ($this->matrix as $point) {
            $x = $this->location->x + $point[0];
            $y = $this->location->y + $point[1];
            $field->setAt($x, $y, null);
        }

        $moved = 0;
        while (true) {
            $this->location->y ++;
            if (!$this->check($field)) {
                $this->location->y--;
                break;
            }
            $moved ++;
        }

        foreach ($this->matrix as $point) {
            $x = $this->location->x + $point[0];
            $y = $this->location->y + $point[1];
            $field->setAt($x, $y, $this);
        }
        return $moved;
    }

    public function check(CascadePlayField $field)
    {
        foreach ($this->matrix as $point) {
            $x = $this->location->x + $point[0];
            $y = $this->location->y + $point[1];
            if ($y >= $field->size()->height) {
                return false;
            }
            $state = $field->get($x, $y);
            if ($state !== null) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param int $atY
     * @param CascadePlayField $field
     * @return null|Polymino
     */
    public function split($atY, CascadePlayField $field)
    {
        $bottom = clone $this;
        foreach ($this->matrix as $i => $point) {
            $x = $this->location->x + $point[0];
            $y = $this->location->y + $point[1];
            if ($y == $atY) {
                unset($bottom->matrix[$i]);
                unset($this->matrix[$i]);
                $field->setAt($x, $y, null);
            } elseif ($y < $atY) {
                unset($bottom->matrix[$i]);
                $field->setAt($x, $y, $this);
            } else {
                unset($this->matrix[$i]);
                $field->setAt($x, $y, null);
                $field->setAt($x, $y, $bottom);
            }
        }
        if (!$bottom->isEmpty()) {
            return $bottom;
        }
        return null;
    }

    public function isEmpty()
    {
        return count($this->matrix) == 0;
    }
}
