<?php
namespace Tet\Game\Event;

use Tet\Game\State\AbstractState;

interface OnStateUpdateInterface extends ListenerInterface
{
    public function onStateUpdate(AbstractState $state);
}
