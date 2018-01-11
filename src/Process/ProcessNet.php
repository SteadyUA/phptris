<?php
namespace Tet\Process;

use SplQueue;
use Tet\Game\Block\Block;
use Tet\Game\Event\OnBlockLockInterface;
use Tet\Game\Event\OnBlockUpdateInterface;
use Tet\Game\Event\OnHoldBlockInterface;
use Tet\Game\Event\OnSpawnNextInterface;
use Tet\Game\Event\OnStateUpdateInterface;
use Tet\Game\NetAction;
use Tet\Game\NextQueue;
use Tet\Game\State\AbstractState;
use Tet\Game\TetrisGame;
use Tet\Game\TetrisGameNet;
use Tet\Net\IoInterface;

class ProcessNet extends AbstractProcess implements
    OnStateUpdateInterface,
    OnSpawnNextInterface,
    OnBlockUpdateInterface,
    OnHoldBlockInterface,
    OnBlockLockInterface
{
    /**
     * @var IoInterface
     */
    private $client;
    private $outputQueue;
    private $inputQueue;
    private $remoteGame;
    private $game;
    private $prevAction;

    public function __construct(IoInterface $client, TetrisGame $game, TetrisGameNet $remoteGame)
    {
        $this->game = $game;
        $this->game->getEventManager()->subscribe($this);
        $this->remoteGame = $remoteGame;
        $this->client = $client;
        $this->outputQueue = new SplQueue();
        $this->inputQueue = new SplQueue();
    }

    /**
     * @throws \Exception
     */
    public function run()
    {
        while ($message = $this->client->read()) {
            $message = json_decode($message, true);
            if (NetAction::ACTION_LOCK == $this->prevAction) {
                $this->remoteGame->getField()->clearLines();
            }
            switch ($message['action']) {
                case NetAction::ACTION_SPAWN:
                    $block = $message['block'];
                    $this->remoteGame->spawnBlock($block['t'], $block['o'], $block['x'], $block['y']);
                    $this->remoteGame->handleSpawnState();
                    break;
                case NetAction::ACTION_UPDATE_BLOCK:
                    $block = $message['block'];
                    $this->remoteGame->updateBlock($block['o'], $block['x'], $block['y']);
                    $this->remoteGame->handleDropState();
                    break;
                case NetAction::ACTION_HOLD:
                    $this->remoteGame->holdBlock();
                    break;
                case NetAction::ACTION_PRE_LOCK:
                    $this->remoteGame->handleLockState();
                    break;
                case NetAction::ACTION_LOCK:
                    $block = $message['block'];
                    $this->remoteGame->updateBlock($block['o'], $block['x'], $block['y']);
                    $deletedLines = $this->remoteGame->placeOnField();
                    if ($deletedLines) {
                        $this->remoteGame->updateStats($deletedLines);
                        $this->remoteGame->handleLineClearState();
                    }
            }
            $this->prevAction = $message['action'];
        }

        while ($this->outputQueue->count() > 0) {
            $message = $this->outputQueue->dequeue();
            $this->client->write(json_encode($message));
        }
    }

    public function onStateUpdate(AbstractState $state)
    {
        if ($state->getName() == AbstractState::NAME_LOCK) {
            $this->outputQueue->enqueue(['action' => NetAction::ACTION_PRE_LOCK]);
        }
    }

    public function onSpawnNext(Block $block, NextQueue $nextQueue)
    {
        $this->outputQueue->enqueue(['action' => NetAction::ACTION_SPAWN, 'block' => $this->blockToData($block)]);
    }

    private function blockToData(Block $block)
    {
        return [
            't' => $block->getType(),
            'o' => $block->getOrientation(),
            'x' => $block->location()->x,
            'y' => $block->location()->y,
        ];
    }

    public function onBlockUpdate(Block $block)
    {
        $this->outputQueue->enqueue(['action' => NetAction::ACTION_UPDATE_BLOCK, 'block' => $this->blockToData($block)]);
    }

    public function onHoldBlock(Block $block)
    {
        $this->outputQueue->enqueue(['action' => NetAction::ACTION_HOLD, 'block' => $this->blockToData($block)]);
    }

    public function onBlockLock(Block $block)
    {
        $this->outputQueue->enqueue(['action' => NetAction::ACTION_LOCK, 'block' => $this->blockToData($block)]);
    }
}
