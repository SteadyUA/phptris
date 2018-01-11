<?php
namespace Tet\Game;

use Tet\Game\PlayField\AbstractPlayField;

interface GameInterface
{
    public function getEventManager(): EventManager;
    public function getField(): AbstractPlayField;
    public function getNextQueue(): NextQueue;
}
