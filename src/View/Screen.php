<?php
namespace Tet\View;

use Tet\Game\GameInterface;
use Tet\View\Font\Font1x1;
use Tet\View\Font\Font4x3;
use Tet\View\Font\Font6x4;

class Screen extends ViewNode
{
    protected $game;

    /**
     * @var BlockView
     */
    public $blockView;

    /**
     * @var BlockView
     */
    public $shadowView;

    /**
     * @var PlayFieldView
     */
    public $playFieldView;

    /**
     * @var BlockView
     */
    public $holdView;

    public $scoreView;
    public $levelView;
    public $linesView;
    public $timeView;
    public $fieldGroup;

    /**
     * @var BlockView[]
     */
    public $nextViews = [];

    public $over;
    public $win;

    public function __construct(GameInterface $game)
    {
        parent::__construct();

        $layout = new ViewHorizontalNode();

        // left
        $left = new ViewVerticalNode(1, 1, 11);
        $layout->addChild($left);

        $title = new Title('Hold', 0, 0, 12);
        $left->addChild($title);

        $holdPreview = new BlockPreview(12);
        $left->addChild($holdPreview);
        $this->holdView = $holdPreview;

        $font3 = new Font4x3();
        $title = new Title('Lines', 0, 5, 12);
        $left->addChild($title);
        $linesCount = new Text(-1, 0, 12, 3, $font3);
        $left->addChild($linesCount);
        $this->linesView = $linesCount;

        $title = new Title('Score', 0, 1, 12);
        $left->addChild($title);
        $scoreCount = new Text(-1, 0, 12, 3, new Font1x1());
        $left->addChild($scoreCount);
        $this->scoreView = $scoreCount;

        //center
        $center = new ViewNode(0, 0);
        $layout->addChild($center);

        $this->playFieldView = $playFieldView = new PlayFieldView($game->getField());
        $this->blockView = new BlockView();
        $this->shadowView = new BlockShadowView($game->getField());

        $fieldSize = $playFieldView->getSize();
        $boxView = new PlayFieldBorderView(
            0,
            1,
            $fieldSize->width + 2,
            $fieldSize->height + 2
        );

        $center->addChild($boxView);

        $this->fieldGroup = new ViewNode(1, 2);
        $center->addChild($this->fieldGroup);

        $this->fieldGroup->addChild($playFieldView);
        $this->fieldGroup->addChild($this->shadowView);
        $this->fieldGroup->addChild($this->blockView);

        // right
        $right = new ViewVerticalNode(0, 0, 11);
        $layout->addChild($right);
        $title = new Title('Next', 0, 1, 12);
        $right->addChild($title);

        $nextQueue = new ViewVerticalNode(1, 0, 10);
        $right->addChild($nextQueue);

        $nextPreview = new BlockPreview(10);
        $nextQueue->addChild($nextPreview);
        $this->nextViews[] = $nextPreview;

        $nextQueueLength = $game->getNextQueue()->getLength();
        for ($i = 1; $i < $nextQueueLength; $i ++) {
            $next = new BlockPreviewMini();
            $nextQueue->addChild($next);
            $this->nextViews[] = $next;
        }

        $title = new Title('Level', 0, 1, 12);
        $right->addChild($title);
        $levelCount = new Text(0, 0, 12, 3, $font3);
        $right->addChild($levelCount);
        $this->levelView = $levelCount;

        $title = new Title('Time', 0, 1, 12);
        $right->addChild($title);
        $this->timeView = new TimeView(0, 0, 11, 3);
        $right->addChild($this->timeView);

        $this->addChild($layout);

        $font6x4 = new Font6x4();
        $over = new WindowView(10, 4, 27, 10);
        $over->addChild(new Text(0, 0, 26, 4, $font6x4, 'GAME'));
        $over->addChild(new Text(0, 4, 26, 4, $font6x4, 'OVER'));
        $over->hide();
        $this->addChild($over);
        $this->over = $over;

        $win = new WindowView(12, 4, 24, 10);
        $win->addChild(new Text(0, 0, 22, 4, $font6x4, 'YOU'));
        $win->addChild(new Text(0, 4, 22, 4, $font6x4, 'WIN'));
        $win->hide();
        $this->addChild($win);
        $this->win = $win;
    }
}
