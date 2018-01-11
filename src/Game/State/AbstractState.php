<?php
namespace Tet\Game\State;

use Tet\Common\Timer;

abstract class AbstractState
{
    const NAME_SPAWN = 1;
    const NAME_DROP = 2;
    const NAME_HARD_DROP = 3;
    const NAME_LOCK = 4;
    const NAME_LINE_CLEAR = 5;
    const NAME_YOU_WIN = 6;
    const NAME_GAME_OVER = 7;

    /**
     * @var Timer
     */
    private $timer;

    abstract public function getName(): int;

    /**
     * @return Timer
     */
    public function getTimer()
    {
        if (null === $this->timer) {
            $this->initTimer(0);
        }

        return $this->timer;
    }

    public function handle()
    {
        if (null !== $this->timer) {
            $this->timer->set();
        }
    }

    public function process()
    {
        if ($this->timer->check()) {
            $this->doProcess();
        }
    }

    public function rotateRight()
    {
    }

    public function rotateRight180()
    {
    }

    public function rotateLeft()
    {
    }

    public function rotateLeft180()
    {
    }

    public function moveRight()
    {
    }

    public function moveLeft()
    {
    }

    public function softDrop()
    {
    }

    public function hardDrop()
    {
    }

    public function hold()
    {
    }

    abstract protected function doProcess();

    protected function initTimer(int $frames)
    {
        $this->timer = new Timer($frames);
    }
}
