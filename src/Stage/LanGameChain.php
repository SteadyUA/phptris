<?php
namespace Tet\Stage;

use Tet\Input\Input;
use Tet\Output\Output;
use Tet\Game\Config\Config;
use Tet\Game\InitStatus;
use Tet\Game\TetrisGame;
use Tet\Game\TetrisGameNet;
use Tet\Process\ProcessEventDispatch;
use Tet\Process\ProcessGameState;
use Tet\Process\ProcessInput;
use Tet\Process\ProcessNet;
use Tet\Process\Processor;
use Tet\Process\ProcessOutput;
use Tet\Process\ProcessOutputPvP;
use Tet\Process\ProcessSleep;

class LanGameChain extends AbstractChain
{
    private $cfg;
    private $status;
    private $in;
    private $out;

    public function __construct(Input $in, Output $out, InitStatus $status, Config $cfg)
    {
        $this->cfg = $cfg;
        $this->in = $in;
        $this->out = $out;
        $this->status = $status;
    }

    protected function handle()
    {
        $socketIo = $this->status->getSocketIo();
        $game = new TetrisGame($this->cfg);
        $remote = new TetrisGameNet($this->cfg);
        $game->start();
        $remote->start();

        $processor = new Processor();
        $processor->addProcess(new ProcessNet($socketIo, $game, $remote));
        $processor->addProcess(new ProcessEventDispatch($game->getEventManager()));
        $processor->addProcess(new ProcessEventDispatch($remote->getEventManager()));
        $processor->addProcess(new ProcessInput($game, $this->in));
        $processor->addProcess(new ProcessGameState($game));
        $processor->addProcess(new ProcessOutputPvP(
            new ProcessOutput($game, $this->out),
            new ProcessOutput($remote, $this->out),
            $this->out
        ));
        $processor->addProcess(new ProcessSleep());
        $processor->run();
    }
}
