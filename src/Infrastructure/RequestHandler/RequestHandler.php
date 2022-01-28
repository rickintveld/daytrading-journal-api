<?php

namespace App\Infrastructure\RequestHandler;

use App\Common\Contracts\Command;
use App\Common\Contracts\CommandBus;
use Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Infrastructure\RequestHandler
 */
abstract class RequestHandler
{
    public const USER_CREATE_TYPE = 1;
    public const USER_BLOCK_TYPE = 2;
    public const USER_UNBLOCK_TYPE = 3;
    public const USER_REMOVE_TYPE = 4;
    public const USER_RESTORE_TYPE = 5;
    public const USER_UPDATE_TYPE = 6;

    public const PROFIT_ADD_TYPE = 7;
    public const PROFIT_WITHDRAW_TYPE = 8;

    public const REQUEST_TYPES = [
        self::USER_CREATE_TYPE,
        self::USER_BLOCK_TYPE,
        self::USER_UNBLOCK_TYPE,
        self::USER_REMOVE_TYPE,
        self::USER_RESTORE_TYPE,
        self::USER_UPDATE_TYPE,
        self::PROFIT_ADD_TYPE,
        self::PROFIT_WITHDRAW_TYPE
    ];

    private CommandBus $commandBus;

    /**
     * @param \App\Common\Contracts\CommandBus $commandBus
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
     * @param int<RequestHandler::REQUEST_TYPES> $requestType
     * @return bool
     */
    abstract public function supports(int $requestType): bool;

    /**
     * @param array $payload
     */
    abstract protected function validatePayload(array $payload): void;

    /**
     * @param array $payload
     * @return \App\Common\Contracts\Command
     */
    abstract protected function createCommand(array $payload): Command;
}
