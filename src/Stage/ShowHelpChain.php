<?php
namespace Tet\Stage;

use Tet\Output\Context;

class ShowHelpChain extends AbstractChain
{
    private $version = '';

    public function __construct($version)
    {
        $this->version = $version;
    }

    protected function handle()
    {
        $text = <<<HELP
ANSI terminal **Tetris** game.
phptris version {$this->version}

**Usage:**
  **phptris play** [**--config=**<**ini file**>]
#  **phptris start** [**--port**=<**port**>] [**--config**=<**ini file**>]
#  **phptris connect** **--addr**=<**addr**> [**--port**=<**port**>]

**Commands:**
  **play**    Start single player.
#  **start**   Start network game.
#  **connect** Connect to the network game.

**Arguments:**
  <**ini file**> Configuration file (such as default.ini)
#  <**addr**>     Network ip address.
#  <**port**>     Network port number.

HELP;
        $parts = explode('**', preg_replace('/\#.+\n/', '', $text));
        $context = new Context();
        for ($i = 0; $i < count($parts); $i ++) {
            $context->light(($i % 2) === 1);
            echo $context->toString();
            echo $parts[$i];
        }
    }
}
