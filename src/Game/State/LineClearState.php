<?php
namespace Tet\Game\State;

use Tet\Game\GameInterface;

class LineClearState extends AbstractState
{
    private $game;

    public function __construct(GameInterface $game, int $frames)
    {
        $this->game = $game;
        $this->initTimer($frames);
    }

    protected function doProcess()
    {
        $this->game->getField()->clearLines();
        if ($this->game->level >= $this->game->maxLevel) {
            $this->game->handleYouWinState();
            return;
        }

        $deletedLines = $this->game->getField()->checkField();
        if ($deletedLines) {
            $this->game->updateStats($deletedLines);
            $this->getTimer()->set();
            return;
        }

        $this->game->handleSpawnState();
    }

    public function getName(): int
    {
        return self::NAME_LINE_CLEAR;
    }
}
