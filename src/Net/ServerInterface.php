<?php
namespace Tet\Net;

interface ServerInterface
{
    public function start(string $address, int $port);

    /**
     * @return SocketIo|null
     */
    public function accept();
    public function shutdown();
}
