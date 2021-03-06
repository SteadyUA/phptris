<?php
namespace Tet;

use Tet\Argument\ArgumentSpec;
use Tet\Game\Config\Config;
use Tet\Game\InitStatus;
use Tet\Input\Input;
use Tet\Net\CliSer;
use Tet\Output\Output;
use Tet\Stage\AbstractChain;
use Tet\Stage\ClientInitChain;
use Tet\Stage\LanGameChain;
use Tet\Stage\ServerInitChain;
use Tet\Stage\ShowHelpChain;
use Tet\Stage\SingleGameChain;
use Tet\Stage\TestGameChain;

class App
{
    const VERSION = '0.2.0';

    public function main(array $argv)
    {
        $this->detectMode($argv)->run();
    }

    protected function detectMode(array $argv): AbstractChain
    {
        $cfg = new Config();
        $in = new Input();
        $out = new Output();
        $status = new InitStatus();
        $server = new CliSer();

        switch ($argv[1] ?? '') {
            case 'play':
                return new SingleGameChain($cfg, $in, $out);

            case 'start':
                $initChain = new ServerInitChain($in, $out, $status, $server);
                $gameChain = new LanGameChain($in, $out, $status, $cfg);
                $initChain->setNext($gameChain);
                return $initChain;

            case 'connect':
                $initChain = new ClientInitChain($in, $out, $server, $status);
                $gameChain = new LanGameChain($in, $out, $status, $cfg);
                $initChain->setNext($gameChain);
                return $initChain;

            case 'test':
                return new TestGameChain($in, $out, __DIR__ . '/../' . $argv[2]);
        }

        return new ShowHelpChain(self::VERSION);
    }
}
