<?php
namespace Tet\Net;

interface IoInterface
{
    public function read(): string;
    public function write(string $message);
}
