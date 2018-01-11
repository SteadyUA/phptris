<?php
namespace Tet\Game;

use SplDoublyLinkedList;
use Tet\Game\Randomizer\RandomizerInterface;
use Tet\Game\Block\Block;
use Tet\Game\Block\AbstractBlockFactory;

class NextQueue
{
    /**
     * @var SplDoublyLinkedList
     */
    protected $queue;

    /**
     * @var RandomizerInterface
     */
    protected $random;

    protected $length = 0;

    /**
     * @var AbstractBlockFactory
     */
    protected $factory;

    public function __construct(RandomizerInterface $random, $length, AbstractBlockFactory $factory)
    {
        $this->factory = $factory;
        $this->random = $random;
        $this->length = $length;
        $this->queue = new SplDoublyLinkedList();

        for ($i = 0; $i < $length; $i ++ ) {
            $this->queue->push($this->makeNextBlock());
        }
    }

    public function getLength()
    {
        return $this->length;
    }

    public function getNext(): Block
    {
        $this->queue->push($this->makeNextBlock());
        $next = $this->queue->shift();

        return $next;
    }

    public function putNext(Block $block)
    {
        $this->queue->unshift($block);
    }

    public function getBlockAt($num): Block
    {
        return $this->queue->offsetGet($num);
    }

    private function makeNextBlock(): Block
    {
        return $this->factory->get($this->random->value());
    }
}
