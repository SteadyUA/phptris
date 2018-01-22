<?php
namespace Tet;

use Tet\Game\Config\Config;
use Tet\Game\InitStatus;
use Tet\Input\Input;
use Tet\Net\CliSer;
use Tet\Output\Output;
use Tet\Stage\AbstractChain;
use Tet\Stage\ClientInitChain;
use Tet\Stage\LanGameChain;
use Tet\Stage\ServerInitChain;
use Tet\Stage\SingleGameChain;
use Tet\Stage\TestGameChain;

class Phptris
{
    public function main(array $argv)
    {
        $this->detectMode($argv)->run();
    }

    protected function detectMode(array $argv): AbstractChain
    {
        $mode = $argv[1] ?? '';

        $cfg = new Config();
        $in = new Input();
        $out = new Output();
        $status = new InitStatus();
        $server = new CliSer();

        if ('start' == $mode) {
            $initChain = new ServerInitChain($in, $out, $status, $server);
            $gameChain = new LanGameChain($in, $out, $status, $cfg);
            $initChain->setNext($gameChain);

            return $initChain;
        }

        if ('connect' == $mode) {
            $initChain = new ClientInitChain($in, $out, $server, $status);
            $gameChain = new LanGameChain($in, $out, $status, $cfg);
            $initChain->setNext($gameChain);

            return $initChain;
        }

        if ('test' == $mode) {
            return new TestGameChain($in, $out, __DIR__ . '/../' . $argv[2]);
        }

        return new SingleGameChain($cfg, $in, $out);
    }

    protected function params()
    {
        $argParser = new Argument\Specificator();

        $argParser->command('', '')
            ->optionalGroup('options');
        $argParser->command('start', 'Start network game')
            ->optionalArg('port', 'port')
            ->optionalGroup('options');
        $argParser->command('connect', 'Connect to the network game')
            ->requiredArg('addr', 'addr')
            ->optionalArg('port', 'port');

        $group = $argParser->group('options', 'Options');
        $group->optionOneOf('spawn', 'Spawn position')
            ->value('follow', '')
            ->value('center', '')
            ->default('follow');
        $group->optionOneOf('kick', 'Wall kick logic')
            ->value('srs', 'Super Rotation System wall kick')
            ->value('cultris2', 'Cultris 2 wall kick logic')
            ->value('nes', 'No wall kick. Like Nintendo tetris')
            ->default('srs');
        $group->separator();
        $group->option('cols', 'Playfield cells wide')
            ->argument('amount')
            ->range(5, 20)
            ->default(10);

        $argParser->argument('addr', 'Network ip address');
        $argParser->argument('port', 'Network port number');
        $argParser->argument('amount', 'Integer amount of value');
        $argParser->argument('frames', 'Integer amount of speed. 1 frame = 1/60 second.');
    }
}
