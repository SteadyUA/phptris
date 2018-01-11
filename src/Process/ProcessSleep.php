<?php
namespace Tet\Process;

use Tet\Common\Timer;

class ProcessSleep extends AbstractProcess
{
    private $timer;

    public function __construct()
    {
        $this->timer = new Timer(1);
        $this->timer->set();
    }

    public function run()
    {
        $timeout = $this->timer->getTimeout();
        if ($timeout > 0) {
            usleep($timeout);
        }
        $this->timer->set();
    }
}
