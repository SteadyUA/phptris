<?php
namespace Tet\Process;

use Tet\Game\InitStatus;
use Tet\Output\Output;
use Tet\View\Font\Font1x1;
use Tet\View\Text;
use Tet\View\ViewVerticalNode;

class ProcessInitOutput extends AbstractProcess
{
    protected $output;
    protected $initStatus;

    private $line;

    public function __construct(Output $output, InitStatus $initStatus)
    {
        $view = new ViewVerticalNode();
        $font = new Font1x1();
        $line = new Text(0, 0, 30, 3, $font);
        $view->addChild($line);
        $this->line = $line;

        $this->output = $output;
        $this->initStatus = $initStatus;
    }

    public function run()
    {
        $this->output->cursor()->set(0, 0);
        switch ($this->initStatus->getStatus()) {
            case InitStatus::STATUS_WAIT:
                $this->line->setText('Waiting player 2 ...');
                break;

            case InitStatus::STATUS_READY:
                $this->line->setText('Start at ' . $this->initStatus->getCountdown());
                break;

            case InitStatus::STATUS_QUIT:
                $this->line->setText('Bue.');
                $this->stopProcessor();
                break;

            case InitStatus::STATUS_ERROR:
                $this->line->setText('Error: ' . $this->initStatus->getError());
                break;
        }
        $this->line->render($this->output);
        $this->output->display();
    }
}
