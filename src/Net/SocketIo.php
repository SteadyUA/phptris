<?php
namespace Tet\Net;

class SocketIo implements IoInterface
{
    /**
     * @var resource
     */
    protected $socket;

    public function __construct($socket)
    {
        $this->socket = $socket;
    }

    /**
     * @return string
     */
    public function read(): string
    {
        if ($this->socket === null) {
            throw new SocketClosedException();
        }
        $read = [$this->socket];
        $write = null;
        $except = null;
        socket_select($read, $write, $except, 0);
        if (!$read) {
            return '';
        }

        $message = '';
        $readLen = 1024;
        do {
            $buf = socket_read($this->socket, $readLen, PHP_NORMAL_READ);
            if ($buf === false) {
                throw new SocketErrorException($this->socket);
            }
            if ($buf === 0) {
                throw new SocketClosedException();
            }
            $message .= $buf;
        } while (strlen($buf) == $readLen);

        $message = substr($message, 0, -1); // trim \n
        $message = base64_decode($message);

        return $message;
    }

    public function write(string $message)
    {
        if ($this->socket === null) {
            throw new SocketClosedException();
        }
        $message = base64_encode($message) . "\n";
        $length = strlen($message);
        $sent = 0;

        do {
            $message = substr($message, $sent);
            $sent = socket_write($this->socket, $message, $length);
            if ($sent === false) {
                throw new SocketErrorException($this->socket);
            }
            $length -= $sent;
        } while ($sent < $length);
    }

    public function close()
    {
        if ($this->socket !== null) {
            socket_close($this->socket);
        }
        $this->socket = null;
    }
}
