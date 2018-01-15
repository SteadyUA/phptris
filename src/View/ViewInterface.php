<?php
namespace Tet\View;

use Tet\Output\Output;

interface ViewInterface
{
    public function render(Output $output);
}
