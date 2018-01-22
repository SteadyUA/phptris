<?php
namespace Tet\Stage;

use Tet\Argument\AbstractOption;
use Tet\Argument\ArgumentInterface;
use Tet\Argument\ArgumentSpec;
use Tet\Argument\Command;

class ShowHelpChain extends AbstractChain
{
    private $spec;

    public function __construct(ArgumentSpec $spec)
    {
        $this->spec = $spec;
    }

    protected function handle()
    {
        echo 'Usage:', PHP_EOL;
        foreach ($this->spec->getCommandIterator() as $command) {
            $line = '';
            $name = $command->getName();
            if ($name) {
                $line .= ' ' . $name;
            }
            foreach ($command as $option) {
                $line .= ' ';
                if ($option->isOptional()) {
                    $line .= '[';
                }
                if ($option->isGroup()) {
                    $line .= $option->getName() . '...';
                } else {
                    $line .= '--' . $option->getName() . '=<' . $option->getArgument() . '>';
                }
                if ($option->isOptional()) {
                    $line .= ']';
                }
            }
            $this->echoText('  phptris', $line);
        }
        echo PHP_EOL;

        foreach ($this->spec->getGroupIterator() as $group) {
            echo $group->getDescription(), ':', PHP_EOL;
            $optionCol = $this->getColumn(
                $group->getIterator(),
                function (AbstractOption $option) {
                    return '  --' . $option->getName() . '  ';
                }
            );
            foreach ($group as $i => $option) {
                $this->echoText($optionCol[$i], $option->getDescription());
            }
            echo PHP_EOL;
        }

        echo 'Commands:', PHP_EOL;
        $commandNameCol = $this->getColumn(
            $this->spec->getCommandIterator(),
            function (Command $cmd) {
                return $cmd->getName();
            }
        );
        foreach ($this->spec->getCommandIterator() as $i => $command) {
            if ($command->getName()) {
                $this->echoText('  ' . $commandNameCol[$i] . '  ', $command->getDescription());
            }
        }
        echo PHP_EOL;

        echo 'Arguments:', PHP_EOL;
        $argumentNameCol = $this->getColumn(
            $this->spec->getArgumentIterator(),
            function (ArgumentInterface $argument) {
                return '  <' . $argument->getName() . '>  ';
            }
        );
        foreach ($this->spec->getArgumentIterator() as $i => $argument) {
            $this->echoText($argumentNameCol[$i], $argument->getDescription());
        }
    }

    private function echoText($prefix, $text)
    {
        $prefixLen = strlen($prefix);
        $stub = PHP_EOL . str_repeat(' ', $prefixLen);
        $lines = explode(PHP_EOL, $text);

        $firs = array_shift($lines);
        echo $prefix, wordwrap($firs, 80, $stub);
        foreach ($lines as $line) {
            echo $stub, wordwrap($line, 80, $stub);
        }
        echo PHP_EOL;
    }

    private function getColumn(\Traversable $iterator, \Closure $call)
    {
        $items = [];

        $maxLen = 0;
        foreach ($iterator as $i => $item) {
            $value = $call($item);
            $len = strlen($value);
            if ($len > $maxLen) {
                $maxLen = $len;
            }
            $items[$i] = $value;
        }

        foreach ($items as $i => $item) {
            $len = strlen($item);
            if ($len < $maxLen) {
                $items[$i] = $item . str_repeat(' ', $maxLen - $len);
            }
        }

        return $items;
    }
}
