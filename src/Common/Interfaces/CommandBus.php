<?php

namespace App\Common\Interfaces;

/**
 * @package App\Common\Interfaces
 */
interface CommandBus
{
    /**
     * @param \App\Common\Interfaces\Command $command
     */
    public function dispatch(Command $command): void;
}
