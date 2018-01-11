<?php
namespace Tet\Process;

use Tet\Game\Event\OnStateUpdateInterface;
use Tet\Game\GameInterface;
use Tet\Game\State\AbstractState;

class ProcessGameState extends AbstractProcess implements OnStateUpdateInterface
{
    /**
     * @var AbstractState
     */
    protected $state;

    public function __construct(GameInterface $game)
    {
        $game->getEventManager()->subscribe($this);
    }

    public function run()
    {
        $this->state->process();
        if (AbstractState::NAME_YOU_WIN == $this->state->getName()
            || AbstractState::NAME_GAME_OVER == $this->state->getName()
        ) {
            $this->stopProcessor();
        }
    }

    public function onStateUpdate(AbstractState $state)
    {
        $this->state = $state;
    }
}
