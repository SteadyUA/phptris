<?php
namespace Tet\Game;

use Tet\Game\Config\Config;
use Tet\Game\State\AbstractState;
use Tet\Game\State\LineClearState;
use Tet\Game\State\NetState;
use Tet\Game\Block\Block;
use Tet\Game\PlayField\AbstractPlayField;

class TetrisGameNet implements GameInterface
{
    /**
     * @var Block
     */
    protected $activeBlock = null;

    /**
     * @var Block
     */
    protected $holdBlock = null;

    protected $field;
    protected $nextQueue;

    public $level = 0;
    public $maxLevel = 11;
    protected $linesCount = 0;
    protected $score = 0;

    /**
     * @var State\AbstractState
     */
    protected $gameState;
    protected $spawnLogic;
    protected $eventManager;

    protected $stateSpawn;
    protected $stateDrop;
    protected $stateGameOver;
    protected $stateLock;
    protected $stateLineClear;
    protected $stateHardDrop;
    protected $stateYouWin;

    /**
     * @var Movement
     */
    protected $movement;

    private $blockFactory;

    /**
     * @param Config $cfg
     * @throws \Exception
     */
    public function __construct(Config $cfg)
    {
        $this->field = $cfg->getPlayfield();
        $this->nextQueue = new NextQueue($cfg->getRandomizer(), $cfg->getNextAmount(), $cfg->getBlockFactory());
        $this->blockFactory = $cfg->getBlockFactory();

        $this->stateDrop = new NetState(AbstractState::NAME_DROP);
        $this->stateSpawn = new NetState(AbstractState::NAME_SPAWN);
        $this->stateLock = new NetState(AbstractState::NAME_LOCK);
        $this->stateLineClear = new LineClearState($this, $cfg->getLine());
        $this->stateHardDrop = new NetState(AbstractState::NAME_HARD_DROP);

        $this->stateGameOver = new NetState(AbstractState::NAME_GAME_OVER);
        $this->stateYouWin = new NetState(AbstractState::NAME_YOU_WIN);

        $this->eventManager = new EventManager();
    }

    public function start()
    {
        $this->handleSpawnState();
        $this->activeBlock = $this->nextQueue->getNext();
        $this->eventManager->onSpawnNext($this->activeBlock, $this->nextQueue);
        $this->updateStats(0);
        $this->eventManager->onGameStart();
    }

    public function handleSpawnState()
    {
        $this->setState($this->stateSpawn)->handle();
    }

    public function handleDropState()
    {
        $this->setState($this->stateDrop)->handle();
    }

    public function handleLockState()
    {
        $this->setState($this->stateLock)->handle();
    }

    public function handleLineClearState()
    {
        $this->setState($this->stateLineClear)->handle();
    }

    public function handleGameOverState()
    {
        $this->setState($this->stateGameOver)->handle();
    }

    public function handleYouWinState()
    {
        $this->setState($this->stateYouWin)->handle();
    }

    public function handleHardDropState()
    {
        $this->setState($this->stateHardDrop)->handle();
    }

    protected function setState(AbstractState $state)
    {
        if ($this->gameState === $state) {
            return $state;
        }
        $this->gameState = $state;
        $this->eventManager->onStateUpdate($state);

        return $state;
    }

    public function holdBlock()
    {
        $block = $this->holdBlock;
        $this->holdBlock = clone $this->activeBlock;
        if ($block !== null) {
            $this->nextQueue->putNext($block);
        }
        $this->eventManager->onHoldBlock($this->holdBlock);

        return true;
    }

    public function placeOnField()
    {
        $this->field->placeOnField($this->activeBlock);
        $this->eventManager->onBlockLock($this->activeBlock);

        return $this->field->checkField();
    }

    /**
     * @param int $type
     * @param $orientation
     * @param $x
     * @param $y
     * @throws \Exception
     */
    public function spawnBlock(int $type, $orientation, $x, $y)
    {
        $block = $this->blockFactory->get($type);
        $block->setOrientation($orientation);
        $block->location()->set($x, $y);
        $this->activeBlock = $block;
        $this->eventManager->onSpawnNext($this->activeBlock, $this->nextQueue);
    }

    public function updateBlock($orientation, $x, $y)
    {
        $this->activeBlock->setOrientation($orientation);
        $this->activeBlock->location()->set($x, $y);
        $this->eventManager->onBlockUpdate($this->activeBlock);
    }

    public function updateStats($linesRemoved)
    {
        $this->linesCount += $linesRemoved;

        // increase level
        $newLevel = ceil(($this->linesCount + 1) / 10);
        if ($newLevel != $this->level) {
            $this->level = $newLevel;
        }

        // increase score
        $i = 0;
        $amount = 0;
        while ($i < $linesRemoved) {
            $amount = $amount * 2 + 100;
            $i ++;
        }
        $this->score += $amount;

        $this->eventManager->onStatChanges($this->level, $this->linesCount, $this->score);
    }

    // getters
    public function getField(): AbstractPlayField
    {
        return $this->field;
    }

    public function getNextQueue(): NextQueue
    {
        return $this->nextQueue;
    }

    public function getHoldBlock()
    {
        return $this->holdBlock;
    }

    /**
     * @return EventManager
     */
    public function getEventManager(): EventManager
    {
        return $this->eventManager;
    }
}
