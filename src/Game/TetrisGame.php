<?php
namespace Tet\Game;

use Tet\Game\Config\Config;
use Tet\Game\State\AbstractState;
use Tet\Game\State\DropState;
use Tet\Game\State\GameOverState;
use Tet\Game\State\HardDropState;
use Tet\Game\State\LineClearState;
use Tet\Game\State\LockState;
use Tet\Game\State\SpawnState;
use Tet\Game\State\YouWinState;
use Tet\Game\Block\Block;
use Tet\Game\PlayField\AbstractPlayField;

class TetrisGame implements GameInterface
{
    /**
     * @var Block
     */
    protected $activeBlock = null;

    /**
     * @var Block
     */
    protected $holdBlock = null;
    protected $holdLocked = false;

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

    /**
     * @param Config $cfg
     * @throws \Exception
     */
    public function __construct(Config $cfg)
    {
        $this->eventManager = new EventManager();

        $this->field = $cfg->getPlayfield();
        $this->nextQueue = new NextQueue($cfg->getRandomizer(), $cfg->getNextAmount(), $cfg->getBlockFactory());
        $this->movement = new Movement($cfg->getWallKick(), $this->field);
        $this->spawnLogic = $cfg->getSpawn($this->field);

        $this->stateDrop = new DropState($this, $cfg->getMin(), $cfg->getMax());
        $this->stateSpawn = new SpawnState($this, $cfg->getAre());
        $this->stateLock = new LockState($this, $cfg->getLock());
        $this->stateLineClear = new LineClearState($this, $cfg->getLine());
        $this->stateHardDrop = new HardDropState($this, $cfg->getDrop());

        $this->stateGameOver = new GameOverState();
        $this->stateYouWin = new YouWinState();
    }

    public function start()
    {
        $this->updateStats(0);
        $this->handleSpawnState();
        $this->eventManager->onGameStart();
    }

    // state getters

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

    // game internal actions
    public function rotateLeft()
    {
        if ($this->movement->rotateLeft($this->activeBlock)) {
            $this->eventManager->onBlockUpdate($this->activeBlock);
        }
    }

    public function rotateLeft180()
    {
        if ($this->movement->rotateLeft($this->activeBlock, 2)) {
            $this->eventManager->onBlockUpdate($this->activeBlock);
        }
    }

    public function rotateRight()
    {
        if ($this->movement->rotateRight($this->activeBlock)) {
            $this->eventManager->onBlockUpdate($this->activeBlock);
        }
    }

    public function rotateRight180()
    {
        if ($this->movement->rotateRight($this->activeBlock, 2)) {
            $this->eventManager->onBlockUpdate($this->activeBlock);
        }
    }

    public function moveLeft()
    {
        if ($this->movement->moveLeft($this->activeBlock)) {
            $this->eventManager->onBlockUpdate($this->activeBlock);
        }
    }

    public function moveRight()
    {
        if ($this->movement->moveRight($this->activeBlock)) {
            $this->eventManager->onBlockUpdate($this->activeBlock);
        }
    }

    public function moveDown()
    {
        if ($this->movement->moveDown($this->activeBlock)) {
            $this->eventManager->onBlockUpdate($this->activeBlock);
            return true;
        }

        return false;
    }

    public function moveUp()
    {
        return $this->movement->moveUp($this->activeBlock);
    }

    public function holdBlock()
    {
        if ($this->holdLocked) {
            return false;
        }
        $this->holdLocked = true;
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
        $this->holdLocked = false;
        $this->eventManager->onBlockLock($this->activeBlock);

        return $this->field->checkField();
    }

    public function activeNextBlock()
    {
        $this->activeBlock = $this->nextQueue->getNext();
        $this->spawnLogic->locateBlock($this->activeBlock);
        $this->eventManager->onSpawnNext($this->activeBlock, $this->nextQueue);
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
