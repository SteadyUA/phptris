<?php
namespace Tet\Game\WallKick;

class Cultris2WallKick implements WallKickInterface
{
    public function getTestList($blockType, $fromOrientation, $toOrientation): array
    {
        return [[0, 0], [-1, 0], [+1, 0], [0, -1], [-1, -1], [+1, -1], [-2, 0], [+2, 0]];
    }
}
