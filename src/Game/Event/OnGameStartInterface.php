<?php
namespace Tet\Game\Event;

interface OnGameStartInterface extends ListenerInterface
{
    public function onGameStart();
}
