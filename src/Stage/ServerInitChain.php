<?php
namespace Tet\Stage;

use Tet\Input\Input;
use Tet\Net\ServerInterface;
use Tet\Output\Output;
use Tet\Game\InitStatus;
use Tet\Process\ProcessInitAccept;
use Tet\Process\ProcessInitInput;
use Tet\Process\ProcessInitOutput;
use Tet\Process\ProcessInitStatus;
use Tet\Process\Processor;

class ServerInitChain extends AbstractChain
{
    private $out;
    private $in;
    private $status;
    private $server;

    public function __construct(Input $in, Output $out, InitStatus $status, ServerInterface $server)
    {
        $this->in = $in;
        $this->out = $out;
        $this->status = $status;
        $this->server = $server;
    }

    protected function handle()
    {
        $this->server->start('127.0.0.1', 1111);

        $processor = new Processor();
        $processor->addProcess(new ProcessInitAccept($this->server, $this->status));
        $processor->addProcess(new ProcessInitInput($this->in, $this->status));
        $processor->addProcess(new ProcessInitOutput($this->out, $this->status));
        $processor->addProcess(new ProcessInitStatus($this->status));
        $processor->run();

        if ($this->status->isQuit()) {
            $this->disableNext();
        }
    }
}
