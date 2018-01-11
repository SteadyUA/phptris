<?php
namespace Tet\Game\WallKick;

interface WallKickInterface
{
    public function getTestList($blockType, $fromOrientation, $toOrientation): array;
}
