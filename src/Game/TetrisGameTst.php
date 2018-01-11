<?php
namespace Tet\Game;

use Tet\Game\Block\Block;
use Tet\Game\Config\ConfigTst;
use Tet\Game\PlayField\NativePlayField;

class TetrisGameTst extends TetrisGame
{
    private $caseList;
    private $factory;

    /**
     * @var NativePlayField
     */
    protected $field;

    /**
     * TetrisGameTst constructor.
     * @param ConfigTst $cfg
     * @param $caseList
     * @throws \Exception
     */
    public function __construct(ConfigTst $cfg, $caseList)
    {
        parent::__construct($cfg);
        $this->caseList = $caseList;
        $this->factory = $cfg->getBlockFactory();
    }

    /**
     * @throws \Exception
     */
    public function activeNextBlock()
    {
        $case = array_shift($this->caseList);
        $this->activeBlock = $this->factory->get($case['type']);
        $this->activeBlock->setOrientation($case['orientation']);
        foreach ($case['data'] as $y => $line) {
            foreach (str_split($line) as $x => $char) {
                if ($char == '@' || $char == '+') {
                    $this->activeBlock->location()->set($x, $y);
                }
                $dot = $char == '-' ? null : ($char == '+' || $char == '*' ? Block::TYPE_O : null);
                $this->field->set($x, $y, $dot);
            }
        }
        $this->eventManager->onSpawnNext($this->activeBlock, $this->nextQueue);
        $this->eventManager->onBlockUpdate($this->activeBlock);
    }

    public function handleGameOverState()
    {
        $this->setState(empty($this->caseList) ? $this->stateGameOver : $this->stateSpawn)->handle();
    }
}
