<?php
namespace Tet;

class OptionParser
{
    private $knownOptions = [];
    private $unknownOptions = [];

    const TYPE_VALUE = 1;
    const TYPE_FLAG = 2;

    public function registerOption(string $name, int $type)
    {
        $this->knownOptions[$name] = $type;
    }

    public function parseOptions(array $argv)
    {
        $result = [];
        $this->unknownOptions = [];
        foreach ($argv as $argument) {
            if (substr($argument, 0, 2) != '--') {
                continue;
            }

            $separatorPos = strpos($argument, '=');
            if (false !== $separatorPos) {
                $optionName = substr($argument, 2, $separatorPos - 2);
                $optionValue = substr($argument, $separatorPos + 1);
            } else {
                $optionName = substr($argument, 2);
                $optionValue = true;
            }

            if (!isset($this->knownOptions[$optionName])) {
                $this->unknownOptions[] = $optionName;
                continue;
            }

            $result[$optionName] = $optionValue;
        }

        return $result;
    }

    public function getUnknown()
    {
        return $this->unknownOptions;
    }
}
