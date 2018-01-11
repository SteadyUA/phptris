<?php
namespace Tet\Game\State;

use Tet\Game\Event\OnStatChangesInterface;
use Tet\Game\TetrisGame;

class DropState extends AbstractState implements OnStatChangesInterface
{
    protected $minDropDelay = 2; // frames
    protected $maxDropDelay = 30; // frames

    /**
     * @var TetrisGame
     */
    protected $game;

    public function __construct(TetrisGame $game, $minDropDelay, $maxDropDelay)
    {
        $this->minDropDelay = $minDropDelay;
        $this->maxDropDelay = $maxDropDelay;
        $this->game = $game;
        $this->game->getEventManager()->subscribe($this);
    }

    public function doProcess()
    {
        if ($this->game->moveDown()) {
            $this->getTimer()->set();
        } else {
            $this->game->handleLockState();
        }
    }

    public function hold()
    {
        if ($this->game->holdBlock()) {
            $this->game->handleSpawnState();
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
        $this->game->handleHardDropState();
    }

    public function onStatChanges(int $level, int $lines, int $score)
    {
        $perLevel = ($this->maxDropDelay - $this->minDropDelay) / ($this->game->maxLevel - 1);
        $this->initTimer($this->maxDropDelay - $level * $perLevel);
    }

    public function getName(): int
    {
        return self::NAME_DROP;
    }
}
