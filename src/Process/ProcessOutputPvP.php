<?php
namespace Tet\Process;

use Tet\Output\Output;
use Tet\View\ViewHorizontalNode;

class ProcessOutputPvP extends AbstractProcess
{
    protected $viewNode;
    protected $output;
    protected $playerOneOut;
    protected $playerTwoOut;

    public function __construct(ProcessOutput $playerOne, ProcessOutput $playerTwo, Output $output)
    {
        $this->viewNode = new ViewHorizontalNode();
        $this->viewNode->addChild($playerOne->getScreen());
        $this->viewNode->addChild($playerTwo->getScreen());
        $this->output = $output;
        $this->playerOneOut = $playerOne;
        $this->playerTwoOut = $playerTwo;
    }

    public function run()
    {
        $this->playerOneOut->animation();
        $this->playerTwoOut->animation();
        $this->output->display();
    }
}
