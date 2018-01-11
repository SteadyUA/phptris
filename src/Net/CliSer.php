<?php
namespace Tet\Net;

class CliSer implements ServerInterface, ClientInterface
{
    private $sock;

    /**
     * For server
     * @param string $address
     * @param int $port
     */
    public function start(string $address, int $port)
    {
        $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (false === $sock) {
            throw new SocketErrorException($sock);
        }
        socket_set_option($sock, SOL_SOCKET, SO_REUSEADDR, 1);
        if (false === socket_bind($sock, $address, $port)) {
            throw new SocketErrorException($this->sock);
        }
        if (false === socket_listen($sock, 1)) {
            throw new SocketErrorException($this->sock);
        }
        socket_set_nonblock($sock);
        $this->sock = $sock;
    }

    /**
     * For server
     */
    public function shutdown()
    {
        if (null === $this->sock) {
            return;
        }
        socket_shutdown($this->sock);
        socket_close($this->sock);
    }

    /**
     * For server
     *
     */
    public function accept()
    {
        $clientSock = socket_accept($this->sock);
        if (false === $clientSock) {
            return null;
        }
        socket_set_nonblock($clientSock);

        return new SocketIo($clientSock);
    }

    /**
     * For clients
     * @param string $address
     * @param int $port
     * @return SocketIo
     */
    public function connect(string $address, int $port): SocketIo
    {
        $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (false == $sock) {
            throw new SocketErrorException($sock);
        }
        if (false == @socket_connect($sock, $address, $port)) {
            throw new SocketErrorException($sock);
        }
        socket_set_nonblock($sock);

        return new SocketIo($sock);
    }
}
