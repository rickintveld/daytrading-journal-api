<?php

namespace App\Common\Bus;

use App\Common\Interfaces\Command;
use App\Common\Interfaces\CommandBus;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @package App\Common\Bus
 */
final class MessengerCommandBus implements CommandBus
{
    /** @var \Symfony\Component\Messenger\MessageBusInterface */
    private $commandBus;

    /**
     * @param \Symfony\Component\Messenger\MessageBusInterface $commandBus
     */
    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param \App\Common\Interfaces\Command $command
     */
    public function dispatch(Command $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
