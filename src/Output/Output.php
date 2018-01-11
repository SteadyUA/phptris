<?php
namespace Tet\Output;

use Tet\Common\Location;

class Output
{
    /**
     * @var Location
     */
    protected $cursor = null;

    /**
     * @var Context
     */
    protected $context = null;

    private $maxRowNum = 0;
    private $lastContext = '';

    protected $lastFrame;
    protected $nextFrame;

    public function __construct()
    {
        $this->lastFrame = new Frame();
        $this->nextFrame = new Frame();
        $this->cursor = new Location(0, 0);
        $this->context = new Context();
    }

    public function newContext(): Context
    {
        return new Context();
    }

    public function cursor()
    {
        return $this->cursor;
    }

    /**
     * @param Context $context
     * @return Context
     */
    public function setContext(Context $context = null)
    {
        $oldContext = $this->context;
        $this->context = $context;
        return $oldContext;
    }

    public function getContext()
    {
        return clone $this->context;
    }

    public function write($string)
    {
        $rowNum = $this->cursor->y;
        if ($rowNum < 0 || $string == '') {
            return;
        }
        $context = $this->context->toString();
        for ($i = 0; $i < mb_strlen($string); $i ++) {
            $colNum = $this->cursor->x + $i;
            if ($colNum < 0) {
                continue;
            }
            $this->nextFrame->char[$rowNum][$colNum] = mb_substr($string, $i, 1);
            $this->nextFrame->context[$rowNum][$colNum] = $context;
        }
    }

    public function display()
    {
        $lastCol = 0;
        $lastRow = 0;
        $lastFrame = $this->lastFrame;
        $nextFrame = $this->nextFrame;

        $outBuffer = '';
        foreach ($this->nextFrame->char as $rowNum => $charLine) {
            foreach ($charLine as $colNum => $char) {
                $echo = '';
                $context = $nextFrame->context[$rowNum][$colNum];
                if ($context !== $lastFrame->context[$rowNum][$colNum] ||
                    $char !== $lastFrame->char[$rowNum][$colNum]
                ) {
                    if ($this->lastContext != $context) {
                        $echo .= $context;
                        $this->lastContext = $context;
                    }
                    $echo .= $char;
                }

                // update last frame
                $lastFrame->context[$rowNum][$colNum] = $context;
                $lastFrame->char[$rowNum][$colNum] = $char;

                if ($echo !== '') {
                    if ($lastRow < $rowNum) {
                        $outBuffer .= $this->cursorDown($rowNum - $lastRow);
                        if ($lastCol) {
                            $outBuffer .= "\r";
                            $lastCol = 0;
                        }
                        $lastRow = $rowNum;
                    }
                    if ($lastCol < $colNum) {
                        $outBuffer .= "\033[" . ($colNum - $lastCol) . 'C';
                        $lastCol = $colNum;
                    }
                    $outBuffer .= $echo;
                    $lastCol ++;
                }
            }
        }

        // output
        if ($outBuffer) {
            if ($this->maxRowNum > 0) {
                echo "\033[" . ($this->maxRowNum + 1) . 'A';
            }
            echo $outBuffer, "\n";
            if ($lastCol > 0) {
                echo "\r";
            }
            if ($lastRow < $this->maxRowNum) {
                echo $this->cursorDown($this->maxRowNum - $lastRow);
            }
            $this->maxRowNum = count($this->lastFrame->char) - 1;
        }

        $this->nextFrame = new Frame();
    }

    protected function cursorDown($nlCount)
    {
        if ($nlCount > 6) {
            return "\033[" . $nlCount . 'B';
        } else {
            return str_repeat("\n", $nlCount);
        }
    }
}
