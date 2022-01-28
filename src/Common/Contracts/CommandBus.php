<?php

namespace App\Common\Contracts;

interface CommandBus
{
    /**
     * @param \App\Common\Contracts\Command $command
     */
    public function dispatch(Command $command): void;
}
