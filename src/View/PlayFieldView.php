<?php
namespace Tet\View;

use Tet\Output\Output;
use Tet\Game\PlayField\AbstractPlayField;

class PlayFieldView extends ViewNode
{
    /**
     * @var AbstractPlayField
     */
    protected $field;
    protected $lineClearProgress = 0;
    /**
     * @var BlockColorProvider
     */
    protected $colorProvider;

    public function __construct(AbstractPlayField $field)
    {
        $this->field = $field;
        $this->colorProvider = new BlockColorProvider();
        parent::__construct(0, 0, $field->size()->width * 2, $field->size()->height);
    }

    public function setLineClearProgress($progress)
    {
        $this->lineClearProgress = min($progress, 4);
    }

    /**
     * @param Output $output
     */
    public function render(Output $output)
    {
        $position = $this->getPosition();

        $context = $output->newContext()->light(true);
        $output->setContext($context);

        $cursor = $output->cursor();
        $size = $this->field->size();
        $removedLines = $this->field->getDeletedLines();
        $visibleChar = [
            1 => '██',
            2 => '▓▓',
            3 => '▒▒',
            4 => '░░',
            5 => '  ',
        ];
        for ($x = 0; $x < $size->width; $x++) {
            $cursor->set($x * 2 + $position->x, $position->y - 1);
            $output->write($visibleChar[5]);
        }
        for ($y = 0; $y < $size->height; $y ++) {
            for ($x = 0; $x < $size->width; $x++) {
                $pixel = $this->field->get($x, $y);
                $cursor->set($x * 2 + $position->x, $y + $position->y);
                if ($pixel == null) {
                    $visibility = 5;
                } else {
                    $this->colorProvider->setColor($context, $pixel);
                    if ($removedLines && in_array($y, $removedLines)) {
                        $visibility = $this->lineClearProgress + 1;
                    } else {
                        $visibility = 1;
                    }
                }
                $output->write($visibleChar[$visibility]);
            }
        }
    }
}
