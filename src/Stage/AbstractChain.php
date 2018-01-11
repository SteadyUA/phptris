<?php
namespace Tet\Stage;

abstract class AbstractChain
{
    /**
     * @var AbstractChain
     */
    private $nextChain;

    public function setNext(AbstractChain $nextChain)
    {
        $this->nextChain = $nextChain;
    }

    protected function disableNext()
    {
        $this->nextChain = null;
    }

    abstract protected function handle();

    public function run()
    {
        $this->handle();
        if ($this->nextChain) {
            $this->nextChain->handle();
        }
    }
}
