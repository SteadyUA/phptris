<?php
namespace Tet\Process;

use Tet\Game\InitStatus;
use Tet\Net\ServerInterface;

class ProcessInitAccept extends AbstractProcess
{
    private $server;
    private $initStatus;

    public function __construct(ServerInterface $server, InitStatus $initStatus)
    {
        $this->server = $server;
        $this->initStatus = $initStatus;
    }

    /**
     * @throws \Exception
     */
    public function run()
    {
        if (false == $this->initStatus->isWait()) {
            return;
        }

        $socket = $this->server->accept();
        if (null !== $socket) {
            $this->initStatus->ready($socket);
        }
    }
}
