<?php
namespace Tet\Process;

class Processor
{
    /**
     * @var \SplObjectStorage|AbstractProcess[]
     */
    protected $processes = [];

    public function __construct()
    {
        $this->processes = new \SplObjectStorage();
    }

    public function addProcess(AbstractProcess $process)
    {
        $this->processes->attach($process);
    }

    public function run()
    {
        if (0 == $this->processes->count()) {
            return;
        }
        $isStopped = false;
        do {
            foreach ($this->processes as $process) {
                $process->run();
                if (false == $isStopped && $process->isStopped()) {
                    $isStopped = true;
                }
            }
        } while (false == $isStopped);
    }
}
