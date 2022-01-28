<?php

namespace App\Common\Contracts;

/**
 * @package App\Common\Interfaces
 */
interface CommandBus
{
    /**
     * @param \App\Common\Contracts\Command $command
     */
    public function dispatch(Command $command): void;
}
