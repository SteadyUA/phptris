<?php
namespace Tet\Process;

use Tet\Game\Event\OnStateUpdateInterface;
use Tet\Game\State\AbstractState;
use Tet\Game\TetrisGame;
use Tet\Input\Input;

class ProcessInput extends AbstractProcess implements OnStateUpdateInterface
{
    /**
     * @var AbstractState
     */
    protected $state;

    /**
     * @var Input
     */
    protected $input;

    public function __construct(TetrisGame $game, Input $input)
    {
        $this->input = $input;
        $game->getEventManager()->subscribe($this);
    }

    public function run()
    {
        foreach ($this->input->read() as $keyPress) {
            switch ($keyPress->getKey()) {
                case 'left':
                    $this->state->moveLeft();
                    break;

                case 'right':
                    $this->state->moveRight();
                    break;

                case 'up':
                    if ($keyPress->isShift()) {
                        $this->state->rotateRight180();
                    } else {
                        $this->state->rotateRight();
                    }
                    break;

                case 'down':
                    $this->state->softDrop();
                    break;

                case ' ':
                    $this->state->hardDrop();
                    break;

                case 'z':
                    if ($keyPress->isShift()) {
                        $this->state->rotateLeft180();
                    } else {
                        $this->state->rotateLeft();
                    }
                    break;

                case 'c':
                    $this->state->hold();
                    break;

                case 'q':
                    $this->stopProcessor();
            }
        }
    }

    public function onStateUpdate(AbstractState $state)
    {
        $this->state = $state;
    }
}
