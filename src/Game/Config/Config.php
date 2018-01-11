<?php
namespace Tet\Game\Config;

use Tet\Game\Block\AbstractBlockFactory;
use Tet\Game\Block\Block;
use Tet\Game\Block\NesFactory;
use Tet\Game\Block\SrsFactory;
use Tet\Game\PlayField\AbstractPlayField;
use Tet\Game\PlayField\CascadePlayField;
use Tet\Game\PlayField\NativePlayField;
use Tet\Game\Randomizer\Bag7Randomizer;
use Tet\Game\Randomizer\RandomizerInterface;
use Tet\Game\Randomizer\RawRandomizer;
use Tet\Game\Randomizer\Tgm2Randomizer;
use Tet\Game\Spawn\CenterSpawn;
use Tet\Game\Spawn\FollowSpawn;
use Tet\Game\Spawn\SpawnInterface;
use Tet\Game\WallKick\Cultris2WallKick;
use Tet\Game\WallKick\NoWallKick;
use Tet\Game\WallKick\SrsWallKick;

class Config
{
    protected $rotation = 'srs';
    protected $wallKick = 'srs';
    protected $clear = 'native';
    protected $height = 20;
    protected $width = 10;
    protected $random = 'tgm2';
//    protected $random = 'stub';
    protected $spawn = 'follow';
    protected $next = 3;
    protected $hold = true;
    protected $ghost = true;
    protected $drop = true;
    protected $are = 30;
    protected $lock = 30;
    protected $line = 40;
    protected $max = 30;
    protected $min = 2;

    const MAX_FRAMES = 60;

    /**
     * @param string $fileName
     * @throws \Exception
     */
    public function read($fileName)
    {
        $data = parse_ini_file($fileName);
        if (false === $data) {
            throw new \Exception('Config file not found.');
        }
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * @return AbstractBlockFactory
     * @throws \Exception
     */
    public function getBlockFactory()
    {
        switch ($this->rotation) {
            case 'srs':
                return new SrsFactory();

            case 'nes':
                return new NesFactory();
        }

        throw new \Exception('Unknown rotation system: ' . $this->rotation);
    }

    /**
     * @return Cultris2WallKick|NoWallKick|SrsWallKick
     * @throws \Exception
     */
    public function getWallKick()
    {
        switch ($this->wallKick) {
            case 'srs':
                return new SrsWallKick();

            case 'ct2':
                return new Cultris2WallKick();

            case 'no':
                return new NoWallKick();
        }

        throw new \Exception('Unknown wall kick system: ' . $this->wallKick);
    }

    /**
     * @return AbstractPlayField
     * @throws \Exception
     */
    public function getPlayfield()
    {
        $width = max(min($this->width, 20), 5);
        $height = max(min($this->height, 40), 7);
        switch ($this->clear) {
            case 'cascade':
                return new CascadePlayField($width, $height);

            case 'native':
                return new NativePlayField($width, $height);
        }
        throw new \Exception('Unknown line clear gravity: ' . $this->clear);
    }

    /**
     * @param AbstractPlayField $field
     * @return SpawnInterface
     */
    public function getSpawn(AbstractPlayField $field)
    {
        switch ($this->spawn) {
            case 'center':
                return new CenterSpawn($field);
            case 'follow':
                return new FollowSpawn($field);
        }
        throw new \RuntimeException('Unknown spawn location: ' . $this->spawn);
    }

    /**
     * @return RandomizerInterface
     * @throws \Exception
     */
    public function getRandomizer()
    {
        $startSet = [
            Block::TYPE_I,
            Block::TYPE_T,
            Block::TYPE_J,
            Block::TYPE_L
        ];
        $allValues = [
            Block::TYPE_I,
            Block::TYPE_O,
            Block::TYPE_T,
            Block::TYPE_S,
            Block::TYPE_Z,
            Block::TYPE_J,
            Block::TYPE_L
        ];
        switch ($this->random) {
            case 'raw':
                return new RawRandomizer($allValues);

            case 'bag7':
                return new Bag7Randomizer($startSet, $allValues);

            case 'tgm2':
                $history = [Block::TYPE_Z, Block::TYPE_S, Block::TYPE_Z, Block::TYPE_S];
                return new Tgm2Randomizer($startSet, $allValues, $history);
        }

        throw new \Exception('Unknown random generator: ' . $this->clear);
    }

    public function getNextAmount()
    {
        return max(min($this->next, 3), 1);
    }

    public function getHold()
    {
        return (bool)$this->hold;
    }

    public function getGhost()
    {
        return (bool)$this->ghost;
    }

    public function getDrop()
    {
        return $this->drop ? 1 : 0;
    }

    public function getAre()
    {
        return max(min($this->are, static::MAX_FRAMES), 0);
    }

    public function getLock()
    {
        return max(min($this->lock, static::MAX_FRAMES), 0);
    }

    public function getLine()
    {
        return max(min($this->line, static::MAX_FRAMES), 1);
    }

    public function getMin()
    {
        $min = max(min($this->min, static::MAX_FRAMES), 0);
        $max = $this->getMax();
        if ($min > $max) {
            throw new \RuntimeException('Invalid minimal delay');
        }
        return $min;
    }

    public function getMax()
    {
        return max(min($this->max, self::MAX_FRAMES), 0);
    }
}
