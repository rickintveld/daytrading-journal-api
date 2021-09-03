<?php

namespace App\Infrastructure\RequestHandler;

use App\Common\Interfaces\Command;
use App\Common\Interfaces\CommandBus;
use Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Infrastructure\RequestHandler
 */
abstract class RequestHandler
{
    /** @var CommandBus */
    private $commandBus;

    /**
     * @param \App\Common\Interfaces\CommandBus $commandBus
     */
    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function handle(Request $request): void
    {
        $payload = $request->toArray();

        $this->validatePayload($payload);

        $this->commandBus->dispatch($this->createCommand($payload));
    }

    /**
     * @param array $payload
     */
    abstract protected function validatePayload(array $payload): void;

    /**
     * @param array $payload
     * @return \App\Common\Interfaces\Command
     */
    abstract protected function createCommand(array $payload): Command;
}
