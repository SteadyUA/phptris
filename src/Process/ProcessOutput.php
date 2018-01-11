<?php
namespace Tet\Process;

use Tet\Common\Timer;
use Tet\Game\Event\OnBlockUpdateInterface;
use Tet\Game\Event\OnGameStartInterface;
use Tet\Game\Event\OnHoldBlockInterface;
use Tet\Game\Event\OnSpawnNextInterface;
use Tet\Game\Event\OnStateUpdateInterface;
use Tet\Game\Event\OnStatChangesInterface;
use Tet\Game\GameInterface;
use Tet\Game\NextQueue;
use Tet\Game\State\AbstractState;
use Tet\Game\Block\Block;
use Tet\Output\Output;
use Tet\View\Screen;

class ProcessOutput extends AbstractProcess implements
    OnStateUpdateInterface,
    OnBlockUpdateInterface,
    OnStatChangesInterface,
    OnGameStartInterface,
    OnSpawnNextInterface,
    OnHoldBlockInterface
{
    protected $screen;
    protected $output;
    protected $game;

    protected $startTime = 0;
    protected $timeTimer;
    protected $lineClearProgress;

    /**
     * @var AbstractState
     */
    protected $state;

    public function __construct(GameInterface $game, Output $output)
    {
        $this->screen = new Screen($game);
        $this->game = $game;
        $this->output = $output;

        $eventManager = $game->getEventManager();
        $eventManager->subscribe($this);

        $this->timeTimer = new Timer(60);
    }

    public function getScreen(): Screen
    {
        return $this->screen;
    }

    public function animation()
    {
        if (AbstractState::NAME_LINE_CLEAR == $this->state->getName()) {
            $lineClearProgress = $this->state->getTimer()->getProgress(5);
            if ($this->lineClearProgress !== $lineClearProgress) {
                $this->screen->playFieldView->setLineClearProgress($lineClearProgress);
                $this->screen->playFieldView->render($this->output);
            }
            $this->lineClearProgress = $lineClearProgress;
        }
        if ($this->timeTimer->check()) {
            $this->screen->timeView->setTime(time() - $this->startTime);
            $this->screen->timeView->render($this->output);
            $this->timeTimer->set();
        }
    }

    public function run()
    {
        $this->animation();
        $this->output->display();
    }

    public function onStateUpdate(AbstractState $state)
    {
        $this->state = $state;
        switch ($state->getName()) {
            case AbstractState::NAME_SPAWN:
            case AbstractState::NAME_LOCK:
                $this->screen->blockView->opacity(2);
                $this->screen->fieldGroup->render($this->output);
                break;

            case AbstractState::NAME_DROP:
            case AbstractState::NAME_HARD_DROP:
                $this->screen->blockView->opacity(1);
                $this->screen->fieldGroup->render($this->output);
                break;

            case AbstractState::NAME_LINE_CLEAR:
                $this->screen->blockView->opacity(1);
                $this->screen->blockView->render($this->output);
                break;

            case AbstractState::NAME_YOU_WIN:
                $this->screen->win->show();
                $this->screen->win->render($this->output);
                break;

            case AbstractState::NAME_GAME_OVER:
                $this->screen->over->show();
                $this->screen->over->render($this->output);
                break;
        }
    }

    public function onBlockUpdate(Block $block)
    {
        $this->screen->blockView->setBlock($block);
        $this->screen->shadowView->setBlock($block);
        $this->screen->fieldGroup->render($this->output);
    }

    public function onStatChanges(int $level, int $lines, int $score)
    {
        $this->screen->scoreView->setText($score);
        $this->screen->scoreView->render($this->output);
        $this->screen->levelView->setText($level);
        $this->screen->levelView->render($this->output);
        $this->screen->linesView->setText($lines);
        $this->screen->linesView->render($this->output);
    }

    public function onGameStart()
    {
        $this->startTime = time();
        $this->screen->timeView->setTime(0);
        $this->timeTimer->set();
        $this->screen->render($this->output);
    }

    public function onSpawnNext(Block $block, NextQueue $nextQueue)
    {
        for ($i = 0; $i < $nextQueue->getLength(); $i ++) {
            $this->screen->nextViews[$i]->setBlock($nextQueue->getBlockAt($i));
            $this->screen->nextViews[$i]->render($this->output);
        }
        $this->onBlockUpdate($block);
    }

    public function onHoldBlock(Block $block)
    {
        $this->screen->holdView->setBlock($block);
        $this->screen->holdView->render($this->output);
    }
}
