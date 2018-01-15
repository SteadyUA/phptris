<?php
namespace Tet\Game\Block;

use Tet\Common\Location;

class Block
{
    const TYPE_I = 1;
    const TYPE_O = 2;
    const TYPE_T = 3;
    const TYPE_S = 4;
    const TYPE_Z = 5;
    const TYPE_J = 6;
    const TYPE_L = 7;

    protected $matrix = [];
    protected $type = 0;
    protected $width = 3;

    /**
     * @var Location
     */
    protected $location;

    protected $orientation = 0;

    public function __construct(array $matrix, $type)
    {
        $this->location = new Location();
        $this->matrix = $matrix;
        $this->type = $type;
        $this->findWidth();
    }

    private function findWidth()
    {
        $set = [];
        foreach ($this->getMatrix() as $pixel) {
            $set[$pixel[0]] = 1;
        }
        $this->width = count($set);
    }

    public function __clone()
    {
        $this->location = clone $this->location;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getOrientation()
    {
        return $this->orientation;
    }

    public function setOrientation($orientation)
    {
        $isChanged = $this->orientation != $orientation;
        $this->orientation = $orientation;
        if ($isChanged) {
            $this->findWidth();
        }
    }

    public function location(): Location
    {
        return $this->location;
    }

    public function getMatrix()
    {
        return $this->matrix[$this->orientation];
    }
}
