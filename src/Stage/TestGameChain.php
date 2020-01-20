<?php
namespace Tet\Stage;

use Tet\Input\Input;
use Tet\Output\Output;
use Tet\Game\Config\ConfigTst;
use Tet\Game\TetrisGameTst;
use Tet\Process\ProcessEventDispatch;
use Tet\Process\ProcessGameState;
use Tet\Process\ProcessInput;
use Tet\Process\Processor;
use Tet\Process\ProcessOutput;
use Tet\Process\ProcessSleep;

class TestGameChain extends AbstractChain
{
    private $cfg;
    private $in;
    private $out;
    private $caseList;

    public function __construct(Input $in, Output $out, $file)
    {
        $this->cfg = new ConfigTst();
        $this->parseCaseList($file, $this->cfg);
        $this->in = $in;
        $this->out = $out;
    }

    protected function handle()
    {
        $game = new TetrisGameTst($this->cfg, $this->caseList);
        $game->start();

        $processor = new Processor();
        $processor->addProcess(new ProcessEventDispatch($game->getEventManager()));
        $processor->addProcess(new ProcessInput($game, $this->in));
        $processor->addProcess(new ProcessGameState($game));
        $processor->addProcess(new ProcessOutput($game, $this->out));
        $processor->addProcess(new ProcessSleep());
        $processor->run();
    }

    private function parseCaseList($file, ConfigTst $cfg)
    {
        $filePath = $file;
        $caseList = [];
        $case = [];
        $lines = file($filePath);
        $cfg->wallKick = trim(array_shift($lines));
        foreach ($lines as $line) {
            $line = trim($line);
            $char = substr($line, 0, 1);
            if ($char == '=') {
                if ($case) {
                    $caseList[] = $case;
                }
                $case = [
                    'type' => (int) substr($line, 1, 1),
                    'orientation' => (int) substr($line, 2, 1),
                    'test-data' => [],
                ];
                continue;
            }
            $case['test-data'][] = $line;
        }
        if ($case) {
            $caseList[] = $case;
        }

        $this->caseList = $caseList;
    }
}
