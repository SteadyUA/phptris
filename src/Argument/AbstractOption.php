<?php
namespace Tet\Argument;

abstract class AbstractOption extends Argument
{
    const REQUIRED = 1;
    const OPTIONAL = 2;

    private $type = self::OPTIONAL;
    private $default;

    public function __construct(string $name, string $description, int $type = self::OPTIONAL)
    {
        parent::__construct($name, $description);
        $this->type = $type;
    }

    public function isRequired()
    {
        return self::REQUIRED == $this->type;
    }

    public function isOptional()
    {
        return self::OPTIONAL == $this->type;
    }

    public function isGroup()
    {
        return false;
    }

    public function default(string $value)
    {
        $this->default = $value;

        return $this;
    }

    public function getDefault()
    {
        return $this->default;
    }
}
