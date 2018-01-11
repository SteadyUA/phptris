<?php
namespace Tet\Stage;

use Tet\Game\InitStatus;
use Tet\Input\Input;
use Tet\Net\ClientInterface;
use Tet\Net\SocketErrorException;
use Tet\Output\Output;
use Tet\Process\ProcessInitInput;
use Tet\Process\ProcessInitOutput;
use Tet\Process\ProcessInitStatus;
use Tet\Process\Processor;

class ClientInitChain extends AbstractChain
{
    private $in;
    private $out;
    private $client;
    private $status;

    public function __construct(Input $in, Output $out, ClientInterface $client, InitStatus $status)
    {
        $this->in = $in;
        $this->out = $out;
        $this->client = $client;
        $this->status = $status;
    }

    protected function handle()
    {
        try {
            $socketIo = $this->client->connect('127.0.0.1', 1111);
            $this->status->ready($socketIo);
        } catch (SocketErrorException $ex) {
            $this->status->error($ex->getMessage());
        }

        $processor = new Processor();
        $processor->addProcess(new ProcessInitInput($this->in, $this->status));
        $processor->addProcess(new ProcessInitOutput($this->out, $this->status));
        $processor->addProcess(new ProcessInitStatus($this->status));
        $processor->run();

        if ($this->status->isQuit()) {
            $this->disableNext();
        }
    }
}
