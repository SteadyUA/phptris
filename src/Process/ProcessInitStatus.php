<?php
namespace Tet\Process;

use Tet\Game\InitStatus;

class ProcessInitStatus extends AbstractProcess
{
    protected $output;
    protected $initStatus;

    public function __construct(InitStatus $initStatus)
    {
        $this->initStatus = $initStatus;
    }

    public function run()
    {
        if ($this->initStatus->isError()) {
            $this->initStatus->quit();
            $this->stopProcessor();
        }
        if ($this->initStatus->isReady()) {
            if ($this->initStatus->checkTimer()) {
                $this->stopProcessor();
            }
        }
    }
}
