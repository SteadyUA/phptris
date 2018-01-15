<?php
namespace Tet\Game;

use Tet\Game\Event\OnBlockLockInterface;
use Tet\Game\Event\OnBlockUpdateInterface;
use Tet\Game\Event\OnGameStartInterface;
use Tet\Game\Event\OnHoldBlockInterface;
use Tet\Game\Event\ListenerInterface;
use Tet\Game\Event\OnSpawnNextInterface;
use Tet\Game\Event\OnStateUpdateInterface;
use Tet\Game\Event\OnStatChangesInterface;
use Tet\Game\State\AbstractState;
use Tet\Game\Block\Block;

class EventManager implements
    OnGameStartInterface,
    OnHoldBlockInterface,
    OnSpawnNextInterface,
    OnStatChangesInterface,
    OnBlockUpdateInterface,
    OnStateUpdateInterface,
    OnBlockLockInterface
{
    private $listeners;
    private $queue;

    public function __construct()
    {
        $this->listeners = new \SplObjectStorage();
        $this->queue = new \SplQueue();
    }

    public function subscribe(ListenerInterface $listener)
    {
        $this->listeners->attach($listener);
    }

    public function dispatchEvents()
    {
        while (false == $this->queue->isEmpty()) {
            list($listenerClass, $method, $params) = $this->queue->dequeue();
//            echo "$listenerClass, $method\n";
            foreach ($this->listeners as $listener) {
                if ($listener instanceof $listenerClass) {
                    $listener->$method(...$params);
                }
            }
        }
    }

    public function onGameStart()
    {
        $this->queue->enqueue([
            OnGameStartInterface::class,
            'onGameStart',
            []
        ]);
    }

    public function onHoldBlock(Block $block)
    {
        $this->queue->enqueue([
            OnHoldBlockInterface::class,
            'onHoldBlock',
            [$block]
        ]);
    }

    public function onSpawnNext(Block $block, NextQueue $nextQueue)
    {
        $this->queue->enqueue([
            OnSpawnNextInterface::class,
            'onSpawnNext',
            [$block, $nextQueue]
        ]);
    }

    public function onStatChanges(int $level, int $lines, int $score)
    {
        $this->queue->enqueue([
            OnStatChangesInterface::class,
            'onStatChanges',
            [$level, $lines, $score]
        ]);
    }

    public function onBlockUpdate(Block $block)
    {
        $this->queue->enqueue([
            OnBlockUpdateInterface::class,
            'onBlockUpdate',
            [$block]
        ]);
    }

    public function onStateUpdate(AbstractState $state)
    {
        $this->queue->enqueue([
            OnStateUpdateInterface::class,
            'onStateUpdate',
            [$state]
        ]);
    }

    public function onBlockLock(Block $block)
    {
        $this->queue->enqueue([
            OnBlockLockInterface::class,
            'onBlockLock',
            [$block]
        ]);
    }
}
