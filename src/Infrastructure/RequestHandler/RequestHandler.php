<?php

namespace App\Infrastructure\RequestHandler;

use App\Common\Contracts\Command;
use App\Common\Contracts\CommandBus;
use Symfony\Component\HttpFoundation\Request;

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

    public function __construct(private CommandBus $commandBus)
    {
    }

    public function handle(Request $request): void
    {
        $payload = $request->toArray();

        $this->validatePayload($payload);

        $this->commandBus->dispatch($this->createCommand($payload));
    }

    abstract public function supports(int $requestType): bool;

    abstract protected function validatePayload(array $payload): void;

    abstract protected function createCommand(array $payload): Command;
}
