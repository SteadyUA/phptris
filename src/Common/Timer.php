<?php
namespace Tet\Common;

class Timer
{
    protected $timeout = .0;
    protected $delay = .0;
    protected $startTime = .0;

    public function __construct(int $frames)
    {
        $this->delay = 1 / 60 * $frames;
    }

    public function set()
    {
        $this->startTime = microtime(true);
        $this->timeout = $this->startTime + $this->delay;
    }

    public function check()
    {
        return microtime(true) >= $this->timeout;
    }

    public function getTimeout()
    {
        $time = $this->timeout - microtime(true);

        return $time > 0 ? $time * 1000000 : 0;
    }

    public function getProgress($step = 100): int
    {
        $percent = ($this->timeout - $this->startTime) / $step;

        return round((microtime(true) - $this->startTime) / $percent);
    }
}
