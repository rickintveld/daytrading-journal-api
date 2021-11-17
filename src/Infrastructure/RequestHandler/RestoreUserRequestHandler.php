<?php

namespace App\Infrastructure\RequestHandler;

use App\Application\Command\RestoreUserCommand;
use App\Common\Exception\InvalidArgumentException;
use App\Common\Interfaces\Command;

/**
 * @package App\Infrastructure\RequestHandler
 */
class RestoreUserRequestHandler extends RequestHandler implements UserRequestHandlerInterface
{
    /**
     * @param array<int> $payload
     * @throws \Exception
     */
    protected function validatePayload(array $payload): void
    {
        if (!array_key_exists('identifier', $payload)) {
            throw new InvalidArgumentException('No identifier was found in the payload');
        }
    }

    /**
     * @param array<int> $payload
     * @return \App\Common\Interfaces\Command
     * @throws \Exception
     */
    protected function createCommand(array $payload): Command
    {
        return new RestoreUserCommand($payload['identifier']);
    }

    /**
     * @param int $requestType
     * @return bool
     */
    protected function supports(int $requestType): bool
    {
        return RequestHandler::USER_RESTORE_TYPE === $requestType;
    }
}
