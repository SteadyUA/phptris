<?php
namespace Tet\Stage;

use Tet\Argument\AbstractOption;
use Tet\Argument\ArgumentInterface;
use Tet\Argument\ArgumentSpec;
use Tet\Argument\Command;

class ShowHelpChain extends AbstractChain
{
    protected function handle()
    {
        echo <<<HELP
Usage:
  phptris [options]
  phptris start [--port=<port>] [options]
  phptris connect --addr=<addr> [--port=<port>]

Options:
  --spawn={follow|center}     Spawn position. (default is follow)
  --kick={srs|cultris2|nes}   Wall kick logic. (default is srs)
                                srs - Super Rotation System wall kick
                                cultris2 - Cultris 2 wall kick logic
                                nes - No wall kick. Like Nintendo tetris.
  --rotation={srs|nes}        Block rotation logic. (default is srs)
                                srs - Super Rortation Systsm rotation.
                                nes - Nintendo tetris rotation.
  --gravity={native|cascade}  Line clear gravity. (default is native)
                                native - Basic tetris gravity.
                                cascade - Cascade line clear gravity.
  --random={bag7|tgm2|plain}  Random generator logic. (default is tgm2)
                                bag7 - 7 pieces bag logic.
                                tgm2 - Tetris The Grand Master 2 logic.
                                plain - Plain random generator.

  --cols=<amount>   Playfield cells wide. From 5 to 20. (default is 10)
  --rows=<amount>   Playfield cells tall. From 7 to 40. (default is 20)
  --hold={yes|no}   Enable hold block. (default is yes)
  --next=<amount>   Next block queue length. From 1 to 3. (default is 3)
  --ghost={yes|no}  Show ghost piece. (default is yes)
  --drop={yes|no}   Show hard drop animation. (default is yes)
  --are=<frames>    Appearance delay. From 0 to 60 (default is 30)
  --lock=<frames>   Lock delay. From 0 to 60 (default is 30)
  --clear=<frames>  Line clear delay. From 0 to 60 (default is 40)
  --begin=<frames>  First level drop delay. From 0 to 60 (default is 30)
  --end=<frames>    Last level drop delay. From 0 to 60 (default is 2)

Commands:
  start    Start network game.
  connect  Connect to the network game.

Arguments:
  <addr>    Network ip address.
  <port>    Network port number.
  <amount>  Integer amount of value.
  <frames>  Integer amount of speed. 1 frame = 1/60 second.

HELP;
    }
}
