<?php
namespace Tet\Common;

class Location
{
    public $x = 0;
    public $y = 0;

    public function __construct($x = 0, $y = 0)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function move($x, $y)
    {
        $this->x += $x;
        $this->y += $y;

        return $this;
    }

    public function set($x, $y)
    {
        $this->x = $x;
        $this->y = $y;

        return $this;
    }

    public function offset(Location $location)
    {
        $this->move($location->x, $location->y);

        return $this;
    }

    public function copy(Location $location)
    {
        $this->x = $location->x;
        $this->y = $location->y;

        return $this;
    }
}
