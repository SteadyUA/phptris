<?php
namespace Tet\Net;

class SocketErrorException extends \RuntimeException implements SocketExceptionInterface
{
    public function __construct($sock, \Throwable $previous = null)
    {
        if ($sock === null) {
            $code = socket_last_error();
        } else {
            $code = socket_last_error($sock);
        }
        $message = socket_strerror($code);
        parent::__construct($message, $code, $previous);
    }
}
