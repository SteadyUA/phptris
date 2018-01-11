<?php
namespace Tet\Game\Event;

interface OnStatChangesInterface extends ListenerInterface
{
    public function onStatChanges(int $level, int $lines, int $score);
}
