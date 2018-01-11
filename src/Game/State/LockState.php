<?php
namespace Tet\Game\State;

use Tet\Game\TetrisGame;

class LockState extends AbstractState
{
    private $game;

    public function __construct(TetrisGame $game, int $frames)
    {
        $this->game = $game;
        $this->initTimer($frames);
    }

    protected function doProcess()
    {
        if (false == $this->game->moveDown()) {
            $deletedLines = $this->game->placeOnField();
            if ($deletedLines) {
                $this->game->updateStats($deletedLines);
                $this->game->handleLineClearState();
                return;
            }
            $this->game->handleSpawnState();
            return;
        }
        $this->game->handleDropState();
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
        $this->doProcess();
    }

    public function getName(): int
    {
        return self::NAME_LOCK;
    }
}
