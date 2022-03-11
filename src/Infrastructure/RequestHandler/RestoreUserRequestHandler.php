<?php

namespace App\Infrastructure\RequestHandler;

use App\Application\Command\RestoreUserCommand;
use App\Common\Exception\InvalidArgumentException;
use App\Common\Contracts\Command;
use App\Infrastructure\Contracts\RequestHandler\UserRequestHandlerInterface;

class RestoreUserRequestHandler extends RequestHandler implements UserRequestHandlerInterface
{
    public function supports(int $requestType): bool
    {
        return RequestHandler::USER_RESTORE_TYPE === $requestType;
    }

    /**
     * @throws \Exception
     */
    protected function validatePayload(array $payload): void
    {
        if (!array_key_exists('identifier', $payload)) {
            throw new InvalidArgumentException('No identifier was found in the payload');
        }
    }

    /**
     * @throws \Exception
     */
    protected function createCommand(array $payload): Command
    {
        return new RestoreUserCommand($payload['identifier']);
    }
}
