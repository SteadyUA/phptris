<?php
namespace Tet\Net;

interface ClientInterface
{
    public function connect(string $address, int $port): SocketIo;
}
