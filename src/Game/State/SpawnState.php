<?php
namespace Tet\Game\State;

use Tet\Game\TetrisGame;

class SpawnState extends AbstractState
{
    private $game;

    public function __construct(TetrisGame $game, int $frames)
    {
        $this->game = $game;
        $this->initTimer($frames);
    }

    public function handle()
    {
        $this->game->activeNextBlock();
        parent::handle();
    }

    protected function doProcess()
    {
        if (false == $this->game->moveDown()) {
            $this->game->handleGameOverState();
        } else {
            $this->game->handleDropState();
        }
    }

    public function hold()
    {
        if ($this->game->holdBlock()) {
            $this->handle();
        }
    }

    public function rotateRight()
    {
        $this->game->rotateRight();
    }

    public function rotateRight180()
    {
        $this->game->rotateRight180();
    }

    public function rotateLeft()
    {
        $this->game->rotateLeft();
    }

    public function rotateLeft180()
    {
        $this->game->rotateLeft180();
    }

    public function moveRight()
    {
        $this->game->moveRight();
    }

    public function moveLeft()
    {
        $this->game->moveLeft();
    }

    public function softDrop()
    {
        $this->doProcess();
    }

    public function hardDrop()
    {
        if (false == $this->game->moveDown()) {
            $this->game->handleGameOverState();
        } else {
            $this->game->handleHardDropState();
        }
    }

    public function getName(): int
    {
        return self::NAME_SPAWN;
    }
}
