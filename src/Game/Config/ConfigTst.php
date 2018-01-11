<?php
namespace Tet\Game\Config;

use Tet\Game\Block\Block;
use Tet\Game\Randomizer\StubRandomizer;

class ConfigTst extends Config
{
    protected $rotation = 'srs';
    public $wallKick = 'srs';
    protected $clear = 'native';
    protected $height = 7;
    protected $width = 5;
    protected $random = 'tgm2';
    protected $spawn = 'follow';
    protected $next = 3;
    protected $hold = true;
    protected $ghost = true;
    protected $drop = true;
    protected $are = 3000;
    protected $lock = 3000;
    protected $line = 40;
    protected $max = 3000;
    protected $min = 20;

    private $case = [];

    const MAX_FRAMES = 100000;

    public function getRandomizer()
    {
        return new StubRandomizer(Block::TYPE_S);
    }

    public function getCaseList()
    {
        return $this->case;
    }
}
