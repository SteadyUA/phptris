<?php
namespace Tet\Game\State;

class NetState extends AbstractState
{
    private $name = '';

    public function __construct(string $name, int $frames = 0)
    {
        $this->name = $name;
        $this->initTimer($frames);
    }

    public function doProcess()
    {
    }

    public function getName(): int
    {
        return self::NAME_DROP;
    }
}
