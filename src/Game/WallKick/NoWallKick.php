<?php
namespace Tet\Game\WallKick;

class NoWallKick implements WallKickInterface
{
    public function getTestList($blockType, $fromOrientation, $toOrientation): array
    {
        return [[0, 0]];
    }
}
