<?php

namespace App\Infrastructure\RequestHandler;

use App\Application\Command\UpdateUserCommand;
use App\Common\Exception\InvalidArgumentException;
use App\Common\Interfaces\Command;

/**
 * @package App\Infrastructure\RequestHandler
 */
class UpdateUserRequestHandler extends RequestHandler
{
    /**
     * @param array<string, int|string> $payload
     * @throws \Exception
     */
    protected function validatePayload(array $payload): void
    {
        foreach (['userId', 'email', 'firstName', 'lastName', 'capital', 'password'] as $key) {
            if (!array_key_exists($key, $payload)) {
                throw new InvalidArgumentException(sprintf('No %s was found in the payload', $key));
            }
        }
    }

    /**
     * @param array<string, int|string> $payload
     * @return \App\Common\Interfaces\Command
     * @throws \Exception
     */
    protected function createCommand(array $payload): Command
    {
        return new UpdateUserCommand(
            (int)$payload['userId'],
            $payload['email'],
            $payload['firstName'],
            $payload['lastName'],
            (int)$payload['capital'],
            $payload['password']
        );
    }
}
