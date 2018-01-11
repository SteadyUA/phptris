<?php
namespace Tet\Stage;

use Tet\Input\Input;
use Tet\Output\Output;
use Tet\Game\Config\Config;
use Tet\Game\TetrisGame;
use Tet\Process\ProcessEventDispatch;
use Tet\Process\ProcessGameState;
use Tet\Process\ProcessInput;
use Tet\Process\Processor;
use Tet\Process\ProcessOutput;
use Tet\Process\ProcessSleep;

class SingleGameChain extends AbstractChain
{
    private $cfg;
    private $in;
    private $out;

    public function __construct(Config $cfg, Input $in, Output $out)
    {
        $this->cfg = $cfg;
        $this->out = $out;
        $this->in = $in;
    }

    protected function handle()
    {
        $game = new TetrisGame($this->cfg);
        $game->start();

        $processor = new Processor();
        $processor->addProcess(new ProcessEventDispatch($game->getEventManager()));
        $processor->addProcess(new ProcessInput($game, $this->in));
        $processor->addProcess(new ProcessGameState($game));
        $processor->addProcess(new ProcessOutput($game, $this->out));
        $processor->addProcess(new ProcessSleep());
        $processor->run();
    }
}
