<?php

namespace App\Common\Bus;

use App\Common\Contracts\Command;
use App\Common\Contracts\CommandBus;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @package App\Common\Bus
 */
final class MessengerCommandBus implements CommandBus
{
    private MessageBusInterface $commandBus;

    /**
     * @param \Symfony\Component\Messenger\MessageBusInterface $commandBus
     */
    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param \App\Common\Contracts\Command $command
     */
    public function dispatch(Command $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
