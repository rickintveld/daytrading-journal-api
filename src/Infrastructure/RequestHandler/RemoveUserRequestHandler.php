<?php

namespace App\Infrastructure\RequestHandler;

use App\Application\Command\RemoveUserCommand;
use App\Common\Exception\InvalidUserStateException;
use App\Common\Contracts\Command;
use App\Infrastructure\Contracts\RequestHandler\UserRequestHandlerInterface;

/**
 * @package App\Infrastructure\RequestHandler
 */
class RemoveUserRequestHandler extends RequestHandler implements UserRequestHandlerInterface
{
    /**
     * @param int $requestType
     * @return bool
     */
    public function supports(int $requestType): bool
    {
        return RequestHandler::USER_REMOVE_TYPE === $requestType;
    }

    /**
     * @param array<int> $payload
     * @throws \Exception
     */
    protected function validatePayload(array $payload): void
    {
        if (!array_key_exists('identifier', $payload)) {
            throw new InvalidUserStateException('No identifier was found in the payload');
        }
    }

    /**
     * @param array<int> $payload
     * @return \App\Common\Contracts\Command
     * @throws \Exception
     */
    protected function createCommand(array $payload): Command
    {
        return new RemoveUserCommand($payload['identifier']);
    }
}
