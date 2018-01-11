<?php
namespace Tet\Game;

use Tet\Common\Timer;
use Tet\Net\SocketIo;

class InitStatus
{
    const STATUS_WAIT = 1;
    const STATUS_QUIT = 2;
    const STATUS_READY = 3;
    const STATUS_ERROR = 4;

    private $status = self::STATUS_WAIT;

    /**
     * @var SocketIo|null
     */
    private $socketIo;
    private $countdown;
    private $errorText;

    public function __construct()
    {
        $this->countdown = new Timer(60 * 3);
    }

    public function isWait()
    {
        return $this->status == self::STATUS_WAIT;
    }

    public function isQuit()
    {
        return $this->status === self::STATUS_QUIT;
    }

    public function isReady()
    {
        return $this->status === self::STATUS_READY;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getSocketIo()
    {
        return $this->socketIo;
    }

    public function checkTimer()
    {
        return $this->countdown->check();
    }

    public function getCountdown()
    {
        return 3 - $this->countdown->getProgress(3);
    }

    public function ready(SocketIo $socketIo)
    {
        $this->socketIo = $socketIo;
        $this->socketIo->write('ready');
        $this->status = self::STATUS_READY;
        $this->countdown->set();
    }

    public function error($errorText)
    {
        $this->errorText = $errorText;
        $this->status = self::STATUS_ERROR;
    }

    public function isError()
    {
        return self::STATUS_ERROR == $this->status;
    }

    public function getError()
    {
        return $this->errorText;
    }

    public function quit()
    {
        $this->status = self::STATUS_QUIT;
        if ($this->socketIo) {
            $this->socketIo->write('quit');
        }
    }
}
