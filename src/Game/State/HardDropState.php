<?php
namespace Tet\Game\State;

use Tet\Game\TetrisGame;

class HardDropState extends AbstractState
{
    private $game;

    public function __construct(TetrisGame $game, int $frames)
    {
        $this->game = $game;
        $this->initTimer($frames);
    }

    protected function doProcess()
    {
        if ($this->game->moveDown()) {
            $this->getTimer()->set();
            $this->process();
        } else {
            $this->game->handleLockState();
        }
    }

    public function getName(): int
    {
        return self::NAME_HARD_DROP;
    }
}
