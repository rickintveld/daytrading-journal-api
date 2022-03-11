<?php

namespace App\Common\Contracts;

interface CommandBus
{
    public function dispatch(Command $command): void;
}
