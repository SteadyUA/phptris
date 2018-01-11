<?php
namespace Tet\Output;

use Tet\Common\Vector;

class Frame
{
    /**
     * @var string[][]
     */
    public $char;

    /**
     * @var string[][]
     */
    public $context;

    public function __construct()
    {
        $this->char = new Vector(new Vector(''));
        $this->context = new Vector(new Vector(''));
    }

}
