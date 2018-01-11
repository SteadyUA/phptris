<?php
namespace Tet\Process;

abstract class AbstractProcess
{
    private $isStop = false;

    protected function stopProcessor()
    {
        $this->isStop = true;
    }

    public function isStopped()
    {
        return $this->isStop;
    }

    abstract public function run();
}
