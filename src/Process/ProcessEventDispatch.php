<?php
namespace Tet\Process;

use Tet\Game\EventManager;

class ProcessEventDispatch extends AbstractProcess
{
    private $eventManager;

    public function __construct(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    public function run()
    {
        $this->eventManager->dispatchEvents();
    }
}
