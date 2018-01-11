<?php
namespace Tet\Process;

use Tet\Game\InitStatus;
use Tet\Input\Input;

class ProcessInitInput extends AbstractProcess
{
    /**
     * @var Input
     */
    private $input;
    private $initStatus;

    public function __construct(Input $input, InitStatus $initStatus)
    {
        $this->input = $input;
        $this->initStatus = $initStatus;
    }

    public function run()
    {
        foreach ($this->input->read() as $key) {
            switch ($key->getKey()) {
                case 'q':
                case 'x':
                    $this->initStatus->quit();
            }
        }
    }
}
