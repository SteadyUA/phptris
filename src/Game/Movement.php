<?php
namespace Tet\Game;

use Tet\Game\PlayField\AbstractPlayField;
use Tet\Game\WallKick\WallKickInterface;
use Tet\Game\Block\Block;

class Movement
{
    /**
     * @var AbstractPlayField
     */
    protected $playField;

    /**
     * @var WallKickInterface
     */
    protected $wallKick;

    public function __construct(WallKickInterface $wallKick, AbstractPlayField $playField)
    {
        $this->playField = $playField;
        $this->wallKick = $wallKick;
    }

    private function checkMove(Block $block, $offsetX, $offsetY)
    {
        $location = $block->location();
        $prevX = $location->x;
        $prevY = $location->y;
        $location->move($offsetX, $offsetY);
        if ($this->check($block)) {
            return true;
        }
        $location->x = $prevX;
        $location->y = $prevY;

        return false;
    }

    private function checkKick(Block $block, $fromOrientation, $toOrientation)
    {
        $block->setOrientation($toOrientation);
        $testList = $this->wallKick->getTestList($block->getType(), $fromOrientation, $toOrientation);
        foreach ($testList as $offset) {
            if ($this->checkMove($block, $offset[0], $offset[1] * -1)) {
                return true;
            }
        }
        $block->setOrientation($fromOrientation);

        return false;
    }

    // actions
    public function rotateRight(Block $block, $times = 1): bool
    {
        $orientation = $block->getOrientation();

        do {
            $prevOrientation = $orientation;
            $orientation += 1;
            if ($orientation > 3) {
                $orientation = 0;
            }
        } while (-- $times > 0);

        return $this->checkKick($block, $prevOrientation, $orientation);
    }

    public function rotateLeft(Block $block, $times = 1): bool
    {
        $orientation = $block->getOrientation();

        do {
            $prevOrientation = $orientation;
            $orientation -= 1;
            if ($orientation < 0) {
                $orientation = 3;
            }
        } while (-- $times > 0);

        return $this->checkKick($block, $prevOrientation, $orientation);
    }

    public function moveLeft(Block $block, $times = 1): bool
    {
        return $this->checkMove($block, -1 * $times, 0);
    }

    public function moveRight(Block $block, $times = 1): bool
    {
        return $this->checkMove($block, $times, 0);
    }

    public function moveDown(Block $block): bool
    {
        return $this->checkMove($block, 0, 1);
    }

    public function moveUp(Block $block): bool
    {
        return $this->checkMove($block, 0, -1);
    }

    public function check(Block $block)
    {
        $matrix = $block->getMatrix();
        $location = $block->location();
        $playFieldSize = $this->playField->size();
        foreach ($matrix as $point) {
            $x = $location->x + $point[ 0];
            $y = $location->y + $point[1];
            if ($x < 0 || $x >= $playFieldSize->width
                || $y >= $playFieldSize->height) {
                return false;
            }
            $state = $this->playField->get($x, $y);
            if ($state !== null) {
                return false;
            }
        }

        return true;
    }
}
