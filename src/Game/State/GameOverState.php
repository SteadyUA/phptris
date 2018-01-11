<?php
namespace Tet\Game\State;

class GameOverState extends AbstractState
{
    public function __construct()
    {
        $this->initTimer(0);
    }

    public function doProcess()
    {
        // nothing to do
    }

    public function getName(): int
    {
        return self::NAME_GAME_OVER;
    }
}
